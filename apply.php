<?php

// Start the session to enable session variables across pages
session_start();

// reads value from jobs page apply button GET request
$job_ref = isset($_GET['job_ref']) ? $_GET['job_ref'] : '';

// Load database configuration (host, user, password, database name)
require_once("settings.php");
?>
<?php include "header.inc" ?>

<meta name="description" content="Job Application Form for Goobers Web Developer Roles">
<meta name="keywords" content="Apply, Job, HTML, CSS, PHP, Employment">
<meta name="author" content="Goobers Web Team">

<!-- Page title displayed in browser tab -->
<title>Goobers - Apply</title>

  <main>

    <section class="application-form" aria-labelledby="form-title">
      <h2 id="form-title">Job Application Form</h2>


      <!-- This pushes the form data to the process_eoi.php file for processing and validation -->
      <!-- The novalidate attribute disables the browser's default validation, allowing for custom validation in process_eoi.php -->
      <!-- aria-label gives screen readers a meaningful name for the form -->
      <form action="process_eoi.php" method="post" novalidate aria-label="Job Application Form">

        <!-- job reference number -->
        <!-- Applicant enters the reference number of the job they are applying for -->
        <div class="reference">
          <label for="ref_number">Job Reference Number</label>
          <input type="text" id="ref_number" name="ref_number" placeholder="12345" value="<?php echo htmlspecialchars($job_ref); ?>" aria-required="true"><br>
          <!-- aria-required tells screen readers this field is required -->
        </div>

        <!-- Personal Info -->

        <div class="personal">

          <!-- First name: letters and spaces only, max 25 characters -->
          <!-- 'First Name' is the visible label; 'first_name' is the POST key sent to process_eoi.php -->
          <label for="first_name">First Name</label>
          <input type="text" id="first_name" name="first_name"
                placeholder="John" aria-required="true"
                pattern="[A-Za-z ]{1,25}" title="First name must only contain letters and spaces (max 25 characters)"><br>
                <!-- pattern provides client-side regex validation as a validation check -->


          <!-- Last name: same rules as first name -->
          <label for="last_name">Last Name</label>
          <input type="text" id="last_name" name="last_name"
                placeholder="Smith" aria-required="true"
                pattern="[A-Za-z ]{1,25}" title="Last name must only contain letters and spaces (max 25 characters)"><br>

          <!-- Date of birth: expects dd/mm/yyyy format, validated server-side -->
          <label for="date_of_birth">Date of Birth</label>
          <input type="text" id="date_of_birth" name="date_of_birth"
                 placeholder="dd/mm/yyyy" aria-required="true"><br>

          <!-- Gender fieldset: Radio buttons grouped with fieldset and legend -->
          <fieldset aria-labelledby="gender-legend">
            <legend id="gender-legend" style="color: #31383f;">Gender</legend>

            <!-- Radio buttons share the same name="gender" so only one can be selected -->
            <input type="radio" name="gender" id="male" value="male" aria-required="true">
            <label for="male">Male</label>

            <input type="radio" name="gender" id="female" value="female" aria-required="true">
            <label for="female">Female</label>
          </fieldset>
        </div>

        <!-- Address -->
        <div class="address">

          <!-- Street address: free text, validated server-side -->
          <label for="street_address">Street Address</label>
          <input type="text" id="street_address" name="street_address"
                 placeholder="123 Goober Street" aria-required="true"><br>

          <!-- Suburb or town name -->
          <label for="suburb_or_town">Suburb/Town</label>
          <input type="text" id="suburb_or_town" name="suburb_or_town"
                 placeholder="Hawthorn" aria-required="true"><br>

          <!-- State: dropdown restricted to Australian states and territories -->
          <!-- Empty first option forces user to make a deliberate selection -->
          <label for="state">State</label>
          <select name="state" id="state" aria-required="true">
            <option value="">-- Select a State --</option>
            <option value="VIC">VIC</option>
            <option value="NSW">NSW</option>
            <option value="QLD">QLD</option>
            <option value="NT">NT</option>
            <option value="WA">WA</option>
            <option value="SA">SA</option>
            <option value="TAS">TAS</option>
            <option value="ACT">ACT</option>
          </select><br>

          <!-- Postcode: 4 digit number, validated server-side -->
          <label for="postcode">Postcode</label>
          <input type="text" id="postcode" name="postcode"
                 placeholder="0000" aria-required="true"><br>
        </div>

        <!-- Contact details -->
        <div class="contact">

          <!-- type="email" provides mobile-friendly keyboard with @ and . keys -->
          <!-- browser validation is suppressed by novalidate on the form -->
          <label for="email">Email</label>
          <input type="email" name="email" id="email" aria-required="true" placeholder="johnsmith@mymail.com"><br>

          <!-- Phone: free text input, format validated server-side -->
          <label for="phone_number">Phone Number</label>
          <input type="text" name="phone_number" id="phone_number"
                 aria-required="true" placeholder="0000 000 000"><br>
        </div>

        <!-- Skills info -->
        <div class="skills">
          <div class="skills-grid">

            <fieldset aria-labelledby="skills-legend">
              <legend id="skills-legend"><strong>Skills</strong></legend>

              <!-- Each checkbox uses name="skills[]" so PHP receives them as an array -->
              <!-- Each id matches its label's for attribute for accessibility -->
              <label for="html">HTML</label>
              <input type="checkbox" name="skills[]" id="html" value="HTML">

              <label for="css">CSS</label>
              <input type="checkbox" name="skills[]" id="css" value="CSS">

              <label for="javascript">JavaScript</label>
              <input type="checkbox" name="skills[]" id="javascript" value="JavaScript">

              <label for="php">PHP</label>
              <input type="checkbox" name="skills[]" id="php" value="PHP">

              <label for="mysql">MySQL</label>
              <input type="checkbox" name="skills[]" id="mysql" value="MySQL">

            </fieldset>
          </div>


          <!-- Free text area for skills not covered by the checkboxes -->
          <!-- resize: vertical prevents horizontal resizing breaking the layout -->
          <!-- max-height: 200px caps how tall the user can drag it -->
          <div class="other-grid">
            <label for="other_skills">Other Skills:</label><br>
            <textarea name="other_skills" id="other_skills" rows="5" cols="65" placeholder="Write a description of your skills here..."
                      style="resize: vertical; max-height: 200px;"></textarea><br><br>
          </div>
        </div>

        <!-- Submit button: triggers form submission to process_eoi.php -->
        <!-- aria-label gives screen readers a more descriptive name than just "Submit" -->
        <button class="custom-button" type="submit" aria-label="Submit job application form">Submit</button>

      </form>
    </section>

  </main>

<?php include "footer.inc" ?>
