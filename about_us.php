<?php
session_start();
require_once("settings.php");
?>
<?php include "header.inc" ?>

    <main>

        <!-- Group name and class time -->
        <section id="groupInfo" class="contentBox" aria-label="Group Information">
            <h2>Group Information</h2>
            <ul>
                <li>
                    Group Details
                    <ul>
                        <li>Group Name: <strong>Goobers</strong></li>
                        <li>Class Day and Time: <strong>Wednesday 2:30</strong></li>
                    </ul>
                </li>
            </ul>
        </section>

        <!-- Member contributions loaded from the database -->
        <section id="contributions" class="contentBox" aria-label="Team Contributions">
            <h2>Introducing - the team.</h2>
            <?php
            $conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);

            if (!$conn) {
                echo "<p>Unable to load team contributions at this time.</p>";
            } else {
                $result = mysqli_query($conn, "SELECT * FROM about ORDER BY id ASC");

                if (!$result || mysqli_num_rows($result) === 0) {
                    echo "<p>No member information available.</p>";
                } else {
                    echo "<dl>";
                    while ($member = mysqli_fetch_assoc($result)) {
                        $name  = htmlspecialchars($member['name']);
                        $sid   = htmlspecialchars($member['student_id']);
                        $quote = htmlspecialchars($member['quote']);
                        $part1 = htmlspecialchars($member['part1_contribution']);
                        $part2 = htmlspecialchars($member['part2_contribution']);
                        ?>
                        <dt><?php echo $name; ?></dt>
                        <dd class="student-id">Student ID: <?php echo $sid; ?></dd>
                        <dd><strong>Project 1:</strong> <?php echo $part1; ?></dd>
                        <dd><strong>Project 2:</strong> <?php echo $part2; ?></dd>
                        <dd><?php echo $quote; ?></dd>
                        <?php
                    }
                    echo "</dl>";
                }
                mysqli_close($conn);
            }
            ?>
        </section>

        <!-- Group photo — static, not from DB -->
        <section id="groupPhoto" class="contentBox" aria-label="Group Photo">
            <h2>Group Photo</h2>
            <figure>
                <!-- Image generated using GPT Image 1.5 (OpenAI, October 2025). Reviewed and edited before use. -->
                <img src="images/working.webp" alt="Group photo of the Goobers team">
                <figcaption style="color:#8b6b13">
                    <strong>Our group working together on the project.</strong>
                </figcaption>
            </figure>
        </section>

        <!-- Fun facts — static, not from DB -->
        <section id="funFacts" class="contentBox" aria-label="Fun Facts">
            <h2>Fun Facts</h2>
            <table>
                <caption>Group Fun Facts</caption>
                <tr>
                    <th>Name</th>
                    <th>Hobby</th>
                    <th>Favourite Coding Language</th>
                </tr>
                <tr>
                    <td>Aiden</td>
                    <td>Playing games</td>
                    <td>Python</td>
                </tr>
                <tr>
                    <td>Tyler</td>
                    <td>Playing Siege</td>
                    <td>Javascript</td>
                </tr>
                <tr>
                    <td>Ned</td>
                    <td>Working on websites</td>
                    <td>Assembly</td>
                </tr>
                <tr>
                    <td>Huw</td>
                    <td>Working on the CSS of websites</td>
                    <td>CSS</td>
                </tr>
            </table>
        </section>

    </main>

<?php include "footer.inc" ?>