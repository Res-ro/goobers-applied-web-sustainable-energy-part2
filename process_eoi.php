<?php



// checks database settings
require_once("settings.php");
 // establishes a connection using the configuration in settings.php
 // if the user tries to directly access the page, they will be sent back to the Apply page


// Sanitisation function - cleans all incoming user input before use
// Function created to handle 'Trim, strip slashes and escape HTML'
function sanitise_input($data) {
    $data = trim($data); // Removes extra spaces
    $data = stripslashes($data); // Removes backlashes from input data
    $data = htmlspecialchars($data); // converts special HTML characters to harmless text to prevent browser from treating it like code. Prevents XSS attacks
    return $data;
}

// Block direct access to this page - if the request is not a POST
// or the ref_number field is missing, redirect back to the apply form
if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST["ref_number"])) { 
    header("Location: apply.php");
    exit();
}

  $conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);

    // If connection fails, stop execution immediately
    if (!$conn) {
        die("Connection failed");
    }

 // copies data from apply.html and saves it to variables
 // Runs all incoming POST data through the sanitisation function
 // ?? "" provides a fallback empty string for optional fields (gender, status)
    $ref_number      = sanitise_input($_POST["ref_number"]);
    $first_name      = sanitise_input($_POST["first_name"]);
    $last_name       = sanitise_input($_POST["last_name"]);
    $date_of_birth   = sanitise_input($_POST["date_of_birth"]);
    $gender          = sanitise_input($_POST["gender"] ?? "");
    $street_address  = sanitise_input($_POST["street_address"]);
    $suburb_or_town  = sanitise_input($_POST["suburb_or_town"]);
    $state           = sanitise_input($_POST["state"]);
    $postcode        = sanitise_input($_POST["postcode"]);
    $email           = sanitise_input($_POST["email"]);
    $phone_number    = sanitise_input($_POST["phone_number"]);
    $other_skills    = sanitise_input($_POST["other_skills"]);
    $status          = sanitise_input($_POST["status"] ?? "");


    // Server-side validation rules, checks if any of the required fields are empty and adds an error message to the $errors array if they are
    $errors = [];

    // Required field checks - adds an error message for each empty field
    if (empty($ref_number))     $errors[] = "Job reference number is required.";
    if (empty($first_name))     $errors[] = "First name is required.";
    if (empty($last_name))      $errors[] = "Last name is required.";
    if (empty($date_of_birth))  $errors[] = "Date of birth is required.";
    if (empty($gender))         $errors[] = "Gender selection is required.";
    if (empty($street_address)) $errors[] = "Street address is required.";
    if (empty($suburb_or_town)) $errors[] = "Suburb or town is required.";
    if (empty($state))          $errors[] = "State selection is required.";
    if (empty($postcode))       $errors[] = "Postcode is required.";
    if (empty($email))          $errors[] = "Email address is required.";
    if (empty($phone_number))   $errors[] = "Phone number is required.";


    // Format Checks / Validation
    // Job Reference Number check (Exactly 5 alphanumeric characters)
    // !empty() check prevents duplicate errors when field is already flagged as empty above
    if (!empty($ref_number) && !preg_match("/^[a-zA-Z0-9]{5}$/", $ref_number)) { 
        $errors[] = "Job reference number must be exactly 5 alphanumeric characters.";
    }

    // First Name: letters and spaces only, max 20 characters
    if (!empty($first_name)) {
        if (!preg_match("/^[a-zA-Z ]+$/", $first_name)) { 
            $errors[] = "First name must contain only alphabetical characters.";
        } else if (strlen($first_name) > 20) { 
            $errors[] = "First name cannot exceed 20 characters.";
        }
    }

    // Last Name: letters and spaces only, max 20 characters
    if (!empty($last_name)) {
        if (!preg_match("/^[a-zA-Z ]+$/", $last_name)) { 
            $errors[] = "Last name must contain only alphabetical characters.";
        } else if (strlen($last_name) > 20) {
            $errors[] = "Last name cannot exceed 20 characters.";
        }
    }

    // Email: uses PHP's built-in FILTER_VALIDATE_EMAIL for format checking
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Suburb/Town check (max 40 characters)
    if (!empty($suburb_or_town) && strlen($suburb_or_town) > 40) {
        $errors[] = "Suburb/Town cannot exceed 40 characters.";
    }

    // Postcode check (Exactly 4 digits & Numeric)
    if (!empty($postcode) && !preg_match("/^[0-9]{4}$/", $postcode)) { 
        $errors[] = "Postcode must be exactly 4 digits.";
    }

    // Phone number check (8 to 12 digits, spaces being allowed)
    if (!empty($phone_number) && !preg_match("/^[0-9 ]{8,12}$/", $phone_number)) {
        $errors[] = "Phone number must contain only 8 to 12 digits (spaces allowed).";
    }

    // Date of Birth: must match dd/mm/yyyy format
    // preg_match captures day, month, year into $matches array for further checks
    if (!empty($date_of_birth)) {
        if (!preg_match("/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/", $date_of_birth, $matches)) {
            $errors[] = "Date of birth must be in the format dd/mm/yyyy.";
        } else {
            $day   = (int)$matches[1]; //Assigns $day the first captured group from the regex (dd)
            $month = (int)$matches[2]; //Assigns $month the second captured group from the regex (mm)
            $year  = (int)$matches[3]; //Assigns $year the third captured group from the regex (yyyy)

            // checkdate() verifies the date is a real calendar date (e.g. rejects 50/11/2002)
            if (!checkdate($month, $day, $year)) { 
                $errors[] = "The date of birth provided is not a valid calendar date.";
            } 
            // Year range check - must be between 1900 and 2026
            else if ($year > 2026 || $year < 1900) { 
                $errors[] = "Please enter a realistic year of birth.";
            }
        }
    }


    // If validation errors exist, terminate execution, display errors and stop execution
    if (!empty($errors)) {
        include("header.inc");
        ?>
        <title>Application Error</title>
        <link rel="stylesheet" href="styles/process_eoi.css">

        <div class="eoi-success-container">
            <div class="result-card error-card">
                <h1>Application Error</h1>
                <p style="color: #666; font-weight: bold; margin-bottom: 10px;">Please resolve the following issues:</p>
                <!-- Loop through the $errors array and display each error as a list item -->
                <ul style="text-align: left; max-width: 420px; margin: 20px auto; line-height: 1.6; color: #333333;">
                    <?php foreach ($errors as $error) { 
                        echo "<li>" . $error . "</li>";
                    } ?>
                </ul>
                <a href="apply.php" class="back-button">Go Back to Form</a>
            </div>
        </div>
        <?php 
        include("footer.inc");
        mysqli_close($conn); // Close DB connection before exiting
        exit();              // Stop further execution
    }

    // Builds a comma-separated string from the selected checkboxes
    // ?? [] provides an empty array fallback if no skills were checked
    $skills = "";
    $skills_posted = $_POST["skills"] ?? [];

    // Check each possible index individually since checkboxes only POST if checked
    if (isset($_POST["skills"][0]))  $skills .= $skills_posted[0];
    if (isset($_POST["skills"][1]))  $skills .= ", " . $skills_posted[1];
    if (isset($_POST["skills"][2]))  $skills .= ", " . $skills_posted[2];
    if (isset($_POST["skills"][3]))  $skills .= ", " . $skills_posted[3];
    if (isset($_POST["skills"][4]))  $skills .= ", " . $skills_posted[4];


    // -- Database Table Creation -- 
    // Creates the eoi table if it doesn't already exist
    // status defaults to 'New' for all fresh applications
    $table_sql = "CREATE TABLE IF NOT EXISTS eoi (  
        EOInumber INT AUTO_INCREMENT PRIMARY KEY,
        ref_number VARCHAR(5),
        first_name VARCHAR(20),
        last_name VARCHAR(20),
        date_of_birth VARCHAR(10),
        gender VARCHAR(10),
        street_address VARCHAR(40),
        suburb_or_town VARCHAR(40),
        state VARCHAR(3),
        postcode VARCHAR(4),
        email VARCHAR(100),
        phone_number VARCHAR(12),
        skills TEXT,
        other_skills TEXT,
        status VARCHAR(10) DEFAULT 'New' 
    )";

    mysqli_query($conn, $table_sql);

    // Inserts the submitted form values into the "eoi" table
    // Uses a prepared statement with ? placeholders to prevent SQL injection
    // All 14 values are bound as strings ("ssssssssssssss")

    $stmt = $conn->prepare("
        INSERT INTO eoi ( ref_number, first_name, last_name, date_of_birth, gender, street_address, suburb_or_town, state, postcode,email, phone_number, skills, other_skills, status
    )
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");

    // Bind all sanitised variables to the prepared statement placeholders
    // "ssssssssssssss" = 14 string parameters, one per ? placeholder
    $stmt->bind_param("ssssssssssssss",$ref_number, $first_name, $last_name, $date_of_birth,$gender, $street_address, $suburb_or_town,$state, $postcode, $email, $phone_number, $skills, $other_skills, $status);



if ($stmt->execute()) {
        // Retrieve the auto-generated EOI number from the inserted row
        $eoi_number = $conn->insert_id;
        
        include("header.inc"); 
    ?>
        <title>Application Completed</title>

        <link rel="stylesheet" href="styles/process_eoi.css">

        <!-- Success card shown to user on successful submission -->
        <div class="eoi-success-container">
            <div class="result-card">
                <h1>Application Complete</h1>
                
                <!-- Display the EOI number so the applicant can reference it later -->
                <p class="eoi-number">
                    EOI Number: <span class="eoi-value"><?php echo $eoi_number; ?></span>
                </p>

                <a href="apply.php" class="back-button">Return</a>
            </div>
        </div>
    <?php        
        include("footer.inc");
    }
    else {
        // If execute() failed, display the database error message
        include("header.inc");
        ?>

        <title>Application Error</title>
        <link rel="stylesheet" href="styles/process_eoi.css">

        <!-- Error card shown if the database insert failed -->
        <div class="eoi-success-container">
            <div class="result-card error-card">
                <h1>Database Error</h1>
                
                <!-- htmlspecialchars prevents any error message content from being rendered as HTML -->
                <p class="error-message">
                    Error Log: <?php echo htmlspecialchars($stmt->error); ?>
                </p>

                <a href="apply.php" class="back-button">Try Again</a>
            </div>
        </div>
        <?php
        include("footer.inc");
    }

// Close the database connection
mysqli_close($conn);

?>