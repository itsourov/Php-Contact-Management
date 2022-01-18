<?php
session_start();

include 'config.php';




if (isset($_SESSION['login_user'])) {
  header("Location: index.php");
  exit();
} else {
  //stay logging in
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // username and password sent from form 

  $myusername = mysqli_real_escape_string($conn, $_POST['username']);
  $mypassword = mysqli_real_escape_string($conn, $_POST['password']);


  $sql = "SELECT * FROM `users` WHERE `username`= '$myusername'  AND `password`='$mypassword'";

  $result = mysqli_query($conn, $sql);


  if (mysqli_num_rows($result) == 1) {

    $row = mysqli_fetch_assoc($result);
    $_SESSION['login_user'] =  $row["username"];


    echo "Wellcome " . $row["full_name"];

    header("Location: ./index.php");
    exit();
  } else if (mysqli_num_rows($result) == 0) {
    echo "0 results";
  } elseif (mysqli_num_rows($result) > 1) {
    echo "Please contact the admin +8801872934185";
  }
}


?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <title>login</title>
</head>

<body class="main-bg">

  <?php
  include 'nav.php';
  ?>

  <!-- Login Form -->
  <div class="container">
    <div class="row justify-content-center mt-5">
      <div class="col-lg-4 col-md-6 col-sm-6">
        <div class="card shadow">
          <div class="card-title text-center border-bottom">
            <h2 class="p-3">Login</h2>
          </div>
          <div class="card-body">
            <form action="" method="POST">
              <div class="mb-4">
                <label for="username" class="form-label">Username/Email</label>
                <input type="text" class="form-control" id="username" name="username" />
              </div>
              <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password"/>
              </div>
              <div class="mb-4">
                <input type="checkbox" class="form-check-input" id="remember" />
                <label for="remember" class="form-label">Remember Me</label>
                <p class="message">Not registered? <a href="#">Create an account</a></p>
              </div>
              
              <div class="d-grid">
                <button type="submit" class="btn text-light main-bg">Login</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>


  <style>
    :root{
  --main-bg:#e91e63;
}

.main-bg {
  background: var(--main-bg) !important;
}

input:focus, button:focus {
  border: 1px solid var(--main-bg) !important;
  box-shadow: none !important;
}

.form-check-input:checked {
  background-color: var(--main-bg) !important;
  border-color: var(--main-bg) !important;
}

.card, .btn, input{
  border-radius:0 !important;
}
  </style>

</body>

</html>