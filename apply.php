<?php
session_start();
require_once("settings.php");
?>
<?php include "header.inc" ?>

  <main>

    <section class="application-form" aria-labelledby="form-title">
      <h2 id="form-title">Job Application Form</h2>

      <form action="process_eoi.php" method="post" novalidate aria-label="Job Application Form">

        <!-- job reference number -->
        <div class="reference">
          <label for="ref_number">Job Reference Number</label>
          <input type="text" id="ref_number" name="ref_number" placeholder="12345" aria-required="true"><br>
        </div>

        <!-- Personal Info -->
        <div class="personal">
          <label for="first_name">First Name</label>
          <input type="text" id="first_name" name="first_name"
                 placeholder="John" aria-required="true"><br>

          <label for="last_name">Last Name</label>
          <input type="text" id="last_name" name="last_name"
                 placeholder="Smith" aria-required="true"><br>

          <label for="date_of_birth">Date of Birth</label>
          <input type="text" id="date_of_birth" name="date_of_birth"
                 placeholder="dd/mm/yyyy" aria-required="true"><br>

          <fieldset aria-labelledby="gender-legend">
            <legend id="gender-legend"><strong>Gender</strong></legend>
            <input type="radio" name="gender" id="male" value="male" aria-required="true">
            <label for="male">Male</label>

            <input type="radio" name="gender" id="female" value="female" aria-required="true">
            <label for="female">Female</label>
          </fieldset>
        </div>

        <!-- Address -->
        <div class="address">
          <label for="street_address">Street Address</label>
          <input type="text" id="street_address" name="street_address"
                 placeholder="123 Goober Street" aria-required="true"><br>

          <label for="suburb_or_town">Suburb/Town</label>
          <input type="text" id="suburb_or_town" name="suburb_or_town"
                 placeholder="Hawthorn" aria-required="true"><br>

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

          <label for="postcode">Postcode</label>
          <input type="text" id="postcode" name="postcode"
                 placeholder="0000" aria-required="true"><br>
        </div>

        <!-- Contact details -->
        <div class="contact">
          <label for="email">Email</label>
          <input type="text" name="email" id="email" aria-required="true" placeholder="johnsmith@mymail.com"><br>

          <label for="phone_number">Phone Number</label>
          <input type="text" name="phone_number" id="phone_number"
                 aria-required="true" placeholder="0000 000 000"><br>
        </div>

        <!-- Skills info -->
        <div class="skills">
          <div class="skills-grid">

            <fieldset aria-labelledby="skills-legend">
              <legend id="skills-legend"><Strong>Skills</Strong></legend>

              <label for="html">HTML</label>
              <input type="checkbox" name="skills[]" id="html" value="HTML">

              <label for="css">CSS</label>
              <input type="checkbox" name="skills[]" id="css" value="CSS">

              <label for="javascript">JavaScript</label>
              <input type="checkbox" name="skills[]" id="javascript" value="JavaScript">

              <label for="php">PHP</label>
              <input type="checkbox" name="skills[]" id="php" value="PHP">

              <label for="mysql">MySQL</label>
              <input type="checkbox" name="skills[]" id="mysql" value="MySQL"><br>

            </fieldset>
          </div>

        <div class="status">
            <label for="status">Application Status</label>
          <select name="status" id="status">
            <option value="New" selected>New</option>
            <option value="Current">Current</option>
            <option value="Final">Final</option>
          </select>
        </div>

          <div class="other-grid">
            <label for="other_skills">Other Skills:</label><br>
            <textarea name="other_skills" id="other_skills" rows="5" cols="65"
                      placeholder="Write a description of your skills here..."></textarea><br><br>
          </div>
        </div>

        <button class="button" type="submit" aria-label="Submit job application form">Submit</button>

      </form>
    </section>

  </main>

<?php include "footer.inc" ?>
