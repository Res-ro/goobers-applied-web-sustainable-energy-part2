<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once("settings.php");

/* =========================
   LOGIN PROTECTION
========================= */
if (!isset($_SESSION['username'])) {
    header("Location: sign_in.php");
    exit();
}

$conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Database connection failed");
}

/* =========================
   HUGGING FACE AI SUMMARY
========================= */

function getAISummary($text) {

    $apiKey = "";

    $url = "https://router.huggingface.co/v1/chat/completions";

    $text = substr($text, 0, 1500);

    $payload = json_encode([
        "model" => "meta-llama/Llama-3.1-8B-Instruct",

        "messages" => [
            [
                "role" => "system",
                "content" => "You are an assistant that summarises EOI recruitment data. Write exactly 3 formal, professional sentences using only the data provided. Sentence 1: total EOI count and status breakdown. Sentence 2: geographic distribution covering towns and states. Sentence 3: most common skills listed by applicants. Do NOT add commentary, filler phrases, or any information not present in the data."
            ],
            [
                "role" => "user",
                "content" => $text
            ]
        ],

        "temperature" => 0.2,
        "max_tokens" => 120
    ]);

    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $apiKey",
            "Content-Type: application/json"
        ],


        CURLOPT_TIMEOUT => 25,
        CURLOPT_CONNECTTIMEOUT => 10
    ]);

    $response = curl_exec($ch);

    if ($response === false) {
        $err = curl_error($ch);
        return "AI summary unavailable (cURL error: $err)";
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode !== 200) {
        // FIX DEBUG: show raw API error message to help diagnose further issues
        return "AI summary unavailable (API error $httpCode): " . $response;
    }

    $result = json_decode($response, true);

    // Hugging Face chat format response parsing
    if (isset($result["choices"][0]["message"]["content"])) {
        return trim($result["choices"][0]["message"]["content"]);
    }

    return "AI summary unavailable (invalid response)";
}

/* =========================
   DELETE EOIs
========================= */
$deleteMessage = "";

if (isset($_GET['delete_job'])) {

    if (empty(trim($_GET['delete_job']))) {
        $_SESSION['deleteMessage'] = "<span style='color:red;'>Error: Please enter a job reference to delete.</span>";
        $_SESSION['skipAiRefresh'] = true;
        header("Location: " . strtok($_SERVER['REQUEST_URI'], '?'));
        exit();
    }

    $job = mysqli_real_escape_string($conn, $_GET['delete_job']);

    $checkResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM eoi WHERE ref_number='$job'");
    $checkRow = mysqli_fetch_assoc($checkResult);

    if ($checkRow['total'] == 0) {
        $_SESSION['deleteMessage'] = "<span style='color:red;'>Error: No EOIs found for Job Reference: " . htmlspecialchars($_GET['delete_job']) . "</span>";
        $_SESSION['skipAiRefresh'] = true;
    } else {
        mysqli_query($conn, "DELETE FROM eoi WHERE ref_number='$job'");
        $_SESSION['deleteMessage'] = "Deleted EOIs for Job Reference: " . htmlspecialchars($_GET['delete_job']);
        $_SESSION['skipAiRefresh'] = false;
    }

    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?'));
    exit();
}

if (isset($_SESSION['deleteMessage'])) {
    $deleteMessage = $_SESSION['deleteMessage'];
    unset($_SESSION['deleteMessage']);
}

/* =========================
   UPDATE STATUS
========================= */
$updateMessage = "";

if (isset($_GET['update'])) {

    if (empty(trim($_GET['eoi_id'] ?? ''))) {
        $_SESSION['updateMessage'] = "<span style='color:red;'>Error: Please enter an EOI number to update.</span>";
        $_SESSION['skipAiRefresh'] = true;
    } else {
        $id = mysqli_real_escape_string($conn, $_GET['eoi_id']);
        $status = mysqli_real_escape_string($conn, $_GET['status']);

        $checkResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM eoi WHERE EOInumber='$id'");
        $checkRow = mysqli_fetch_assoc($checkResult);

        if ($checkRow['total'] == 0) {
            $_SESSION['updateMessage'] = "<span style='color:red;'>Error: No EOI found with ID: " . htmlspecialchars($_GET['eoi_id']) . "</span>";
            $_SESSION['skipAiRefresh'] = true;
        } else {
            mysqli_query($conn, "UPDATE eoi SET status='$status' WHERE EOInumber='$id'");
            $_SESSION['updateMessage'] = "EOI #" . htmlspecialchars($_GET['eoi_id']) . " status updated to " . htmlspecialchars($_GET['status']) . ".";
            $_SESSION['skipAiRefresh'] = false;
        }
    }

    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?'));
    exit();
}

