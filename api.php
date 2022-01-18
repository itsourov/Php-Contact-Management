<?php
session_start();
include 'config.php';

if ($_SESSION['login_user'] == null) {
    header("Location: login.php");
    exit();
} else {
    //stay logging in
}

if ($_SERVER["REQUEST_METHOD"] == "GET") {


    if ($_GET['int'] == "add") {
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
    } elseif ($_GET['int'] == "dashinfo") {

        $sql = "SELECT * FROM `users`";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);




        $myObj = new stdClass();
        $myObj->username = $row['username'];
        $myObj->fullName = $row['full_name'];
        $myObj->numberOfFriends = mysqli_num_rows(mysqli_query($conn, "SELECT id FROM `contacts` WHERE `parent_user`= '$username'"));


        $myJSON = json_encode($myObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        echo $myJSON;
    } elseif ($_GET['int'] == "checkUsername") {
        $checkName = $_GET['checkName'];
        $sql = "SELECT username FROM `users`  WHERE `username`= '$checkName'";
        $result = mysqli_query($conn, $sql);
        if ($_GET['checkName'] =="") {
            $available = false;
            $massage = "Username cant be empty";
        }elseif (  mysqli_num_rows($result) != 1 ||  $_GET['checkName']==$username) {
            $available = true;
            $massage = "the username is available to use";

        } else {
            $available = false;
            $massage = "the username is not available";
        }
        $myObj = new stdClass();
        $myObj->available = $available;
        $myObj->massaage = $massage;
        $myJSON = json_encode($myObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        echo $myJSON;
    }
}
