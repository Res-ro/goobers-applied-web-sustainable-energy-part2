<?php
session_start();
require_once("settings.php");
?>
<?php include "header.inc" ?>


<?php
# debugging values
# $dbuser = "webuser";
# $dbpass = "password123";
$conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_query = trim($_GET['search']);

	  # Warning, unsanitised input. UNSAFE!
	$query = "SELECT * FROM jobs WHERE title LIKE '%$search_query%'";
	$result = mysqli_query($conn, $query);

	$jobs = [];
	while ($row = mysqli_fetch_assoc($result)) {
		$jobs[] = $row;
	}
}

?>

<main>
<h3>Search results for <?php echo htmlspecialchars($search_query); ?>:</h3><br>

<?php
if (!empty($jobs)) {
    echo "<ul>";
    foreach ($jobs as $job) {
        echo "<li>" . htmlspecialchars($job['title']) . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No results found.</p>";
}
?>

</main>
<?php include "footer.inc" ?>
