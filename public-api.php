<?php
session_start();
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
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
        } elseif (mysqli_num_rows($result) != 1) {
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($_POST['int'] == "sign-up") {



        // initializing variables
        $username = "";
        $email    = "";
        $errors = array();

        // connect to the database
        $db = $conn;


        $username = mysqli_real_escape_string($db, $_POST['username']);
        $full_name = mysqli_real_escape_string($db, $_POST['full_name']);
        $email = mysqli_real_escape_string($db, $_POST['email']);
        $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);
        $password_2 = mysqli_real_escape_string($db, $_POST['password_2']);



        // form validation: ensure that the form is correctly filled ...
        // by adding (array_push()) corresponding error unto $errors array
        if (empty($username)) {
            array_push($errors, "Username is required");
        }
        if (empty($email)) {
            array_push($errors, "Email is required");
        }
        if (empty($password_1)) {
            array_push($errors, "Password is required");
        }
        if ($password_1 != $password_2) {
            array_push($errors, "The two passwords do not match");
        }


        // first check the database to make sure 
        // a user does not already exist with the same username and/or email
        $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
        $result = mysqli_query($db, $user_check_query);
        $user = mysqli_fetch_assoc($result);


        if ($user) { // if user exists
            if ($user['username'] === $username) {
                array_push($errors, "Username already exists");
            }

            if ($user['email'] === $email) {
                array_push($errors, "email already exists");
            }
        }

        if (count($errors) == 0) {
            $password = md5($password_1); //encrypt the password before saving in the database

            $query = "INSERT INTO users (full_name, profile_pic, username, email, password) 
                      VALUES('$full_name', 'images/user.png', '$username', '$email', '$password')";

            if ($conn->query($query) === TRUE) {
                $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM `users`  WHERE `username`= '$username'"));
                $_SESSION['login_user'] = $result['id'];

                $myObj = new stdClass();
                $myObj->status = true;
                $myObj->massage = "login successfull";
                $myJSON = json_encode($myObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                echo $myJSON;
            } else {
                $massage = "Error: " . $sql . "<br>" . $conn->error;
                array_push($errors, $massage);
            }
        }
        if (count($errors) > 0) {


            $myObj = new stdClass();
            $myObj->status = false;
            $myObj->errors = $errors;
            $myJSON = json_encode($myObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            echo $myJSON;
        }
    } elseif ($_POST['int'] == "log-in") {
        $errors = array();

        $db = $conn;
        $username = mysqli_real_escape_string($db, $_POST['username']);
        $password = mysqli_real_escape_string($db, $_POST['password']);

        if (empty($username)) {
            array_push($errors, "Username is required");
        }
        if (empty($password)) {
            array_push($errors, "Password is required");
        }

        if (count($errors) == 0) {
            $password = md5($password);
            $query = "SELECT * FROM users WHERE username='$username' AND password='$password'";
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) == 1) {
                $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM `users`  WHERE `username`= '$username'"));
                $_SESSION['login_user'] = $result['id'];

                $myObj = new stdClass();
                $myObj->status = true;
                $myObj->massage = "login successfull";
                $myJSON = json_encode($myObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                echo $myJSON;
            } else {
                array_push($errors, "Wrong username/password combination");
            }
        }
        if (count($errors) > 0) {


            $myObj = new stdClass();
            $myObj->status = false;
            $myObj->errors = $errors;
            $myJSON = json_encode($myObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            echo $myJSON;
        }
    } elseif ($_POST['int'] == "reset-pass") {
        $db = $conn;
        $errors = array();

        $username = mysqli_real_escape_string($db, $_POST['username']);

        if (empty($username)) {
            array_push($errors, "Username is required");
        }


        if (count($errors) == 0) {

            $rand = rand(100000, 999999);

            $query = "SELECT * FROM users WHERE username='$username'";
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) == 1) {
                $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `users`  WHERE `username`= '$username'"));

                $useridres = $result['id'];

                $tokenUpdateSql = "UPDATE `users` SET `token` = '$rand' WHERE `users`.`id` = $useridres";


                if ($conn->query($tokenUpdateSql) === TRUE) {


                    $to = $result['email'];

                    $subject = 'Password Change Reqest';

                    $headers  = "From: Sourov Biswas <p1@sourov.net>\n";
                    $headers .= "Cc: Projet1 <p1@sourov.net>\n";
                    $headers .= "X-Sender: Projet1 <p1@sourov.net>\n";
                    $headers .= 'X-Mailer: PHP/' . phpversion();
                    $headers .= "X-Priority: 1\n"; // Urgent message!
                    $headers .= "Return-Path: p1@sourov.net\n"; // Return path for errors
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=iso-8859-1\n";

                    $message = '
                    <div style="font-family: Helvetica,Arial,sans-serif;min-width:1000px;overflow:auto;line-height:2">
                    <div style="margin:50px auto;width:70%;padding:20px 0">
                        <div style="border-bottom:1px solid #eee">
                            <a href="" style="font-size:1.4em;color: #00466a;text-decoration:none;font-weight:600">Project 1</a>
                        </div>
                        <p style="font-size:1.1em">Hi,</p>
                        <p>Thank you for using our Site. Use the following OTP to complete your Password reset procedures. OTP is valid for 5 minutes</p>
                        <h2 style="background: #00466a;margin: 0 auto;width: max-content;padding: 0 10px;color: #fff;border-radius: 4px;">' . $rand . '</h2>
                        <p style="font-size:0.9em;">Regards,<br />Your Brand</p>
                        <hr style="border:none;border-top:1px solid #eee" />
                        <div style="float:right;padding:8px 0;color:#aaa;font-size:0.8em;line-height:1;font-weight:300">
                            <p>Project 1</p>
                            <p>Sourov</p>
                        </div>
                    </div>
                </div>';


                    mail($to, $subject, $message, $headers);


                    $myObj = new stdClass();
                    $myObj->status = true;
                    $myObj->massage = "Password reset email was sent to your email " . hideEmailAddress($result['email']);
                    $myJSON = json_encode($myObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    echo $myJSON;
                } else {
                    $massage = "Error: " . $tokenUpdateSql . "<br>" . $conn->error;
                    array_push($errors, $massage);
                }
            } else {
                array_push($errors, "username dont exists");
            }
        }
        if (count($errors) > 0) {


            $myObj = new stdClass();
            $myObj->status = false;
            $myObj->errors = $errors;
            $myJSON = json_encode($myObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            echo $myJSON;
        }
    } elseif ($_POST['int'] == "otp-verification") {
        $db = $conn;
        $errors = array();


        $username = $_POST['username'];




        if (count($errors) == 0) {
            $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `users`  WHERE `username`= '$username'"));

            if ($result['token'] == $_POST['otp']) {

                $myObj = new stdClass();
                $myObj->status = true;
                $myObj->massage = "Opt verified";
                $myJSON = json_encode($myObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                echo $myJSON;
            } else {
                array_push($errors, "OTP invalid");
            }
        }
        if (count($errors) > 0) {


            $myObj = new stdClass();
            $myObj->status = false;
            $myObj->errors = $errors;
            $myJSON = json_encode($myObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            echo $myJSON;
        }
    } elseif ($_POST['int'] == "new-pass") {
        $db = $conn;
        $errors = array();


        $username = $_POST['username'];
        $otp = $_POST['otp'];

        $password_1 = mysqli_real_escape_string($db, $_POST['password_1']);




        if (count($errors) == 0) {
            $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `users`  WHERE `username`= '$username'"));

            if ($result['token'] == $_POST['otp']) {

                $password = md5($password_1);

                $query = "UPDATE `users` SET `password` = '$password' WHERE `users`.`username` = '$username'";

                if ($conn->query($query) === TRUE) {
                    $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id FROM `users`  WHERE `username`= '$username'"));
                    $_SESSION['login_user'] = $result['id'];

                    $myObj = new stdClass();
                    $myObj->status = true;
                    $myObj->massage = "login successfull";
                    $myJSON = json_encode($myObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                    echo $myJSON;
                } else {
                    $massage = "Error: " . $sql . "<br>" . $conn->error;
                    array_push($errors, $massage);
                }
            } else {
                array_push($errors, "OTP invalid");
            }
        }
        if (count($errors) > 0) {


            $myObj = new stdClass();
            $myObj->status = false;
            $myObj->errors = $errors;
            $myJSON = json_encode($myObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            echo $myJSON;
        }
    }
}


function hideEmailAddress($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        list($first, $last) = explode('@', $email);
        $first = str_replace(substr($first, '3'), str_repeat('*', strlen($first) - 3), $first);
        $last = explode('.', $last);
        $last_domain = str_replace(substr($last['0'], '1'), str_repeat('*', strlen($last['0']) - 1), $last['0']);
        $hideEmailAddress = $first . '@' . $last_domain . '.' . $last['1'];
        return $hideEmailAddress;
    }
}
