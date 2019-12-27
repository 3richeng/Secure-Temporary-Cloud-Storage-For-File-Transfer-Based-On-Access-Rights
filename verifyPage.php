<?php
session_start();

if(isset($_SESSION['username']))
{

?>

<html>
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">
    <link rel="icon" href="css/logo.png">
    

    <title>Verify</title>
</head>
<body>
    
    <style>
        body
        {
            background: url(css/verifyPage.jpg) no-repeat center center fixed;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
        }
    </style>
    
    <script>
        
        function _(el)
        {
            return document.getElementById(el);
        }
        
        //verification function
        function verifyFile()
        {    
            var file = _("file").files[0];
            var ins = _("file").files.length;
            var id = $("#id").val();
            var formData = new FormData();
            formData.append("file", file);
            formData.append("id", id);
            
            if(ins < 1){
                swal ( "Something is missing!" ,  "Please choose a file to verify." ,  "error" )
            }
            else
            {
                $.ajax({
                url: 'verifyFile.php',
                type: 'POST',
                data: formData,
                success: function (data) 
                {    
                    var data =JSON.parse(data);
                    _("sha1value").innerHTML = data[0];
                    _("md5value").innerHTML = data[1];
            
                    if(data[2] == "true"){
                        $('#result').html('<div class="alert alert-success"><strong>SUCCESS: </strong>Your file has not been modified from the original download!</div>');
                    }
                    else if(data[2] == "false"){
                        $('#result').html('<div class="alert alert-danger"><strong>DANGER: </strong>Your file has been modified from the original download!</div>');
                    }
                    else{
                        $('#result').html('<div class="alert alert-info "><strong>ERROR: </strong>No file found to compare!.</div>');
                    }
                    },
                cache: false,
                contentType: false,
                processData: false
                    });
                }
        }
        
        //Admin login
        function adminLogin()
        {
            var username = _("username").value;
            var password = _("password").value;
            
            $.ajax({ 
                url: 'adminLogin.php',
                type: 'post',
                data: {username:username, password:password},
                success(data)
                {
                    if(data == "true"){
                        window.location.href = "admin.php";
                    }
                    else if(data == "false"){
                        swal ( "Oops" ,  "Something went wrong!" ,  "error" );
                    }
                    else{
                        swal ( "Oops" ,  "Something went wrong!" ,  "error" );
                    }
                }
            });
        }
    
    </script>

<!-- Nav Bar-->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">THE FILE SOLUTION</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <span class="sr-only">(current)</span>
            <a class="nav-item nav-link" href="uploadPage.php">Upload</a>
            <a class="nav-item nav-link" href="downloadPage.php">Download</a>
            <a class="nav-item nav-link active" href="verifyPage.php">Verify</a>
            <a class="nav-item nav-link" href="guestPage.php">Guest</a>
            <a class="nav-item nav-link" href="#" data-toggle="modal" data-target="#adminLogin">Admin</a>
            <a class="nav-item nav-link" href="logout.php">Log Out</a>
        </div>
    </div>
</nav>


<!-- Main Content-->
<div class="container">
    <h1 class="brand">VERIFICATION DASHBOARD</h1>
    <div class="wrapper animated bounceInLeft">
        <div class="uploadForm">
        <h3 id="status"></h3>
        <h3> File Verification </h3>
        <div class="" id="result" role="alert"></div>
        <div id="stats"class=""></div>
            <form method="POST" enctype="multipart/form-data">
            <p>
                <label>Select file</label>
                <input name="image" type="file" id="file"/>
            </p>
            <p>
                <label>Identification</label>
                <input type="number" name="id"  id="id" />
            </p>
            <p id="sha1">
                <label>Secure Hashing Algorithm 1</label>
                <span id="sha1value">SHA1</span>
            </p>
            <p id="md5">
                <label>Message Digest 5</label>
                <span id="md5value">MD5</span>
            </p>
            <p class="full">
                <button type="button" onclick="verifyFile()">Verify</button>
                </p>
            </form>
        </div>
    </div>
</div>

    
<!-- Admin Modal Login-->
<div class="modal fade" id="adminLogin" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><strong>ADMINISTRATOR VERIFICATION</strong></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <form autocomplete="off">
                    <input autocomplete="false" name="hidden" type="text" style="display:none;">
                    <div class="form-group row">
                        <label class="col-sm-2 col-form-label">Username</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="username" placeholder="Username" autocomplete="off">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="inputPassword" class="col-sm-2 col-form-label">Password</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="password" placeholder="Password" autocomplete="new-password">
                        </div>
                    </div>
                </form>   
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-primary" id="fetched-delete"  onclick="adminLogin()">Login</button>
            </div>
        </div>
    </div>
</div>    
    
</body>
</html>




<?php 

}
else
{ 
    header('Location: index.php'); 
} 

?>