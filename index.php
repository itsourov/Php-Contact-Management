<?php
session_start();
include("./config.php");


if ($_SESSION['login_user'] == null) {
    header("Location: login.php");
    exit();
} else {
    $sql = "SELECT * FROM `users` WHERE `users`.`username` = '$username'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" integrity="sha512-Fo3rlrZj/k7ujTnHg4CGR2D7kSs0v4LLanw2qksYuRlEzO+tcaEPQogQ0KaoGN26/zrn20ImR1DfuLWnOo7aBA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <title>Dash</title>
</head>

<body>
    <?php
    include 'nav.php';
    ?>
    <div class="gray">

    </div>
    <div class="container white-box ">
        <div class="image-box  mx-auto position-relative">
            <img class="img-fluid shadow" id="blah" src="<?php echo $siteUrl . $row['profile_pic']; ?>" alt="">
            <div id="spinner" class="spinner-grow text-secondary loader img-fluid" role="status" style="display: none;">

            </div>
            <div class="upload-btn" style="display: none;">
                <input type="file" accept="image/*" id="imgInp" name="fileToUpload" multiple>
            </div>



            <button onclick="enableEdit()" class="edit-btn btn btn-success btn-sm rounded-0" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></button>
        </div>
        <div class="name">
            <h3 class="title"><?php echo  $row['full_name']; ?></h3>
            <p><?php echo  $row['username']; ?></p>
        </div>

        <form id="info-table" enctype="multipart/form-data">

            <table class="mx-auto table table-striped info-table text-start">

                <tr>
                    <td>Full Name</td>
                    <td><input type="text" name="full_name" value="<?php echo $row['full_name']; ?> " disabled></td>
                </tr>
                <tr>
                    <td>Username</td>
                    <td><input type="text" id="username" name="username" value="<?php echo  $row['username']; ?>" disabled>
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
                    <td><input type="text" name="number" value="<?php echo $row['number']; ?> " disabled></td>
                </tr>
                <tr>
                    <td>Emaile</td>
                    <td><input type="text" name="email" value="<?php echo $row['email']; ?> " disabled></td>
                </tr>
                <tr>
                    <td>Number of friends</td>
                    <td><input type="text" value="<?php echo mysqli_num_rows(mysqli_query($conn, "SELECT id FROM `contacts` WHERE `parent_user`= '$username'")); ?> " disabled></td>
                </tr>



            </table>
            <div id="buttons" class=" mx-auto text-center" style="display: none;">

                <button type="button" class="btn btn-warning m-1" onclick="disableEdit()">Cancel</button>
                <button type="button" class="btn btn-success m-1" id="upload" value="Save">Save</button>


            </div>
        </form>

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
            max-width: 400px;
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

        .edit-btn {

            position: absolute;
            bottom: 35px;
            right: -40px;
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


    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <script>
        function enableEdit() {
            $("input").prop('disabled', false);

            $("#buttons").css("display", "block");
            $(".upload-btn").css("display", "block");


        }

        function disableEdit() {
            $("input").prop('disabled', true);

            $("#buttons").css("display", "none");
            $(".upload-btn").css("display", "none");
            $("#spinner").css("display", "None");
            $("#info-table")[0].reset();
            $('#username').attr("class", "");

        }

        $('#username').on('input', function(e) {
            $("#spinner").css("display", "block");
            var input = $(this);
            var val = input.val();

            $.ajax({
                url: "api.php?int=checkUsername&checkName=" + val,
                type: 'GET',
                dataType: 'json', // added data type
                success: function(res) {
                    $("#spinner").css("display", "none");

                    if (res.available) {
                        $('#username').attr("class", "form-control is-valid");
                        $('#valid-feedback').html(res.massaage);
                    } else {
                        $('#username').attr("class", "form-control is-invalid");
                        $('#invalid-feedback').html(res.massaage);
                    }
                }
            });


        });

        var imageSelected = false;

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#blah').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
                $("#spinner").css("display", "block");
                imageSelected = true;
            }
        }

        $("#imgInp").change(function() {
            readURL(this);

            var file_data = $('#imgInp').prop('files')[0];
            var form_data = new FormData();
            form_data.append('file', file_data);

            $.ajax({
                url: 'api.php', // point to server-side PHP script 
                dataType: 'json', // what to expect back from the PHP script, if anything
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
             
                success: function(output) {
                    alert(output.massage); // display response from the PHP script, if any
                    $("#spinner").css("display", "none");
                }
            });
            $('#imgInp').val(''); /* Clear the file container */
        });

        $('#upload').on('click', function() {

        });
    </script>

</body>

</html>