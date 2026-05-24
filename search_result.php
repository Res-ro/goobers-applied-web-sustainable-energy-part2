<?php
session_start();
require_once("settings.php");
?>
<?php include "header.inc" ?>

<?php
$conn        = mysqli_connect($host, $dbuser, $dbpass, $dbname);
$jobs        = [];
$search_term = "";
$error       = "";

if (!$conn) {
    $error = "Unable to connect to the database. Please try again later.";
} elseif (isset($_GET['search']) && trim($_GET['search']) !== "") {
    $search_term = trim($_GET['search']);
    $search_safe = mysqli_real_escape_string($conn, $search_term);

    $result = mysqli_query($conn, "SELECT * FROM jobs
                                   WHERE title LIKE '%$search_safe%'
                                      OR short_description LIKE '%$search_safe%'
                                   ORDER BY id ASC");
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $jobs[] = $row;
        }
    }
    mysqli_close($conn);
}
?>

<main>

    <!-- persistent search bar, pre-filled with current search term -->
    <form method="GET" action="search_result.php" id="jobs-search-form" aria-label="Job search">
        <label for="jobs-search-input" class="visually-hidden">Search jobs</label>
        <input type="text" id="jobs-search-input" name="search"
               placeholder="Search jobs... e.g. Developer, Media"
               maxlength="100"
               aria-label="Search jobs input"
               value="<?php echo htmlspecialchars($search_term); ?>">
        <button type="submit">Search</button>
    </form>
    <section>
        <h2 style="color: inherit; font-size: 2em; font-weight: bold;">Job Search Results</h2>

        <?php if ($error): ?>
            <p><?php echo htmlspecialchars($error); ?></p>

        <?php elseif ($search_term === ""): ?>
            <p>Please enter a search term to find jobs.</p>

        <?php elseif (empty($jobs)): ?>
            <p>No jobs found matching <strong><?php echo htmlspecialchars($search_term); ?></strong>.</p>
            <a href="jobs.php">View all available jobs</a>

        <?php else: ?>
            <p><?php echo count($jobs); ?> result(s) for <em><?php echo htmlspecialchars($search_term); ?></em>:</p>

            <div class="job-cards-container">
                <?php foreach ($jobs as $index => $job):
                    $toggle_id        = "result-job" . ($index + 1) . "-toggle";
                    $title            = htmlspecialchars($job['title']);
                    $reference        = htmlspecialchars($job['reference']);
                    $location         = htmlspecialchars($job['location']);
                    $short_desc       = htmlspecialchars($job['short_description']);
                    $salary           = number_format((int) $job['salary']);
                    $reporting_line   = htmlspecialchars($job['reporting_line']);
                    $responsibilities = explode('|', $job['key_responsibilities']);
                    $essential        = explode('|', $job['essential_requirements']);
                    $preferable       = explode('|', $job['preferable_requirements']);

                    include "job_card.inc";
                endforeach; ?>
            </div>

        <?php endif; ?>
    </section>
</main>

<?php include "footer.inc" ?>