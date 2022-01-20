<?php

$servername = "localhost";
$sqlusername = "sohojco_php";
$password = "power of 12";
$dbname = "sohojco_php";

// Create connection
$conn = new mysqli($servername, $sqlusername, $password, $dbname);

if (isset($_SESSION['login_user'])) {
    $userid = $_SESSION['login_user'];
    $userRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `users` WHERE `users`.`id` = '$userid'"));
   
}



$siteUrl = 'http://php.sohoj.co/';

?>