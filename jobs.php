<?php
session_start();
require_once("settings.php");
?>
<?php include "header.inc" ?>

    <main>

        <section>
            <h2 style="color: inherit; font-size: 2em; font-weight: bold;">Available Jobs</h2>
            <h2>Renewable Energy Career Opportunities</h2>

            <div class="top-layout">
                <ol>
                    <li>Browse available job roles</li>
                    <li>Open a job to view full details</li>
                    <li>Click apply and submit your application</li>
                </ol>

                <aside>
                    <h3>Application Tip</h3>
                    <p>Ensure your CV highlights relevant technical skills and teamwork experience.</p>
                </aside>
            </div>

        </section>

        <section>
            <div class="job-cards-container">
            <?php
            $conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);

            if (!$conn) {
                echo "<p>Unable to connect to the database. Please try again later.</p>";
            } else {
                $result = mysqli_query($conn, "SELECT * FROM jobs ORDER BY id ASC");

                if (!$result || mysqli_num_rows($result) === 0) {
                    echo "<p>No job listings are available at this time. Please check back soon.</p>";
                } else {
                    $job_index = 1;
                    while ($job = mysqli_fetch_assoc($result)) {
                        $toggle_id        = "job" . $job_index . "-toggle";
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
                        $job_index++;
                    }
                }
                mysqli_close($conn);
            }
            ?>
            </div>
        </section>

    </main>

<?php include "footer.inc" ?>