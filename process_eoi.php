<?php

// checks database settings
require_once("settings.php");
 // establishes a connection using the configuration in settings.php
 // if the user tries to directly access the page, they will be sent back to the Apply page

 // Function created to handle 'Trim, strip slashes and escape HTML'
function sanitise_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
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
    $status = sanitise_input($_POST["status"]);


    // Server-side validation rules, checks if any of the required fields are empty and adds an error message to the $errors array if they are
    
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


    // Format Checks
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    if (!empty($postcode) && !preg_match("/^[0-9]{4}$/", $postcode)) {
        $errors[] = "Postcode must be exactly 4 digits.";
    }


    // If validation errors exist, teminate execution and show them to the user
    if (!empty($errors)) {
        echo "<h1>Application Error</h1>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . $error . "</li>";
        }
        echo "</ul>";
        echo "<p><a href='apply.php'>Go back and fix the form</a></p>";
        mysqli_close($conn);
        exit();
    }


// retrieves the selected option(s) in the 'skills' section of the Apply form by using arrays
    $skills = "";

    $skills_posted = $_POST["skills"] ?? [];

    if (isset($_POST["skills"][0])) {
        $skills .= $_POST["skills"][0];
    }
    if (isset($_POST["skills"][1])) {
        $skills .= ", " . $_POST["skills"][1];
    }
    if (isset($_POST["skills"][2])) {
        $skills .= ", " . $_POST["skills"][2];
    }
    if (isset($_POST["skills"][3])) {
        $skills .= ", " . $_POST["skills"][3];
    }
    if (isset($_POST["skills"][4])) {
        $skills .= ", " . $_POST["skills"][4];
    }


    // table properties, also creates a table, if it is not found in the database
    // CREATE TABLE IF NOT EXISTS sourced from https://www.geeksforgeeks.org/sql/create-a-table-if-it-doesn-t-exist-in-sql/ Accessed: Wed 20 May 2026
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

    $result = mysqli_query($conn, $sql);

    // shows the EOI number to the user, if the submission was successful
    if ($result) {
        $eoi_number = mysqli_insert_id($conn);

        echo "<h1>Application Successful</h1>";
        echo "<p>EOI Number: " . $eoi_number . "</p>";

    } else {
        echo "Error";
    }

    mysqli_close($conn);


?>