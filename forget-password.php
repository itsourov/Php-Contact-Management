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
            outline: 0;
            background: #f2f2f2;
            width: 100%;
            border: 0;
            border-radius: 5px;
            margin: 0 0 15px;
            padding: 15px;
            box-sizing: border-box;
            font-size: 14px;
            font-family: 'Comfortaa', cursive;
        }

        input:focus {
            background: #dbdbdb;
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
        <h3 class="title mb-3">Reset Password</h3>
        <form enctype="multipart/form-data" id="forgetPassForm" onsubmit="reqReset(); return false;">
            <input class=" mb-3" type="text" placeholder="Username" name="username" >
            <input type="hidden" name="int" value="reset-pass">
            <button class="btn mb-3" onclick="reqReset()" type="button">Request Reset</button>
        </form>

    </div>


    <style>
        @import url('https://fonts.googleapis.com/css2?family=Comfortaa&display=swap');
    </style>
    <script>
        var alert = document.getElementById('alert');
        alert.style.display = "none"



        function fadeout(element) { // 1
            element.style.opacity = 1; // 2
            let hidden_process = window.setInterval(function() { // 3
                if (element.style.opacity > 0) { // 4
                    element.style.opacity = parseFloat(element.style.opacity - 0.01).toFixed(2); // 5 
                } else {
                    element.style.display = 'none'; // 6 
                    element.style.opacity =1;
                    console.log('1');
                    clearInterval(hidden_process);
                }
            }, 4);


        };

        function reqReset() {
            var data = new FormData(document.getElementById("forgetPassForm"));


            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'public-api.php', true);
            xhr.onload = function() {
                // do something to response
                console.log(this.responseText);

                var res = JSON.parse(this.responseText);

                alert.style.display = "block"
                if (res.status) {
                    alert.innerHTML = res.massage
                } else {
                    var errorText = ""
                    res.errors.forEach(function(data, index) {

                        errorText = errorText + "*" + data + "<br>"

                    });
                    alert.innerHTML = errorText
                }



              
            };
            xhr.send(data);
        }
    </script>

</body>

</html>