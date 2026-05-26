<?php

// checks database settings
require_once("settings.php");
 // establishes a connection using the configuration in settings.php
 // if the user tries to directly access the page, they will be sent back to the Apply page

 // Function created to handle 'Trim, strip slashes and escape HTML'
function sanitise_input($data) {
    $data = trim($data); // Removes extra spaces
    $data = stripslashes($data); // Removes backlashes from input data
    $data = htmlspecialchars($data); // converts special HTML characters to harmless text to prevent browser from treating it like code. Prevents XSS attacks
    return $data;
}

// POST variables are set so blank POST requests are blocked
if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_POST["ref_number"])) { 
    header("Location: apply.php");
    exit();
}

// if ($_SERVER["REQUEST_METHOD"] != "POST") {
//     header("Location: apply.php");
//     exit();
// }
  $conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);

    if (!$conn) {
        die("Connection failed");
    }
 // copies data from apply.html and saves it to variables
 // Runs all incoming POST data through the sanitisation function
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
    $status          = sanitise_input($_POST["status"]);


    // Server-side validation rules, checks if any of the required fields are empty and adds an error message to the $errors array if they are
    // Errors is an empty array that stores validation error messages
    $errors = [];

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
    if (!empty($ref_number) && !preg_match("/^[a-zA-Z0-9]{5}$/", $ref_number)) { // preg_match is a php function that checks if a string matches required pattern
        $errors[] = "Job reference number must be exactly 5 alphanumeric characters.";
    }

    // First Name check
    if (!empty($first_name)) {
        if (!preg_match("/^[a-zA-Z ]+$/", $first_name)) { // preg_match is a php function that checks if a string matches required pattern
            $errors[] = "First name must contain only alphabetical characters.";
        } else if (strlen($first_name) > 20) {
            $errors[] = "First name cannot exceed 20 characters.";
        }
    }

    // Last Name check
    if (!empty($last_name)) {
        if (!preg_match("/^[a-zA-Z ]+$/", $last_name)) { // preg_match is a php function that checks if a string matches required pattern
            $errors[] = "Last name must contain only alphabetical characters.";
        } else if (strlen($last_name) > 20) {
            $errors[] = "Last name cannot exceed 20 characters.";
        }
    }

    // Email format check
    // FILTER_VALIDATE_EMAIL is a built in php function that checks if an email address is valid
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Suburb/Town check (max 40 characters)
    if (!empty($suburb_or_town) && strlen($suburb_or_town) > 40) {
        $errors[] = "Suburb/Town cannot exceed 40 characters.";
    }

    // Postcode check (Exactly 4 digits & Numeric)
    if (!empty($postcode) && !preg_match("/^[0-9]{4}$/", $postcode)) { // preg_match is a php function that checks if a string matches required pattern
        $errors[] = "Postcode must be exactly 4 digits.";
    }

    // Phone number check (8 to 12 digits, spaces being allowed)
    if (!empty($phone_number) && !preg_match("/^[0-9 ]{8,12}$/", $phone_number)) {
        $errors[] = "Phone number must contain only 8 to 12 digits (spaces allowed).";
    }

    if (!empty($date_of_birth)) {
        // Pattern / Format check for DOB (dd/mm/yyyy)
        if (!preg_match("/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/", $date_of_birth, $matches)) {
            $errors[] = "Date of birth must be in the format dd/mm/yyyy.";
        } else {
            // Convert the DOB into integers for day, month and year (Allows for further validation checks)
            $day   = (int)$matches[1]; // Splits date into day (Eg. 12/11/2000 would be 12)
            $month = (int)$matches[2]; // Splits date into month (Eg. 12/11/2000 would be 11)
            $year  = (int)$matches[3]; // Splits date into year (Eg. 12/11/2000 would be 2000)

            // Verify it's a real calendar date (rejects 50/11/2009 for example)
            if (!checkdate($month, $day, $year)) { // checkdate is an american function in php. Required to be in month, day, year format for database.
                $errors[] = "The date of birth provided is not a valid calendar date.";
            } 
            // Ensure they aren't born in the future or be 200 years old
            else if ($year > 2026 || $year < 1900) { // || means OR in php
                $errors[] = "Please enter a realistic year of birth.";
            }
        }
    }


    // If validation errors exist, teminate execution and show them to the user
    if (!empty($errors)) {
        echo "<h1>Application Error</h1>";
        echo "<ul>";
        foreach ($errors as $error) { // Loops through each error in the $errors array and displays it as a list item in an unordered list
            echo "<li>" . $error . "</li>";
        }
        echo "</ul>";
        echo "<p><a href='apply.php'>Go back and fix the form</a></p>";
        mysqli_close($conn); // $conn is a variable that stores the database connection
        exit();
    }


