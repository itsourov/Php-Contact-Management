<?php
session_start();
include 'config.php';

if ($_SESSION['login_user'] == null) {
    header("Location: login.php");
    exit();
} else {
    //stay logging in
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {


    if ($_POST['int'] == "add") {
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $date = mysqli_real_escape_string($conn, $_POST['date']);
        $number = mysqli_real_escape_string($conn, $_POST['number']);
       

        $sql = "INSERT INTO `contacts`( `Name`, `number`, `date_of_birth`, `parent_user`) VALUES ('$name','$number','$date','$username')";

        if ($conn->query($sql) === TRUE) {
            $massage = "Contact added successfuly";
            $success = true;
        } else {
            $massage = "Error: " . $sql . "<br>" . $conn->error;
            $success = false;
        }

        $myObj = new stdClass();
        $myObj->massage = $massage;
        $myObj->success = $success;
    

        $myJSON = json_encode($myObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        echo $myJSON;
        
    }
}
