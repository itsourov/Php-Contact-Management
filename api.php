<?php
session_start();
include 'config.php';

if ($_SESSION['login_user'] == null) {
    header("Location: login.php");
    exit();
} else {
    //stay logging in
}

$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "GET") {

    $username = $userRow['username'];

    if ($_GET['int'] == "checkUsername") {
        $checkName = $_GET['checkName'];
        $sql = "SELECT username FROM `users`  WHERE `username`= '$checkName'";
        $result = mysqli_query($conn, $sql);
        if ($_GET['checkName'] == "") {
            $available = false;
            $massage = "Username cant be empty";
        } elseif (strpos($_GET['checkName'], ' ') !== false) {
            $available = false;
            $massage = "Username cant contain space";
        } elseif (mysqli_num_rows($result) != 1 ||  $_GET['checkName'] == $username) {
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
    } elseif ($_GET['int'] == "logout") {
        $_SESSION['login_user'] = null;
        header("Location: login.php");
        exit();
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($_POST['int'] == "updateUserInfo") {


        $chagedusername = $_POST['username'];
        $full_name = $_POST['full_name'];
        $dob = $_POST['dob'];
        $number = $_POST['number'];
        $email = $_POST['email'];


        if (empty($chagedusername)) {
            array_push($errors, "Username is required");
        }
        if (empty($full_name)) {
            array_push($errors, "Name is required");
        }
        if (empty($email)) {
            array_push($errors, "Email is required");
        }





      
        if (count($errors) == 0) {

            if (mysqli_num_rows(mysqli_query($conn, "SELECT id FROM `users` WHERE `users`.`username`= '$chagedusername'")) == 0) {
                $conn->query("UPDATE `users` SET `username` = '$chagedusername' WHERE `users`.`id` = $userid");
            }
    

            $sql = "UPDATE `users` SET `full_name`='$full_name',`dob`='$dob',`number`='$number',`email`='$email' WHERE `users`.`id`='$userid'";
            if ($conn->query($sql) === TRUE) {
                $massage = "Info updated successfuly";
                $success = true;

                $myObj = new stdClass();
                $myObj->success = $success;
                $myObj->massaage = $massage;
                $myJSON = json_encode($myObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                echo $myJSON;
            } else {
                $massage = "Error: " . $sql . "<br>" . $conn->error;
                array_push($errors, $massage);
            }
        }
    }

    if ($_POST['int'] == "uploadImage") {
        $target_dir = "uploads/" . $userid . "/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        if ($_FILES['file']['error'] > 0) {
            echo 'Error: ' . $_FILES['file']['error'] . '<br>';
        } else {

            $filepath =  $target_dir . "profile_pic_" . rand() . $_FILES['file']['name'];

            if (move_uploaded_file($_FILES['file']['tmp_name'], $filepath)) {



                $sql = "UPDATE `users` SET `profile_pic` = '$filepath' WHERE `id`= '$userid'";

                if ($conn->query($sql) === TRUE) {
                    $massage = "Image Uploaded successfuly";
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
    }
}

if (count($errors) > 0) {

    $myObj = new stdClass();
    $myObj->status = false;
    $myObj->errors = $errors;
    $myJSON = json_encode($myObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    echo $myJSON;
}