if (isset($_SESSION['updateMessage'])) {
    $updateMessage = $_SESSION['updateMessage'];
    unset($_SESSION['updateMessage']);
}

/* =========================
   BASE QUERY
========================= */
$sql = "SELECT * FROM eoi WHERE 1=1";

if (!empty($_GET['search'])) {

    $search = mysqli_real_escape_string($conn, $_GET['search']);

    $sql .= " AND (
        ref_number LIKE '%$search%'
        OR first_name LIKE '%$search%'
        OR last_name LIKE '%$search%'
    )";
}

$allowed_sort = ["EOInumber","ref_number","first_name","last_name","status"];
$sort = "EOInumber";

if (!empty($_GET['sort']) && in_array($_GET['sort'], $allowed_sort)) {
    $sort = $_GET['sort'];
}

$sql .= " ORDER BY $sort";

$result = mysqli_query($conn, $sql);

/* =========================
   STATS
========================= */
$countResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM eoi");
$countRow = mysqli_fetch_assoc($countResult);
$totalEOIs = $countRow['total'];

/* =========================
   LOGIN HISTORY
========================= */
$historyResult = mysqli_query(
    $conn,
    "SELECT username, login_time
     FROM login_history
     WHERE username='{$_SESSION['username']}'
     ORDER BY login_time DESC
     LIMIT 1 OFFSET 1"
);

$lastLogin = mysqli_fetch_assoc($historyResult);


/* =========================
   AI SUMMARY DATA BUILDER
========================= */

$aiText = "";

if ($result && mysqli_num_rows($result) > 0) {

    mysqli_data_seek($result, 0);

    $statusCounts = [];
    $uniqueJobs   = [];
    $townCounts   = [];
    $stateCounts  = [];
    $skillCounts  = [];

    while ($row = mysqli_fetch_assoc($result)) {
        // status
        $s = $row['status'];
        $statusCounts[$s] = ($statusCounts[$s] ?? 0) + 1;

        // jobs
        $uniqueJobs[$row['ref_number']] = true;

        // towns
        $town = trim($row['suburb_or_town']);
        if ($town !== '') {
            $townCounts[$town] = ($townCounts[$town] ?? 0) + 1;
        }

        // states
        $state = trim($row['state']);
        if ($state !== '') {
            $stateCounts[$state] = ($stateCounts[$state] ?? 0) + 1;
        }

        // skills (comma-separated field)
        $skills = array_filter(array_map('trim', explode(',', $row['skills'] ?? '')));
        foreach ($skills as $skill) {
            if ($skill !== '') {
                $skillCounts[$skill] = ($skillCounts[$skill] ?? 0) + 1;
            }
        }
    }

    mysqli_data_seek($result, 0);

    $totalCount  = array_sum($statusCounts);
    $uniqueCount = count($uniqueJobs);

    $statusLine = implode(', ', array_map(
        fn($s, $c) => "$c $s", array_keys($statusCounts), $statusCounts
    ));

    arsort($townCounts);
    $topTowns = implode(', ', array_slice(array_keys($townCounts), 0, 3));

    arsort($stateCounts);
    $topStates = implode(', ', array_slice(array_keys($stateCounts), 0, 3));

    arsort($skillCounts);
    $topSkillsArr = array_slice($skillCounts, 0, 5, true);
    $topSkills = implode(', ', array_map(fn($s, $c) => "$s ($c)", array_keys($topSkillsArr), $topSkillsArr));

    $aiText = "EOI Data Summary:
"
            . "- Total EOIs: $totalCount across $uniqueCount job(s).
"
            . "- Status breakdown: $statusLine.
"
            . "- Most common towns/suburbs: " . ($topTowns ?: "N/A") . ".
"
            . "- Most common states: " . ($topStates ?: "N/A") . ".
"
            . "- Most listed skills: " . ($topSkills ?: "N/A") . ".

"
            . "Write exactly 3 formal, professional sentences summarising the above. "
            . "Cover: (1) total EOIs and status breakdown, (2) geographic distribution by town and state, (3) most common skills. "
            . "Use only the data provided. Do not add commentary or filler.";
}

// Only skip AI call when a search or sort is being performed (update/delete use session flag)
$skipAiRefresh = !empty($_GET['search']) || !empty($_GET['sort']);

if (isset($_SESSION['skipAiRefresh'])) {
    if ($_SESSION['skipAiRefresh']) $skipAiRefresh = true;
    unset($_SESSION['skipAiRefresh']);
}

$isSearchOrSort = $skipAiRefresh;

