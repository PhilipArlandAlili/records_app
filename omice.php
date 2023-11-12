<?php
require_once('config/config.php');
require_once('config/db.php');

require 'vendor/autoload.php';

$faker = Faker\Factory::create();

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

for ($i = 0; $i < 200; $i++) {

    $lastname = mysqli_real_escape_string($conn, $faker->lastname);
    $firstname = mysqli_real_escape_string($conn, $faker->firstname);
    $address = mysqli_real_escape_string($conn, $faker->address);
    $office = mysqli_real_escape_string($conn, $faker->numberBetween(1, 100));

    $query = "INSERT INTO employee(lastname, firstname, address, office_id) 
            VALUES ('$lastname', '$firstname', '$address','$office')";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Error: " . mysqli_error($conn));
    }
}
?>