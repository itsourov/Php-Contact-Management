<?php
session_start();
include 'config.php';

if (isset($userid)) {
    header("Location: index.php");
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        body {
            background: linear-gradient(90deg, #4b6cb7 0%, #182848 100%);

        }

        .whitebox {
            width: 360px;
            max-width: 95%;
            background-color: white;
            padding: 45px;
            border-radius: 10px;
            text-align: center;
            margin-top: 8%;
            font-family: 'Comfortaa', cursive;
        }


        input {
            width: 100%;
            border-radius: 5px;
            padding: 15px;
            font-size: 14px;
            font-family: 'Comfortaa', cursive;
        }


        .btn {
            font-family: 'Comfortaa', cursive;
            text-transform: uppercase;
            background: #4b6cb7;
            width: 100%;
            border-radius: 5px;
            padding: 15px;
            color: #FFFFFF;
            font-size: 14px;
            -webkit-transition: all 0.3 ease;
            transition: all 0.3 ease;
            cursor: pointer;
        }

        .btn:active {
            background: #395591;
        }
    </style>
    <title>Document</title>
</head>

<body>
    <?php
    include 'nav.php';
    ?>


    <div class="whitebox mx-auto">

        <div class="alert alert-primary" role="alert" id="alert">

        </div>
        <form enctype="multipart/form-data" id="signUpForm">
            <input class="form-control mb-3" type="text" name="full_name" placeholder="Full Name" required>
            <input class="form-control" type="text" placeholder="Username" id="username" name="username" onchange="checkUsername();" onkeyup="this.onchange();" onpaste="this.onchange();" oninput="this.onchange();" required>
            <div class="valid-feedback" id="valid-feedback">
            </div>
            <div class="invalid-feedback" id="invalid-feedback">
            </div>
            <input class="form-control mb-3 mt-3" type="email" name="email" placeholder="Email" required>
            <input class="form-control mb-3 " type="text" placeholder="new Password" name="password_1" required>
            <input class="form-control mb-3 " type="text" placeholder="Confirm new Password" name="password_2" required>
            <input type="hidden" name="int" value="sign-up">
            <button class="btn" onclick="signUp()" type="button">Sign Up</button>
        </form>
        <p class="message mt-3">Already have an account? <a href="login.php">Log in</a></p>
    </div>


    <style>
        @import url('https://fonts.googleapis.com/css2?family=Comfortaa&display=swap');
    </style>


    <script>
        // username cheching system
        var username = document.getElementById('username');

        function checkUsername() {
            username.classList.add("form-control");

            fetch("public-api.php?int=checkUsername&checkName=" + username.value).then(function(response) {
                return response.json();
            }).then(function(data) {


                if (data.available) {
                    username.classList.remove("is-invalid");
                    username.classList.add("is-valid");
                    document.getElementById("valid-feedback").innerHTML = data.massaage;
                } else {
                    username.classList.remove("is-valid");
                    username.classList.add("is-invalid");
                    document.getElementById("invalid-feedback").innerHTML = data.massaage;
                }


            }).catch(function() {
                console.log("Booo");
            });


        }

        var alert = document.getElementById('alert');
        alert.style.display = "none"

        function signUp() {
            var data = new FormData(document.getElementById("signUpForm"));


            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'public-api.php', true);
            xhr.onload = function() {
                // do something to response
                console.log(this.responseText);
alert(this.responseText);

                var res = JSON.parse(this.responseText);

                alert.style.display = "block"
                if (res.status) {
                    alert.innerHTML = res.massage
                    location.reload();
                } else {
                    var errorText = ""
                    res.errors.forEach(function(data, index) {
                      
                        errorText = errorText+ "*"+data+"<br>"
                    
                    });
                    alert.innerHTML = errorText
                }



                setTimeout(function() {
                    alert.style.display = "none"
                }, 5000);
            };
            xhr.send(data);
        }
    </script>
</body>

</html>
