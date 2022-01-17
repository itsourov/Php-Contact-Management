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
    <title>Document</title>
</head>
<body>
    <table border="1">
    <tr><td>Name</td><td>Date of birth</td></tr>
<?php 
$username = $_SESSION['login_user'];
$sql = "SELECT * FROM `contacts` WHERE `parent_user`= '$username'";
$result =mysqli_query($conn, $sql);
foreach ($result as $row) {
    echo "<tr> <td>". $row['Name']."</td>";
    echo "<td>". $row['date_of_birth']."</td></tr>";
}



?> 
</table>
</body>
</html>