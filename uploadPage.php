<!DOCTYPE html>

<?php
require 'db.php';
session_start();

if(isset($_SESSION['username']))
{
        
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
  
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">
    <link rel="icon" href="css/logo.png">

    <title>Upload</title>
</head>
<body>
    

    
    <style>
        body
        {
            background: url(css/homeUpload.jpg) no-repeat center center fixed;
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
        
            
            function uploadFile()
            {
                var getFile = $("#file");
                var fileLength = _("file").files.length;
                var file = getFile[0].files;
                var fileSize = 0;
                
                var name = $("#name").val();
                var level = $("#level").val();
                var publicity = $("#publicity").val();
                var filePassword = $("#filePassword").val();
                var description = $("#description").val();
                var guest = "false";
                
                //console.log(file);
                
                //Get total file size
                if(fileLength > 0){
                    for(var i = 0; i < fileLength; i++){
                        fileSize = fileSize+file[i].size
                    }
                }
                
                if(name.length > 0) //get amount of access
                {
                    if(fileLength > 0) //number of files
                    {
                        if(fileSize < 1000000000)//file size validation
                        {
                    
                            var formdata = new FormData();
                    
                            //append data to PHP
                            for(var x=0; x < fileLength; x++){ formdata.append("file[]", _("file").files[x]); }
                            for (var i = 0; i < name.length; i++) { formdata.append("name[]", name[i]);}
                            formdata.append("level", level);
                            formdata.append("filePassword", filePassword);
                            formdata.append("description", description);
                            formdata.append("publicity", publicity);
                            formdata.append("guest", guest);
                    
                            var ajax = new XMLHttpRequest();
                            ajax.upload.addEventListener("progress", progressHandler, false);
                            ajax.addEventListener("load", completeHandler, false);
                            ajax.addEventListener("error", errorHandler, false);
                            ajax.addEventListener("abort", abortHandler, false);
                            ajax.open("POST", "upload.php");
                            ajax.send(formdata);
                        }
                        else
                        {
                            event.preventDefault();
                            swal("ERROR DETECTED!", "The file is too large!", "error");   
                        }
                    }
                    else
                    {
                        event.preventDefault();
                        swal("ERROR DETECTED!", "Please select with a minimum of one file!", "error"); 
                    }    
                }
                else
                {
                    event.preventDefault();
                    swal("ERROR DETECTED!", "Please select with a minumun of one person!", "error"); 
                }
                
                
                
            //JS progress bar eventHandler
            }
            
            function progressHandler(event)
            {   
                var percent = (event.loaded / event.total) * 100;
                
                _("status").innerHTML = "<div class='alert alert-info'><strong>STATUS: </strong>"+Math.round(percent)+" %Uploading "+event.loaded+ " of " + event.total +" Bytes</div>" 
            }
            
            function completeHandler(event)
            {
                _("status").innerHTML = event.target.responseText;
 
                
            }
            
            function errorHandler(event)
            {
               
                document.getElementById("status").className = "alert alert-danger";
                
                var element = document.getElementById("status");
                element.innerHTML = "<strong>ERROR: </strong>An Error has occured while uploading the file.";
                
                
            }
            
            function abortHandler(event)
            {
                document.getElementById("status").className = "alert alert-danger";
                
                var element = document.getElementById("status");
                element.innerHTML = "<strong>ABORT: </strong>The process has been aborted.";
                
            }
        
        //admin login
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
    
    
  
<!-- Nav Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">THE FILE SOLUTION</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav">
            <span class="sr-only">(current)</span>
            <a class="nav-item nav-link active" href="uploadPage.php">Upload</a>
            <a class="nav-item nav-link" href="downloadPage.php">Download</a>
            <a class="nav-item nav-link" href="verifyPage.php">Verify</a>
            <a class="nav-item nav-link" href="guestPage.php">Guest</a>
            <a class="nav-item nav-link" href="#" data-toggle="modal" data-target="#adminLogin">Admin</a>
            <a class="nav-item nav-link" href="logout.php">Log Out</a>
        </div>
    </div>
</nav>
    
    
<!-- Main Content -->
<div class="container">
    <h1 class="brand">UPLOAD DASHBOARD</h1>
    <div class="wrapper animated bounceInLeft">

      <div class="uploadForm">
          <h3 id="status"></h3>
        <h3> FILE ATTRIBUTES <p id="loaded_n_total"></p></h3>
          <div id="stats"class=""></div>
  
        
        <form method="POST" enctype="multipart/form-data">
            
          <p>
            <label>Humans (Ctrl + Select)</label>
            <?php
            $query = "SELECT * FROM accounts WHERE status='Active'";
            $result = mysqli_query($db_conn,$query);
            ?>  
              
            <select name="name[]" id="name" multiple="multiple">
                <?php while($row= mysqli_fetch_array($result)):;?>
                <option value="<?php echo $row[1];?>"><?php echo $row[1];?></option>
                <?php endwhile;?>
            </select>
          </p>
            
          <p>
            <label>Array of Files</label>
            <input type="file" name="file[]" id="file" multiple="multiple">
          </p>
            
          <p id="selective">
              
            <label>Sensitivity Level & Publicity</label>
              
            <select name="level" id="level">
                <option value="Reserved">Unclassified</option>
                <option value="Copyright" style="color:blue">Normal</option>
                <option value="Moderately sensitive" style="color:#418bf4">Moderately sensitive</option>
                <option value="Highly sensitive" style="color:#f44171">Highly sensitive</option>
                <option value="Ultra-sensitive" style="color:#f44242">Ultra-sensitive</option>
                
            </select>
              
              <select name="publicity" id="publicity">
                <option value="Public">Public</option>
                <option value="Private">Private</option>
            </select>
          </p>
            
          <p>
            <label>Password (optional)</label>
            <input type="password" name="filePassword" id="filePassword" placeholder="******" maxlength="20" size="20">
          </p>
            
          <p class="full">
            <label>Description</label>
            <textarea name="description" id="description" rows="3" placeholder="" maxlength="255" size="255"></textarea>
          </p>
            
          <p class="full">
            
              <button type="button" name="submit" onclick="uploadFile()" id="upload">Encrypt & Upload</button>
          </p>
            
        </form>
      </div>
    </div>
</div>
    
<!-- Admin Login-->
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