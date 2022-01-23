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
    <title>Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
            height: 160px;
            width: 160px;
            margin-top: -80px;
            object-fit: cover;

        }

        .upload-btn {
            background-image: url('images/pictures.png');
            background-size: cover;
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

        .row {
            padding: 5px 0px;

        }

        .row:nth-child(odd) {
            background: rgba(0, 0, 0, 0.05);
        }

        .row:nth-child(even) {
            background-color: rgb(175 233 255 / 15%);
        }

        .form-control:disabled {
            background-color: #ffffff00;
            border: 0;


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
    </style>
</head>

<body>
    <?php
    include 'nav.php';
    ?>
    <div class="gray">

    </div>
    <div class="container white-box position-relative">
        <div class="progress info-table mx-auto mb-3" id="progress-bar-wraper" style=" display:none;">
            <div class="progress-bar" id="progress-bar-file1" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
        </div>
        <div class="d-flex flex-column align-items-center text-center">
            <div class="image-box  mx-auto position-relative">
                <img class="img-fluid shadow rounded-circle p-1 bg-primary " id="blah" src="<?php echo $siteUrl . $row['profile_pic']; ?>" alt="">


                <div class="upload-btn">

                    <input type="file" accept="image/*" id="imageInput" name="fileToUpload" multiple>

                </div>

            </div>

            <div id="alert" class="alert alert-success info-table mx-auto" role="alert" style="display: none;">
                Image uploaded successfuly!
            </div>
            <div class="name">
                <h3 class="title"><?php echo  $row['full_name']; ?></h3>
                <p><?php echo  $row['username']; ?></p>
            </div>
        </div>

        <div class="col-lg-8 mx-auto ">
            <div class="alert alert-primary" role="alert" id="responseAlert" style="display: none;">

            </div>
            <form action="#" id="info-table">


                <div class="row">
                    <div class="col-sm-6">
                        <h6 class="mb-0">Full Name</h6>
                    </div>
                    <div class="col-sm-6 ">
                        <input type="text" name="full_name" class="form-control" value="<?php echo $row['full_name']; ?>" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <h6 class="mb-0">Username</h6>
                    </div>
                    <div class="col-sm-6 ">
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo  $row['username']; ?>" onchange="checkUsername();" onkeyup="this.onchange();" onpaste="this.onchange();" oninput="this.onchange();" disabled>
                        <div class="valid-feedback" id="valid-feedback">

                        </div>
                        <div class="invalid-feedback" id="invalid-feedback">

                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-sm-6">
                        <h6 class="mb-0">Email</h6>
                    </div>
                    <div class="col-sm-6 ">
                        <input type="hidden" name="int" value="updateUserInfo">
                        <input type="text" class="form-control" name="email" value="<?php echo $row['email']; ?>" disabled>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <h6 class="mb-0">Date of Birth</h6>
                    </div>
                    <div class="col-sm-6 ">
                        <input type="date" name="dob" class="form-control" value="<?php echo $row['dob']; ?>" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <h6 class="mb-0">Number</h6>
                    </div>
                    <div class="col-sm-6 ">
                        <input type="text" class="form-control" name="number" value="<?php echo $row['number']; ?>" disabled>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-6">
                        <h6 class="mb-0">Number of friends</h6>
                    </div>
                    <div class="col-sm-6 ">
                        <p class="ps-3"><?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM `contacts` WHERE `parent_user`= '$userid'")); ?></p>
                    </div>
                </div>

            </form>

            <div id="buttons" class=" mx-auto text-center" style="display: none;">
                <input type="hidden" name="int" value="updateUserInfo">
                <button type="button" class="btn btn-warning m-1" onclick="resetForm()">Cancel</button>
                <button type="button" class="btn btn-success m-1" id="upload" onclick="submitValue()" value="Save">Save</button>


            </div>

        </div>
        <img src="images/edit.svg" alt="" class="edit-btn img-fluid shadow" onclick="enableEdit()">
    </div>

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

        }

        function disableEdit() {
            var inputs = document.getElementsByTagName('input');
            for (var i = 0; i < inputs.length; i++) {
                if (inputs[i].type != 'submit' && inputs[i].type != 'file') {
                    inputs[i].disabled = true;
                }
            }
            document.getElementById('buttons').style.display = "none"
            document.getElementById('buttons').style.display = "none"
            username.classList.remove("is-invalid");
            username.classList.remove("is-valid");


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



            const url = "api.php";
            fetch(url, {
                method: "POST",
                body: new FormData(document.getElementById("info-table")),

            }).then(
                response => response.json() // .json(), etc.
                // same as function(response) {return response.text();}

            ).then(
                html => manageResponse(html),



            );
        }

        var responseAlert = document.getElementById('responseAlert')

        function manageResponse(json) {

            console.log(json)

            responseAlert.style.display = "block"
            setTimeout(function() {
                responseAlert.style.display = "none"
            }, 4000);

            if (json.success) {
                responseAlert.innerHTML = json.massaage
                disableEdit()
            } else {
                var errorText = ""
                json.errors.forEach(function(data, index) {
                    errorText = errorText + "*" + data + "<br>"
                });
                responseAlert.innerHTML = errorText
            }
        }


        //profile pic update

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
            } else {
                alert("image is more than 1 mb");
            }

        }, false);



        var progressdiv = document.getElementById('progress-bar-wraper');
        var progressBar = document.getElementById('progress-bar-file1');

        function postFile() {
            var formdata = new FormData();

            formdata.append('file', document.getElementById('imageInput').files[0]);
            formdata.append('int', "uploadImage");

            var request = new XMLHttpRequest();

            request.upload.addEventListener('progress', function(e) {
                var file1Size = document.getElementById('imageInput').files[0].size;
                console.log(file1Size);
                progressdiv.style.display = "block"
                if (e.loaded <= file1Size) {
                    var percent = Math.round(e.loaded / file1Size * 100);
                    progressBar.style.width = percent + '%';
                    document.getElementById('progress-bar-file1').innerHTML = percent + '%';

                    console.log("percent" + percent);
                }

                if (e.loaded == e.total) {
                    progressBar.style.width = '100%';
                    progressBar.innerHTML = '100%';
                    document.getElementById('progress-bar-wraper').style.display = "none"


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