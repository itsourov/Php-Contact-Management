<?php

$servername = "localhost";
$sqlusername = "root";
$password = "";
$dbname = "bondhu";

// Create connection
$conn = new mysqli($servername, $sqlusername, $password, $dbname);

if (isset($_SESSION['login_user'])) {
    $username =$_SESSION['login_user'];
}


$siteUrl ='http://localhost/www/Bondhu/';
