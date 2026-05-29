<?php
require_once("settings.php");

$conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Database connection failed");
}

/* Delete EOI'S by Job Reference */
if (isset($_GET['delete_job']) && !empty($_GET['delete_job'])) {

    $job = mysqli_real_escape_string($conn, $_GET['delete_job']);

    $deleteSQL = "DELETE FROM eoi WHERE ref_number='$job'";
    mysqli_query($conn, $deleteSQL);

    echo "<p class='manage-message'>Deleted EOIs for Job Reference: $job</p>";
}

/* Update status */
if (isset($_GET['update'])) {

    $id = mysqli_real_escape_string($conn, $_GET['eoi_id']);
    $status = mysqli_real_escape_string($conn, $_GET['status']);

    $updateSQL = "UPDATE eoi SET status='$status' WHERE EOInumber='$id'";
    mysqli_query($conn, $updateSQL);

    echo "<p class='manage-message'>EOI status updated.</p>";
}


$result = null;
$showTable = false;

/* only run query when user submits search or list all */
if (isset($_GET['search']) || isset($_GET['sort'])) {

    $showTable = true;

    $sql = "SELECT * FROM eoi WHERE 1=1";

/* Search filter */
    if (!empty($_GET['search'])) {

        $search = mysqli_real_escape_string($conn, $_GET['search']);

        $sql .= " AND (
            ref_number LIKE '%$search%' 
            OR first_name LIKE '%$search%' 
            OR last_name LIKE '%$search%'
        )";
    }

/* Sort */
    $allowed_sort = ["EOInumber", "ref_number", "first_name", "last_name", "status"];

    $sort = "EOInumber";

    if (!empty($_GET['sort']) && in_array($_GET['sort'], $allowed_sort)) {
        $sort = $_GET['sort'];
    }

    $sql .= " ORDER BY $sort";

    $result = mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage EOIs</title>
    <link rel="stylesheet" href="styles/style.css">
</head>
<body>

<?php include "header.inc"; ?>

<main>

<div class="manage-container">

<h1>EOI Management System</h1>

<!-- Search and Sort -->
<h3>Search EOIs</h3>

<form method="GET">

    <input 
        type="text" 
        name="search" 
        placeholder="Search job ref, first or last name"
        value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
        style="min-width: 320px;"
    >

    <select name="sort">

        <option value="EOInumber"
            <?php if (isset($_GET['sort']) && $_GET['sort'] == "EOInumber") echo "selected"; ?>>
            Sort by EOI ID
        </option>

        <option value="ref_number"
            <?php if (isset($_GET['sort']) && $_GET['sort'] == "ref_number") echo "selected"; ?>>
            Sort by Job Reference
        </option>

        <option value="first_name"
            <?php if (isset($_GET['sort']) && $_GET['sort'] == "first_name") echo "selected"; ?>>
            Sort by First Name
        </option>

        <option value="last_name"
            <?php if (isset($_GET['sort']) && $_GET['sort'] == "last_name") echo "selected"; ?>>
            Sort by Last Name
        </option>

        <option value="status"
            <?php if (isset($_GET['sort']) && $_GET['sort'] == "status") echo "selected"; ?>>
            Sort by Status
        </option>

    </select>

    <input type="submit" value="Search / List All">

</form>

<!-- Delete EOIS-->
<h3>Delete EOIs</h3>

<form method="GET">
    <input type="text" name="delete_job" placeholder="Enter job reference">
    <input type="submit" value="Delete">
</form>

<!-- Update status -->
<h3>Update Status</h3>

<form method="GET">

    <input type="text" name="eoi_id" placeholder="Enter EOI Number">

    <select name="status">
        <option value="New">New</option>
        <option value="Current">Current</option>
        <option value="Final">Final</option>
    </select>

    <input type="submit" name="update" value="Update">

</form>

<!-- Results -->
<h3>Results</h3>

<?php
if ($showTable && $result && mysqli_num_rows($result) > 0) {

    echo "<div class='manage-table-wrap'>";
    echo "<table class='manage-table'>";

    echo "<tr>
        <th>EOI ID</th>
        <th>Job Reference</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Date of Birth</th>
        <th>Gender</th>
        <th>Street Address</th>
        <th>Suburb/Town</th>
        <th>State</th>
        <th>Postcode</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Skills</th>
        <th>Other Skills</th>
        <th>Status</th>
    </tr>";

    while ($row = mysqli_fetch_assoc($result)) {

        echo "<tr>";

        echo "<td>{$row['EOInumber']}</td>";
        echo "<td>{$row['ref_number']}</td>";
        echo "<td>{$row['first_name']}</td>";
        echo "<td>{$row['last_name']}</td>";
        echo "<td>{$row['date_of_birth']}</td>";
        echo "<td>{$row['gender']}</td>";
        echo "<td>{$row['street_address']}</td>";
        echo "<td>{$row['suburb_or_town']}</td>";
        echo "<td>{$row['state']}</td>";
        echo "<td>{$row['postcode']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>{$row['phone_number']}</td>";
        echo "<td>{$row['skills']}</td>";
        echo "<td>{$row['other_skills']}</td>";
        echo "<td>{$row['status']}</td>";

        echo "</tr>";
    }

    echo "</table></div>";

} else {
    echo "<p class='manage-message'>Enter search criteria to display EOIs.</p>";
}
?>

</div>

</main>

<?php include "footer.inc"; ?>

</body>
</html>