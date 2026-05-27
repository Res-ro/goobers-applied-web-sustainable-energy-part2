<?php
session_start();
require_once("settings.php");
?>
<?php include "header.inc" ?>

    <main>
       <!-- Card 1 - Powering a cleaner Tomorrow -->
      <section id="company-value" class="contentBox" style="text-align: center;" aria-labelledby="powering-heading">
        <h2 id="powering-heading">Powering a Cleaner Tomorrow</h2>
        <p>
          Goobers is committed to delivering innovative renewable energy solutions.
        </p>
        <a href="jobs.php" id="companyvalue-button" aria-label="View available jobs and get involved">
          Get Involved
        </a>
      </section>

      <!-- Card 2 - Who We Are-->
      <section id="about-snapshot" class="contentBox" style="text-align: center;" aria-labelledby="about-heading">
        <h2 id="about-heading">Who We Are</h2>
        <p>
          We are a sustainable energy solutions company focused on promoting renewable technologies.
        </p>
        <a href="about_us.php" aria-label="Learn more about Goobers company">
          Learn More About Us
        </a>
      </section>

     <!-- Card 3 - Highlights-->
      <section id="highlights" class="contentBox" style="text-align: center;" aria-labelledby="highlights-heading">
        <h2 id="highlights-heading">What We Do</h2>
        <!-- Reference to image-->
	<img src="images/dam.png" alt="Dam used to source renewable energy">
        <div class="highlight-box">
          <h3>Renewable Technology</h3>
          <p>
            We design and implement energy systems tailored to community and industry needs.
          </p>
        </div>

  <!-- Addition to Card 3 - Our Practices-->
        <div class="highlight-box">
          <h3>Our Practices</h3>
          <p>
            Since our founding, we have helped power over 10,000 homes with clean energy, reducing carbon emissions by an estimated 25,000 tonnes annually.
          </p>
        </div>
      </section>

   <!-- Card 4 - Current Renewable Projects-->
      <section id="projects" class="contentBox" aria-labelledby="projects-heading">
        <h2 id="projects-heading" style="text-align: center;">Current Renewable Projects</h2>
    <!-- Table structure-->
        <table aria-describedby="projects-heading">

          <caption>
            Active Projects by Location and Type
          </caption>
    <!-- Cell merging -->
          <tr>
            <th rowspan="2">Project Name</th>
            <th colspan="2">Location</th>
            <th rowspan="2">Energy Type</th>
          </tr>

          <tr>
            <th>City</th>
            <th>Country</th>
          </tr>

          <tr>
            <td>Project 1</td>
            <td>Melbourne</td>
            <td>Australia</td>
            <td>Solar</td>
          </tr>

          <tr>
            <td>Project 2</td>
            <td>Sydney</td>
            <td>Australia</td>
            <td>Wind</td>
          </tr>

          <tr>
            <td>Project 3</td>
            <td>Brisbane</td>
            <td>Australia</td>
            <td>Hydroelectric</td>
          </tr>

        </table>

      </section>
 <!-- Card 5 - Acknowledgment of Country -->
      <section id="acknowledgment" class="contentBox" style="text-align: center;" aria-labelledby="acknowledgment-heading">

        <h2 id="acknowledgment-heading">Acknowledgment of Country</h2>

        <p>
          We respectfully acknowledge the Wurundjeri People of the Kulin Nation, who are the Traditional Owners of the land on which this website was made.
        </p>

      </section>
 <!-- Card 6 - Join Us -->
      <section id="join-section" class="contentBox" style="text-align: center;" aria-labelledby="join-heading">

        <h2 id="join-heading">Join the Goobers Team</h2>

        <p>
          Join our community now!
        </p>

        <a href="apply.php" aria-label="Apply to join the Goobers team">
          Apply Now
        </a>

      </section>

    </main>

<?php include "footer.inc" ?>
