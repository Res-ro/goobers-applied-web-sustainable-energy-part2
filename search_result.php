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

<title>Search result</title>
<main>

    <!--persistent search bar-->
    <form method="GET" action="search_result.php" id="jobs-search-form"
          aria-label="Job search"
          style="display: flex; flex-direction: row; align-items: center; gap: 0; padding: 0; background: none; border: none; border-radius: 0; box-shadow: none; width: fit-content; margin: 1.5em auto;">
        <label for="jobs-search-input" class="visually-hidden"></label>
        <input type="text" id="jobs-search-input" name="search"
               placeholder="Search jobs... e.g. Developer, Media"
               maxlength="100"
               aria-label="Search jobs input"
               value="<?php echo htmlspecialchars($search_term); ?>"
               style="padding: 8px 14px; border: none; border-radius: 999px 0 0 999px; background-color: rgba(255,255,255,0.8); font-size: 1em; outline: none; width: 250px; box-shadow: 0 10px 35px rgba(0,0,0,0.4);">
        <button type="submit"
                style="padding: 8px 18px; border: none; border-radius: 0 999px 999px 0; background-color: #8b6b13; color: white; font-size: 1em; font-weight: bold; cursor: pointer; box-shadow: 0 10px 35px rgba(0,0,0,0.4);">
            Search
        </button>
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
                    $toggle_id = "result-job" . ($index + 1) . "-toggle";
                    $title = htmlspecialchars($job['title']);
                    $reference = htmlspecialchars($job['reference']);
                    $location = htmlspecialchars($job['location']);
                    $short_desc = htmlspecialchars($job['short_description']);
                    $salary = number_format((int) $job['salary']);
                    $reporting_line = htmlspecialchars($job['reporting_line']);
                    $responsibilities = explode('|', $job['key_responsibilities']);
                    $essential = explode('|', $job['essential_requirements']);
                    $preferable = explode('|', $job['preferable_requirements']);

                    include "job_card.inc";
                endforeach; ?>
            </div>

        <?php endif; ?>
    </section>

</main>

<?php include "footer.inc" ?>
