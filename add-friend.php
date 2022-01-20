<?php
session_start();

if ($_SESSION['login_user'] == null) {
    header("Location: login.php");
    exit();
} else {
    //stay logging in
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
       

    <title>Add Friends no css 2</title>
</head>

<body>

<script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
<script>
$(function () {
    
        $('#form_id').on('click','#submit', function(e){
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "api.php",
                data: $('#form_id').serialize(),
                success: function(data) {
                    alert(data);
                    $('.box').val("");
                },
                error:function(data) {
                    alert("fail");
                }
            });
            return false;
        });
    });
    </script>
</head>
<form method="post" id="form_id">
<input type="text" class="box" name="name" placeholder="full name">
<input type="date" class="box"  name="date" placeholder="date of birth">
<input type="text" class="box"  name="number" placeholder="phn number">
<input type="hidden" name="int" value="add">
<input type="button" id="submit" name="submit" value="Send">
</form>

</body>

</html>