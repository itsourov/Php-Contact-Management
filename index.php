<?php
session_start();
include 'config.php';


if ($_SESSION['login_user'] == null) {
    header("Location: login.php");
    exit();
} else {
    $row = $userRow;
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Dash</title>
</head>

<body>
    <?php
    include 'nav.php';
    ?>
    <div class="gray">

    </div>
    <div class="container white-box  position-relative">
        <div class="image-box  mx-auto position-relative">
            <img class="img-fluid shadow" id="blah" src="<?php echo $siteUrl . $row['profile_pic']; ?>" alt="">
            <div id="spinner" class="spinner-grow text-secondary loader img-fluid" role="status" style="display: none;">

            </div>

            <div class="upload-btn" style="display: none;" id="uploadBtn">
                <input type="file" accept="image/*" id="imageInput" name="fileToUpload" multiple>
            </div>
        </div>
        <div class="progress info-table mx-auto" id="progress-bar-wraper" style=" display:none;">
            <div class="progress-bar" id="progress-bar-file1" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
        </div>
        <div id="alert" class="alert alert-success info-table mx-auto" role="alert" style="display: none;">
            Image uploaded successfuly!
        </div>
        <div class="name">
            <h3 class="title"><?php echo  $row['full_name']; ?></h3>
            <p><?php echo  $row['username']; ?></p>
        </div>

        <form id="info-table" enctype="multipart/form-data">

            <table class="mx-auto table table-striped info-table text-start">

                <tr>
                    <td>Full Name</td>
                    <td><input type="text" name="full_name" value="<?php echo $row['full_name']; ?>" disabled></td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td><input type="text" id="username" name="username" value="<?php echo  $row['username']; ?>" onchange="checkUsername();" onkeyup="this.onchange();" onpaste="this.onchange();" oninput="this.onchange();" disabled>
                        <div class="spinner-border text-info" id="spinner" role="status" style="display: none;">

                        </div>
                        <div class="valid-feedback" id="valid-feedback">

                        </div>
                        <div class="invalid-feedback" id="invalid-feedback">

                        </div>
                    </td>
                </tr>

                <tr>
                    <td>Date of birth</td>
                    <td><input type="date" name="dob" value="<?php echo $row['dob']; ?>" disabled></td>
                </tr>
                <tr>
                    <td>Number</td>
                    <td><input type="text" name="number" value="<?php echo $row['number']; ?>" disabled></td>
                </tr>
                <tr>
                    <td>Emaile</td>
                    <td><input type="text" name="email" value="<?php echo $row['email']; ?>" disabled></td>
                </tr>
                <tr>
                    <td>Number of friends</td>
                    <td><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM `contacts` WHERE `parent_user`= '$userid'")); ?></td>
                </tr>



            </table>
            <div id="buttons" class=" mx-auto text-center" style="display: none;">
                <input type="hidden" name="int" value="updateUserInfo">
                <button type="button" class="btn btn-warning m-1" onclick="resetForm()">Cancel</button>
                <button type="button" class="btn btn-success m-1" id="upload" onclick="submitValue()" value="Save">Save</button>


            </div>
        </form>

        <img src="images/edit.svg" alt="" class="edit-btn img-fluid shadow" onclick="enableEdit()">
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
            height: 160px;
            width: 160px;
            object-fit: cover;

        }

        .title {
            margin-top: 30px;

            min-height: 32px;
            color: #3C4858;
            font-weight: 700;
            font-family: "Roboto Slab", "Times New Roman", serif;
        }


        .info-table {
            max-width: 95%;
            width: 400px;
        }

        .info-table input {
            width: 100%;
        }

        .info-table input:disabled {
            cursor: default;
            background-color: #00000000;
            color: black;
            border-color: #00000000;
        }

        .info-table tr {
            width: 100%;
        }

        .edit-btn {
            position: absolute;
            top: 5%;
            right: 5%;
            width: 30px;
            cursor: pointer;
        }

        .edit-btn:hover {
            transform: scale(110%);
        }

        .upload-btn {
            background: aqua;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            position: absolute;
            bottom: 0;
            right: 0;
        }

        .upload-btn input {
            width: 100%;
            height: 100%;
            opacity: 0;
        }



        .loader {
            position: absolute;
            border-radius: 50%;
            margin-top: -80px;
            height: 160px;
            width: 160px;
            bottom: 0;
            right: 0;



        }
    </style>

    <script>
        function resetForm() {
            document.getElementById("info-table").reset();
            disableEdit();
        }

        function enableEdit() {
            var inputs = document.getElementsByTagName('input');
            for (var i = 0; i < inputs.length; i++) {
                if (inputs[i].type != 'submit') {
                    inputs[i].disabled = false;
                }
            }
            document.getElementById('buttons').style.display = "block"
            document.getElementById('uploadBtn').style.display = "block"
        }

        function disableEdit() {
            var inputs = document.getElementsByTagName('input');
            for (var i = 0; i < inputs.length; i++) {
                if (inputs[i].type != 'submit') {
                    inputs[i].disabled = true;
                }
            }
            document.getElementById('buttons').style.display = "none"
            document.getElementById('buttons').style.display = "none"
            username.className = '';


        }


        // username cheching system
        var username = document.getElementById('username');

        function checkUsername() {


            username.classList.add("form-control");

            fetch("api.php?int=checkUsername&checkName=" + username.value)
            .then(function(response) {
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

        function submitValue() {

            const form = document.querySelector('form');
            const data = Object.fromEntries(new FormData(form).entries());
            console.log(data);

            const url = "api.php";
            fetch(url, {
                method: "POST",
                body: new FormData(document.getElementById("info-table")),

            }).then(
                response => response.text() // .json(), etc.
                // same as function(response) {return response.text();}

            ).then(
                html => console.log(html),
                disableEdit()

            );
        }


        let chooseImg = document.getElementById('imageInput'),
            img = document.getElementById('blah'),
            finished = function(e) {
                URL.revokeObjectURL(img.src); //clean up
            };

        img.onload = finished;
        img.onerror = finished;

        chooseImg.addEventListener("change", function(e) {
            if (document.getElementById('imageInput').files[0].size < 1000000) {
                img.src = URL.createObjectURL(e.target.files[0]);
                postFile();
                document.getElementById('spinner').style.display = "block"
            } else {
                alert("image is more than 1 mb");
            }

        }, false);



        function postFile() {
            var formdata = new FormData();

            formdata.append('file', document.getElementById('imageInput').files[0]);
            formdata.append('int', "uploadImage");

            var request = new XMLHttpRequest();

            request.upload.addEventListener('progress', function(e) {
                var file1Size = document.getElementById('imageInput').files[0].size;
                console.log(file1Size);
                document.getElementById('progress-bar-wraper').style.display = "block"
                if (e.loaded <= file1Size) {
                    var percent = Math.round(e.loaded / file1Size * 100);
                    document.getElementById('progress-bar-file1').style.width = percent + '%';
                    document.getElementById('progress-bar-file1').innerHTML = percent + '%';

                    console.log("percent" + percent);
                }

                if (e.loaded == e.total) {
                    document.getElementById('progress-bar-file1').style.width = '100%';
                    document.getElementById('progress-bar-file1').innerHTML = '100%';
                    document.getElementById('progress-bar-wraper').style.display = "none"
                    document.getElementById('spinner').style.display = "none"

                    document.getElementById('alert').style.display = "block"
                    setTimeout(function() {
                        document.getElementById('alert').style.display = "none"
                    }, 4000);




                }
            });

            request.open('post', 'api.php');
            request.timeout = 45000;
            request.send(formdata);
        }
    </script>


</body>

</html>