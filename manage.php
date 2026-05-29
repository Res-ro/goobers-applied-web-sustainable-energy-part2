<?php
require_once("settings.php");

$conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);

if (!$conn) {
    die("Database connection failed");
}

$results = [];
$search_query = "";


if (isset($_GET['search']) && !empty($_GET['search'])) {

    $search_query = trim($_GET['search']);

    $query = "SELECT * FROM eoi 
              WHERE ref_number LIKE '%$search_query%'
              OR first_name LIKE '%$search_query%'
              OR last_name LIKE '%$search_query%'";

    $result = mysqli_query($conn, $query);

    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $results[] = $row;
        }
    }
}


if (isset($_GET['delete_job']) && !empty($_GET['delete_job'])) {

    $job = $_GET['delete_job'];

    $deleteSQL = "DELETE FROM eoi WHERE ref_number='$job'";

    mysqli_query($conn, $deleteSQL);

    echo "<p class='manage-message'>Deleted EOIs with Job Reference: $job</p>";
}


if (isset($_GET['update'])) {

    $id = $_GET['eoi_id'];
    $status = $_GET['status'];

    $updateSQL = "UPDATE eoi 
                  SET status='$status' 
                  WHERE EOInumber='$id'";

    mysqli_query($conn, $updateSQL);

    echo "<p class='manage-message'>Status updated.</p>";
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

    <h1>EOI Management</h1>


    <h3>Search EOIs</h3>

    <form method="GET">

        <input 
            type="text" 
            name="search" 
            placeholder="Search by job reference or name"
        >

        <input type="submit" value="Search">

    </form>


    <h3>Delete EOIs by Job Reference</h3>

    <form method="GET">

        <input 
            type="text" 
            name="delete_job" 
            placeholder="Enter job reference"
        >

        <input type="submit" value="Delete">

    </form>

    <h3>Update EOI Status</h3>

    <form method="GET">

        <input 
            type="text" 
            name="eoi_id" 
            placeholder="EOI Number"
        >

        <select name="status">
            <option value="New">New</option>
            <option value="Current">Current</option>
            <option value="Final">Final</option>
        </select>

        <input type="submit" name="update" value="Update">

    </form>


    <?php
    if (!empty($search_query)) {

        echo "<h3>Results for \"" . htmlspecialchars($search_query) . "\"</h3>";

        if (!empty($results)) {

            echo "<table class='manage-table'>";

            echo "<tr>
                    <th>EOI Number</th>
                    <th>Job Reference</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Status</th>
                  </tr>";

            foreach ($results as $row) {

                echo "<tr>";

                echo "<td>" . $row['EOInumber'] . "</td>";
                echo "<td>" . $row['ref_number'] . "</td>";
                echo "<td>" . $row['first_name'] . "</td>";
                echo "<td>" . $row['last_name'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";

                echo "</tr>";
            }

            echo "</table>";

        } else {

            echo "<p class='manage-message'>No EOIs found.</p>";
        }
    }
    ?>

</div>

</main>

<?php include "footer.inc"; ?>
</body>
</html>