if ($isSearchOrSort && isset($_SESSION['aiSummary'])) {
    $aiSummary = $_SESSION['aiSummary'];
} else {
    $aiSummary = !empty($aiText) ? getAISummary($aiText) : "No EOI data available to summarise.";
    $_SESSION['aiSummary'] = $aiSummary;
}

include "header.inc";
?>

<title>Goobers - Management</title>
<main>

<div class="manage-container">

    <h1>EOI Management System</h1>

    <!-- AI SUMMARY -->
    <div class="manage-message">
        🤖 Hey <?php echo htmlspecialchars($_SESSION['username']); ?>!
        <br><br>
        <?php echo htmlspecialchars($aiSummary); ?>
    </div>

    <!-- dashboard -->
    <div class="dashboard">

        <div class="manage-message dashboard-box">
            📄 Total EOIs
            <strong><?php echo $totalEOIs; ?></strong>
        </div>

        <div class="manage-message dashboard-box">
            👤 Logged in as:
            <strong><?php echo $_SESSION['username']; ?></strong>
        </div>

        <div class="manage-message dashboard-box">
            🕒 Last Login
            <strong>
                <?php echo $lastLogin ? $lastLogin['login_time'] : "No record"; ?>
            </strong>
        </div>

    </div>

    <!-- search -->
    <h3>Search EOIs</h3>

    <form method="GET">

        <input type="text" name="search"
            placeholder="Enter job reference, first or last name"
            value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">

        <select name="sort">

            <option value="EOInumber" <?php if (($sort ?? '') == "EOInumber") echo "selected"; ?>>
                Sort by EOI ID
            </option>

            <option value="ref_number" <?php if (($sort ?? '') == "ref_number") echo "selected"; ?>>
                Sort by Job Ref
            </option>

            <option value="first_name" <?php if (($sort ?? '') == "first_name") echo "selected"; ?>>
                Sort by First Name
            </option>

            <option value="last_name" <?php if (($sort ?? '') == "last_name") echo "selected"; ?>>
                Sort by Last Name
            </option>

            <option value="status" <?php if (($sort ?? '') == "status") echo "selected"; ?>>
                Sort by Status
            </option>

        </select>

        <input type="submit" value="Search / List All">

    </form>

    <!-- delete -->
    <h3>Delete EOIs</h3>
    <form method="GET">
        <input type="text" name="delete_job" placeholder="Enter job reference">
        <input type="submit" value="Delete">
    </form>
    <?php if ($deleteMessage): ?>
        <p class="manage-message"><?php echo $deleteMessage; ?></p>
    <?php endif; ?>

    <!-- update -->
    <h3>Update Status</h3>
    <form method="GET">
        <input type="text" name="eoi_id" placeholder="Enter EOI Number">

        <select name="status">
            <option>New</option>
            <option>Current</option>
            <option>Final</option>
        </select>

        <input type="submit" name="update" value="Update">
    </form>
    <?php if ($updateMessage): ?>
        <p class="manage-message"><?php echo $updateMessage; ?></p>
    <?php endif; ?>

    <!-- results -->
    <h3>Results</h3>

    <?php if (mysqli_num_rows($result) > 0): ?>

    <div class="manage-table-wrap">
        <table class="manage-table">

            <tr>
                <th>EOI</th>
                <th>Job Ref</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>DOB</th>
                <th>Gender</th>
                <th>Street</th>
                <th>Suburb</th>
                <th>State</th>
                <th>Postcode</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Skills</th>
                <th>Other</th>
                <th>Status</th>
            </tr>

            <?php while ($row = mysqli_fetch_assoc($result)): ?>
            <tr>
                <td><?php echo $row['EOInumber']; ?></td>
                <td><?php echo $row['ref_number']; ?></td>
                <td><?php echo $row['first_name']; ?></td>
                <td><?php echo $row['last_name']; ?></td>
                <td><?php echo $row['date_of_birth']; ?></td>
                <td><?php echo $row['gender']; ?></td>
                <td><?php echo $row['street_address']; ?></td>
                <td><?php echo $row['suburb_or_town']; ?></td>
                <td><?php echo $row['state']; ?></td>
                <td><?php echo $row['postcode']; ?></td>
                <td><?php echo $row['email']; ?></td>
                <td><?php echo $row['phone_number']; ?></td>
                <td><?php echo $row['skills']; ?></td>
                <td><?php echo $row['other_skills']; ?></td>
                <td><?php echo $row['status']; ?></td>
            </tr>
            <?php endwhile; ?>

        </table>
    </div>

    <?php else: ?>
        <p class="manage-message">No EOIs found.</p>
    <?php endif; ?>

</div>

</main>

<?php include "footer.inc"; ?>
