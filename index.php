<?php
session_start();
include("./config.php"); 


if( $_SESSION['login_user']==null){
    header("Location: login.php");
    exit();
}else {
   //stay logging in
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dash</title>
</head>
<body>
    <a href="add.php">Add new friend</a>
    <br>
    <a href="fd_list.php"> friend List</a>
    <br>

    <h3>you have total <?php 
$username = $_SESSION['login_user'];
$sql = "SELECT id FROM `contacts` WHERE `parent_user`= '$username'";
echo mysqli_num_rows(mysqli_query($conn, $sql));
?> friends</h3>

<table border="1">
<?php 
$sql = "SELECT * FROM `users`";
$result =mysqli_query($conn, $sql);



$row = mysqli_fetch_assoc($result);
echo "<tr> <td>Full Name</td><td>". $row['full_name']."</td></tr>";
echo "<tr> <td>Username</td><td>". $row['username']."</td></tr>";
echo "<tr> <td>Password (for testing)</td><td>". $row['password']."</td></tr>";

?> 
</table>
</body>
</html>
    