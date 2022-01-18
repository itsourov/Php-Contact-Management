<?php
session_start();
include("./config.php");


if ($_SESSION['login_user'] == null) {
    header("Location: login.php");
    exit();
} else {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $sql = "SELECT * FROM `contacts` WHERE `parent_user`= '$username' AND `id`= '$id'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
        if (mysqli_num_rows($result) != 1) {
            header("Location: fd_list.php");
            exit();
        }
    } else {
        header("Location: fd_list.php");
        exit();
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
    <title>Document</title>
</head>

<body>
    <?php
    include 'nav.php';
    ?>
    <div class="gray">

    </div>
    <div class="container white-box">
        <div class="image-box  mx-auto ">
            <img class="img-fluid shadow" src="<?php echo $siteUrl . $row['profile_pic']; ?>" alt="">
        </div>
        <div class="name">
            <h3 class="title"><?php echo  $row['full_name']; ?></h3>

        </div>


        <table class="mx-auto table table-striped info-table text-start">
            <?php

            echo "<tr> <td>Full Name</td><td>" . $row['full_name'] . "</td></tr>";
            echo "<tr> <td>Date of birth</td><td>" . $row['date_of_birth'] . "</td></tr>";
            echo "<tr> <td>Number</td><td>" . $row['number'] . "</td></tr>";
            echo "<tr> <td>Number of friends</td><td>" . mysqli_num_rows(mysqli_query($conn, "SELECT id FROM `contacts` WHERE `parent_user`= '$username'")) . "</td></tr>";

            ?>


        </table>
        <div class=" mx-auto text-center">

            <button type="button" class="btn btn-warning m-1">Cancel</button>
            <button type="button" class="btn btn-success m-1">Save</button>


        </div>


    </div>
    <style>
        body {
            background-color: aqua;
        }

        .gray {
            background-color: gray;
            height: 50vh;
            max-height: 400px;
        }

        .white-box {
            margin-top: -10vh;
            background-color: white;
            border-radius: 10px;
            text-align: center;
            max-width: 90%;
            padding-bottom: 5vh;
            margin-bottom: 10vh;

        }

        .image-box {
            max-height: 160px;
            max-width: 160px;


        }

        .image-box img {
            border-radius: 50%;
            margin-top: -80px;

        }

        .title {
            margin-top: 30px;

            min-height: 32px;
            color: #3C4858;
            font-weight: 700;
            font-family: "Roboto Slab", "Times New Roman", serif;
        }


        .info-table {
            max-width: 400px;
        }
    </style>

</body>

</html>