// retrieves the selected option(s) in the 'skills' section of the Apply form by using arrays
    $skills = "";

    $skills_posted = $_POST["skills"] ?? [];

    // .= is used to append to a string rather than overwrite it. Puts everything on the same line in the database
    if (isset($_POST["skills"][0])) {
        $skills .= $skills_posted[0];
    }
    if (isset($_POST["skills"][1])) {
        $skills .= ", " . $skills_posted[1];
    }
    if (isset($_POST["skills"][2])) {
        $skills .= ", " . $skills_posted[2];
    }
    if (isset($_POST["skills"][3])) {
        $skills .= ", " . $skills_posted[3];
    }
    if (isset($_POST["skills"][4])) {
        $skills .= ", " . $skills_posted[4];
    }


    // table properties, also creates a table, if it is not found in the database
    // CREATE TABLE IF NOT EXISTS sourced from https://www.geeksforgeeks.org/sql/create-a-table-if-it-doesn-t-exist-in-sql/ Accessed: Wed 20 May 2026
    // Primary Key automatically gives each record a new number
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

// inserts values into the created table
    $sql = "INSERT INTO eoi ( 
    ref_number, first_name, last_name, date_of_birth, gender,
    street_address, suburb_or_town, state, postcode,
    email, phone_number, skills, other_skills, status
    )  
    
    VALUES (
    '" . mysqli_real_escape_string($conn, $ref_number) . "',
    '" . mysqli_real_escape_string($conn, $first_name) . "',
    '" . mysqli_real_escape_string($conn, $last_name) . "',
    '" . mysqli_real_escape_string($conn, $date_of_birth) . "',
    '" . mysqli_real_escape_string($conn, $gender) . "',
    '" . mysqli_real_escape_string($conn, $street_address) . "',
    '" . mysqli_real_escape_string($conn, $suburb_or_town) . "',
    '" . mysqli_real_escape_string($conn, $state) . "',
    '" . mysqli_real_escape_string($conn, $postcode) . "',
    '" . mysqli_real_escape_string($conn, $email) . "',
    '" . mysqli_real_escape_string($conn, $phone_number) . "',
    '" . mysqli_real_escape_string($conn, $skills) . "',
    '" . mysqli_real_escape_string($conn, $other_skills) . "',
    '" . mysqli_real_escape_string($conn, $status) . "'
)";
        // The . is used to build one big string | join strings together
        // mysqli_real_escape_string is used to escape special characters in the input data to prevent SQL injection attacks
        // (Makes them safe for the database as they're treated as plain text rather than code)


    $result = mysqli_query($conn, $sql); // Checks if the query was successful, stores in result variable

    // shows the EOI number to the user, if the submission was successful
    if ($result) {
        $eoi_number = mysqli_insert_id($conn); // Retrieves the auto-generated EOInumber for added record

        echo "<h1>Application Successful</h1>";
        echo "<p>EOI Number: " . $eoi_number . "</p>";

    } else {
        echo "Error";
    }

    mysqli_close($conn); // Ends the connection to the database


?>