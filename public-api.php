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
        
            $rand = rand(100000,999999);

            $query = "SELECT * FROM users WHERE username='$username'";
            $results = mysqli_query($db, $query);
            if (mysqli_num_rows($results) == 1) {
                $result = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `users`  WHERE `username`= '$username'"));
            
                $useridres =$result['id'];

                $tokenUpdateSql = "UPDATE `users` SET `token` = '$rand' WHERE `users`.`id` = $useridres";


                if ($conn->query($tokenUpdateSql) === TRUE) {
                  

                    $to = $result['email'];

$subject = 'Website Change Reqest';

$headers = "From: " . strip_tags('p1@sourov.net') . "\r\n";
$headers .= "Reply-To: ". strip_tags('p1@sourov.net') . "\r\n";
$headers .= "CC: susan@example.com\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: text/html; charset=UTF-8\r\n";

$message = '<p><strong>This is strong text</strong> while this is not.</p>';


mail($to, $subject, $message, $headers);
                    
    
                    $myObj = new stdClass();
                    $myObj->status = true;
                    $myObj->massage = "Password reset email was sent to your email ".hideEmailAddress($result['email']) ;
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
    }
}


function hideEmailAddress($email)
{
    if(filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        list($first, $last) = explode('@', $email);
        $first = str_replace(substr($first, '3'), str_repeat('*', strlen($first)-3), $first);
        $last = explode('.', $last);
        $last_domain = str_replace(substr($last['0'], '1'), str_repeat('*', strlen($last['0'])-1), $last['0']);
        $hideEmailAddress = $first.'@'.$last_domain.'.'.$last['1'];
        return $hideEmailAddress;
    }
}