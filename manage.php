<?php
error_reporting(E_ALL); // show all PHP errors
ini_set('display_errors', 1); // enable error display

session_start(); // start session for login tracking
require_once("settings.php"); // load DB settings

/* LOGIN PROTECTION */
if (!isset($_SESSION['username'])) {
    header("Location: sign_in.php"); // redirects to sign in page if the user has not signed in
    exit();
}

$conn = mysqli_connect($host, $dbuser, $dbpass, $dbname); // grabs data from settings.php

if (!$conn) {
    die("Database connection failed");
}

// The following section of code was assisted by ChatGPT (OpenAI, GPT-5.5, April 2026).
// The code was reviewed, tested, and adjusted by the student before submission.
/* AI SUMMARY FUNCTION */
function getAISummary($text) {

    $apiKey = ""; // API key which is used to connect to the Hugging Face endpoint

    $url = "https://router.huggingface.co/v1/chat/completions"; // Hugging Face AI Endpoint (where the responses are sourced from)

    $text = substr($text, 0, 1500); // limit AI input size

    $payload = json_encode([
        "model" => "meta-llama/Llama-3.1-8B-Instruct", // specifices which model to use

        "messages" => [
            [
                "role" => "system", // provides the instructions to the AI
                "content" => "You are an assistant that summarises EOI recruitment data. Write exactly 3 formal, professional sentences." // Provides instructions and criteria which the model must follow in its response
            ],
            [
                "role" => "user", // the user's input (what gets sent to the AI)
                "content" => $text // the EOI data which is to be summarised by the AI
            ]
        ],

        "temperature" => 0.2, // limits how random the responses are
        "max_tokens" => 120 // maximum number of tokens which can be used for the response
    ]);

    // create an instance of curl (which is used to send and receive data from API's)
    $ch = curl_init();
    
    // configures the API requests settings such as authentication, and connection timeout
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

    // check for errors with Curl
    if ($response === false) {
        $err = curl_error($ch);
        return "AI summary unavailable (cURL error: $err)";
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // get status code for errors

    if ($httpCode !== 200) { // handle API Errors
        return "AI summary unavailable (API error $httpCode): " . $response;
    }

    $result = json_decode($response, true); // converts the JSON Response into PHP

    if (isset($result["choices"][0]["message"]["content"])) {
        return trim($result["choices"][0]["message"]["content"]);
    } // returns the AI response if the function was successful

    return "AI summary unavailable (invalid response)"; // returns an error if the response is invalid
}

/* DELETE EOIs */
$deleteMessage = ""; // stores user feedback after delete action

if (isset($_GET['delete_job'])) {

    // validate input
    if (empty(trim($_GET['delete_job']))) {
        $_SESSION['deleteMessage'] = "<span style='color:red;'>Error: Please enter a job reference to delete.</span>";
        $_SESSION['skipAiRefresh'] = true;
        header("Location: " . strtok($_SERVER['REQUEST_URI'], '?'));
        exit();
    }

    $job = mysqli_real_escape_string($conn, $_GET['delete_job']);

    // check if job exists before deleting
    $checkResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM eoi WHERE ref_number='$job'");
    $checkRow = mysqli_fetch_assoc($checkResult);

    if ($checkRow['total'] == 0) {
        $_SESSION['deleteMessage'] = "<span style='color:red;'>Error: No EOIs found for Job Reference.</span>";
        $_SESSION['skipAiRefresh'] = true;
    } else {
        mysqli_query($conn, "DELETE FROM eoi WHERE ref_number='$job'");
        $_SESSION['deleteMessage'] = "Deleted EOIs for Job Reference.";
        $_SESSION['skipAiRefresh'] = false;
    }

    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?'));
    exit();
}

/* load delete message after redirect */
if (isset($_SESSION['deleteMessage'])) {
    $deleteMessage = $_SESSION['deleteMessage'];
    unset($_SESSION['deleteMessage']);
}

/* UPDATE STATUS */
$updateMessage = "";

if (isset($_GET['update'])) {

    // validate EOI ID input
    if (empty(trim($_GET['eoi_id'] ?? ''))) {
        $_SESSION['updateMessage'] = "<span style='color:red;'>Error: Please enter an EOI number.</span>";
        $_SESSION['skipAiRefresh'] = true;
    } else {

        $id = mysqli_real_escape_string($conn, $_GET['eoi_id']);
        $status = mysqli_real_escape_string($conn, $_GET['status']);

        // verify record exists
        $checkResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM eoi WHERE EOInumber='$id'");
        $checkRow = mysqli_fetch_assoc($checkResult);
        // check if EOI record exists
        if ($checkRow['total'] == 0) {
            $_SESSION['updateMessage'] = "<span style='color:red;'>Error: No EOI found.</span>";
            $_SESSION['skipAiRefresh'] = true;
        } else {
            // update EOI status in database
            mysqli_query($conn, "UPDATE eoi SET status='$status' WHERE EOInumber='$id'");
            $_SESSION['updateMessage'] = "EOI status updated successfully.";
            $_SESSION['skipAiRefresh'] = false; // allow the AI summary to update according to the new database
        }
    }

    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?')); // reload page and remove URL parameters
    exit();
}

/* load update message after redirect */
if (isset($_SESSION['updateMessage'])) {
    $updateMessage = $_SESSION['updateMessage'];
    unset($_SESSION['updateMessage']);
}

/* BASE QUERY */
$sql = "SELECT * FROM eoi WHERE 1=1";

/* SEARCH FILTER */
if (!empty($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
    $sql .= " AND (
        ref_number LIKE '%$search%'
        OR first_name LIKE '%$search%'
        OR last_name LIKE '%$search%'
    )";
}

/* SORT HANDLING */
$allowed_sort = ["EOInumber","ref_number","first_name","last_name","status"];
$sort = "EOInumber";

if (!empty($_GET['sort']) && in_array($_GET['sort'], $allowed_sort)) {
    $sort = $_GET['sort'];
}

$sql .= " ORDER BY $sort";

$result = mysqli_query($conn, $sql);

/* TOTAL EOIs COUNT */
$countResult = mysqli_query($conn, "SELECT COUNT(*) AS total FROM eoi");
$countRow = mysqli_fetch_assoc($countResult);
$totalEOIs = $countRow['total'];

/* LAST LOGIN CHECK */
$historyResult = mysqli_query(
    $conn,
    "SELECT username, login_time
     FROM login_history
     WHERE username='{$_SESSION['username']}'
     ORDER BY login_time DESC
     LIMIT 1 OFFSET 1"
);

$lastLogin = mysqli_fetch_assoc($historyResult);

// The following section of code was assisted by ChatGPT (OpenAI, GPT-5.5, April 2026).
// The code was reviewed, tested, and adjusted by the student before submission.
/* AI DATA PREPARATION */
$aiText = "";

// check if the AI response returned at least one row
if ($result && mysqli_num_rows($result) > 0) {

    mysqli_data_seek($result, 0); // reset result pointer

    // arrays to store the summary counts which are used in the response given by the AI
    $statusCounts = [];
    $townCounts = [];
    $stateCounts = [];
    $skillCounts = [];

    while ($row = mysqli_fetch_assoc($result)) {

        // count statuses
        $statusCounts[$row['status']] = ($statusCounts[$row['status']] ?? 0) + 1;

        // count towns
        $town = trim($row['suburb_or_town']);
        if ($town) $townCounts[$town] = ($townCounts[$town] ?? 0) + 1;

        // count states
        $state = trim($row['state']);
        if ($state) $stateCounts[$state] = ($stateCounts[$state] ?? 0) + 1;

        // split and count skills
        $skills = array_filter(array_map('trim', explode(',', $row['skills'] ?? '')));
        foreach ($skills as $skill) {
            $skillCounts[$skill] = ($skillCounts[$skill] ?? 0) + 1;
        }
    }

    
    mysqli_data_seek($result, 0); // goes back to the first row in the results section


    // convert status counts into a readable string and sort data from highest to lowest
    $statusLine = implode(', ', array_map(fn($s,$c)=>"$c $s", array_keys($statusCounts), $statusCounts));

    // sorts the data so the most common values appear first
    arsort($townCounts); 
    arsort($stateCounts);
    arsort($skillCounts);

    $aiText = "EOI Data Summary:\n"
        . "- Status breakdown: $statusLine\n"
        . "- Top towns: " . implode(', ', array_slice(array_keys($townCounts), 0, 3)) . "\n"
        . "- Top states: " . implode(', ', array_slice(array_keys($stateCounts), 0, 3)) . "\n"
        . "- Top skills: " . implode(', ', array_keys(array_slice($skillCounts, 0, 5, true)));
}

/* AI CACHE CONTROL */
$skipAiRefresh = !empty($_GET['search']) || !empty($_GET['sort']);

// override cache behaviour after delete/update actions
if (isset($_SESSION['skipAiRefresh'])) {
    if ($_SESSION['skipAiRefresh']) $skipAiRefresh = true;
    unset($_SESSION['skipAiRefresh']);
}

// reuse cached AI summary if valid
if ($skipAiRefresh && isset($_SESSION['aiSummary'])) {
    $aiSummary = $_SESSION['aiSummary'];
} else {
    $aiSummary = !empty($aiText) ? getAISummary($aiText) : "No EOI data available.";
    $_SESSION['aiSummary'] = $aiSummary;
}

include "header.inc";
?>

<title>Goobers - Management</title>
<main>

<div class="manage-container">

    <h1>EOI Management System</h1>

    <h2>AI Summary</h2>
    <div class="manage-message">
        🤖 Hey <?php echo htmlspecialchars($_SESSION['username']); ?>!
        <br><br>
        <?php echo htmlspecialchars($aiSummary); ?> <!-- AI-generated summary output -->
    </div>

    <!-- DASHBOARD SECTION: shows key system stats -->
    <h2>Dashboard</h2>
    <div class="dashboard">

        <div class="manage-message dashboard-box">
            📄 Total EOIs
            <strong><?php echo $totalEOIs; ?></strong> <!-- total database entries -->
        </div>

        <div class="manage-message dashboard-box">
            👤 Logged in as:
            <strong><?php echo $_SESSION['username']; ?></strong> <!-- current user -->
        </div>

        <div class="manage-message dashboard-box">
            🕒 Last Login
            <strong>
                <?php echo $lastLogin ? $lastLogin['login_time'] : "No record"; ?>
            </strong> <!-- previous login timestamp -->
        </div>

    </div>

    <!-- SEARCH + SORT SECTION -->
    <h2>Search EOIs</h2>

    <form method="GET">

        <label for="search">Search EOIs</label>
        <input type="text" id="search" name="search"
            placeholder="Enter job reference, first or last name"
            value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">

        <label for="sort">Sort results</label>
        <select id="sort" name="sort">

            <!-- sorting options control SQL ORDER BY -->
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

    <!-- DELETE SECTION -->
    <h2>Delete EOIs</h2>

    <form method="GET">
        <label for="delete_job">Job Reference to delete</label>
        <input type="text" id="delete_job" name="delete_job" placeholder="Enter job reference">
        <input type="submit" value="Delete">
    </form>

    <!-- delete feedback message (success/error) -->
    <?php if ($deleteMessage): ?>
        <p class="manage-message"><?php echo $deleteMessage; ?></p>
    <?php endif; ?>

    <!-- UPDATE SECTION -->
    <h2>Update Status</h2>

    <form method="GET">

        <label for="eoi_id">EOI Number</label>
        <input type="text" id="eoi_id" name="eoi_id" placeholder="Enter EOI Number">

        <label for="status">Status</label>
        <select id="status" name="status">
            <option>New</option>
            <option>Current</option>
            <option>Final</option>
        </select>

        <input type="submit" name="update" value="Update">
    </form>

    <!-- update feedback message -->
    <?php if ($updateMessage): ?>
        <p class="manage-message"><?php echo $updateMessage; ?></p>
    <?php endif; ?>

    <!-- RESULTS SECTION -->
    <h2>Results</h2>

    <!-- if database has results, show table -->
    <?php if (mysqli_num_rows($result) > 0): ?>

    <div class="manage-table-wrap">

        <table class="manage-table">

            <!-- TABLE HEADER -->
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

            <!-- LOOP THROUGH ALL EOIs AND DISPLAY ROWS -->
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
        <p class="manage-message">No EOIs found.</p> <!-- fallback when query returns nothing -->
    <?php endif; ?>

</div>

</main>

<?php include "footer.inc"; ?>