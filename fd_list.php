<?php
session_start();
include("./config.php");


if ($_SESSION['login_user'] == null) {
    header("Location: login.php");
    exit();
} else {
    $sql = "SELECT * FROM `contacts` WHERE `parent_user`= '$username'";
    $result = mysqli_query($conn, $sql);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
    <?php
    include 'nav.php';
    ?>


    <div class="container">
        <div class="page_heading text-center">
            <h1>Friend Lists</h1>
        </div>

        <?php
        foreach ($result as $row) {

        ?>
            <a href="<?php echo $siteUrl . "fd-details.php?id=" . $row['id']; ?>">
                <div class="fd-wrapper shadow">
                    <div class="row">

                        <div class="col ">
                            <img src="./image/user.png" alt="" class="img-fluid shadow">
                        </div>
                        <div class="col ">
                            <h4 class="title"><?php echo  $row['full_name']; ?></h4>
                            <p>Tlkhari Shalikha</p>
                        </div>
                    </div>


                </div>
            </a>


        <?php

        }

        ?>




    </div>


    <style>
        a {
            color: black;
            text-decoration: none;
        }

        a:hover {
            color: black;
            text-decoration: none;
        }

        body {
            background-color: #e7e7e7;
        }

        .col {
            max-width: fit-content;
        }

        .fd-wrapper {
            background-color: white;
            border-radius: 10px;
            margin-top: 15px;
            padding: 5px;
        }
        .fd-wrapper:hover{
            transform: scale(105%);
        }

        .fd-wrapper img {

            width: 100px;
            object-fit: cover;
            border-radius: 50%;
        }
    </style>



    <!-- 
    <table border="1">
        <tr>
            <td>Name</td>
            <td>Date of birth</td>
        </tr>
        <?php
        // $username = $_SESSION['login_user'];
        // $sql = "SELECT * FROM `contacts` WHERE `parent_user`= '$username'";
        // $result = mysqli_query($conn, $sql);
        // foreach ($result as $row) {
        //     echo "<tr> <td>" . $row['Name'] . "</td>";
        //     echo "<td>" . $row['date_of_birth'] . "</td></tr>";
        // }



        ?>
    </table> -->
</body>

</html>