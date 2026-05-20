<?php

// checks database settings
require_once("settings.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") { // checks if the form was submitted using the POST method

    $conn = mysqli_connect($host, $user, $pwd, $sql_db); // establishes a connection using the configuration in settings.php

    if (!$conn) {
        die("Connection failed");
    }
 // copies data from apply.html and saves it to variables
    $ref_number = $_POST["ref_number"];
    $first_name = $_POST["first_name"];
    $last_name = $_POST["last_name"];
    $date_of_birth = $_POST["date_of_birth"];
    $gender = $_POST["gender"];
    $street_address = $_POST["street_address"];
    $suburb_or_town = $_POST["suburb_or_town"];
    $state = $_POST["state"];
    $postcode = $_POST["postcode"];
    $email = $_POST["email"];
    $phone_number = $_POST["phone_number"];
    $other_skills = $_POST["other_skills"];
 
// retrieves the selected option(s) in the 'skills' section of the Apply form by using arrays
    $skills = "";

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
        other_skills TEXT
    )";

    mysqli_query($conn, $table_sql);

// inserts values into the created table
    $sql = "INSERT INTO eoi ( 
    ref_number, first_name, last_name, date_of_birth, gender,
    street_address, suburb_or_town, state, postcode,
    email, phone_number, skills, other_skills
    )  
    
    VALUES (
    '$ref_number', '$first_name', '$last_name', '$date_of_birth', '$gender',
    '$street_address', '$suburb_or_town', '$state', '$postcode',
    '$email', '$phone_number', '$skills', '$other_skills'
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
}

?>