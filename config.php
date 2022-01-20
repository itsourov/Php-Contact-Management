<?php

$local = false;
if ($local) {
   
$servername = "localhost";
$sqlusername = "root";
$password = "";
$dbname = "bondhu";

// Create connection
$conn = new mysqli($servername, $sqlusername, $password, $dbname);

if (isset($_SESSION['login_user'])) {
    $userid = $_SESSION['login_user'];
    $userRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `users` WHERE `users`.`id` = '$userid'"));
   
}



$siteUrl = 'http://localhost/www/Bondhu/';

}else{

    $servername = "localhost";
    $sqlusername = "sourovne_project1";
    $password = "power of 12";
    $dbname = "sourovne_project1";
    
    // Create connection
    $conn = new mysqli($servername, $sqlusername, $password, $dbname);
    
    if (isset($_SESSION['login_user'])) {
        $userid = $_SESSION['login_user'];
        $userRow = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `users` WHERE `users`.`id` = '$userid'"));
       
    }
    
    
    
    $siteUrl = 'https://p1.sourov.net/';
}


?>