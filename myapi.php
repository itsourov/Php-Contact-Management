<?php
session_start();
include 'config.php';

$db = $conn;
$errors = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if ($_POST['int'] == "reset-pass") {
        $rand = rand(100000, 999999);
        $username = mysqli_real_escape_string($db, $_POST['username']);

        if (empty($username)) {
            array_push($errors, "Username is required");
        }

        if (count($errors) == 0) {


            $results = mysqli_query($db, "SELECT * FROM users WHERE username='$username'");

            if (mysqli_num_rows($results) == 1) {

                $userRow = mysqli_fetch_assoc($results);

                $user_id = $userRow['id'];

                $tokenUpdateSql = "UPDATE `users` SET `token` = '$rand' WHERE `users`.`id` = $user_id";


                if ($conn->query($tokenUpdateSql) === TRUE) {


                    $to = $userRow['email'];

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
                    $myObj->step = 1;
                    $myObj->massage = "Password reset email was sent to your email " . hideEmailAddress($userRow['email']);
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
    } elseif ($_POST['int'] == "otp-verification") {
        $username = $_POST['username'];
        $otp = $_POST['otp'];

        if (empty($username)) {
            array_push($errors, "Username is required");
        }
        if (empty($otp)) {
            array_push($errors, "OTP is required");
        }
        if (count($errors) == 0) {
            $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `users`  WHERE `username`= '$username'"));

            if ($result['token'] == $otp) {

                $myObj = new stdClass();
                $myObj->status = true;
                $myObj->step = 2;
                $myObj->massage = "Opt verified";
                $myJSON = json_encode($myObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
                echo $myJSON;
            } else {
                array_push($errors, "OTP invalid");
            }
        }
    } elseif ($_POST['int'] == "new-pass") {
        $username = $_POST['username'];
        $otp = $_POST['otp'];
        $password_1 = mysqli_real_escape_string($db, $_POST['password']);

        if (empty($username)) {
            array_push($errors, "Username is required");
        }
        if (empty($otp)) {
            array_push($errors, "OTP is required");
        }
        if (empty($password_1)) {
            array_push($errors, "Password is required");
        }
        if (strlen($password_1) < 6) {
            array_push($errors, "Password is too short");
        }

        if (count($errors) == 0) {
            $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `users`  WHERE `username`= '$username'"));
            if ($result['token'] == $otp) {

                $password = md5($password_1);

                $query = "UPDATE `users` SET `password` = '$password' WHERE `users`.`username` = '$username'";

                if ($conn->query($query) === TRUE) {

                    $_SESSION['login_user'] = $result['id'];

                    $myObj = new stdClass();
                    $myObj->status = true;
                    $myObj->step = 3;
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
    }


    if (count($errors) > 0) {


        $myObj = new stdClass();
        $myObj->status = false;
        $myObj->errors = $errors;
        $myJSON = json_encode($myObj, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        echo $myJSON;
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
