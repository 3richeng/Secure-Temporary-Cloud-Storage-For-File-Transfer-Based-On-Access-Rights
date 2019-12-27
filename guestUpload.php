<!DOCTYPE html>

<?php
    require 'db.php';
    session_start();
if(isset($_SESSION['username']))
{
    if($_SERVER['REQUEST_TIME'] - $_SESSION['time'] > 3600) //Timeout out after 1 hour
    {
        header('Location: guestindex.php');
    }
    else{
?>


<html lang="en">
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
 
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.css" />
    <link rel="stylesheet" href="css/main.css">
    <link rel="icon" href="css/logo.png">
    
    
    <title>Guest Upload</title>
</head>
<body>


    <style>
        body
        {
            background: url(css/apple-devices-beads-black-coffee-265020.jpg) no-repeat center center fixed;
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
                
                var level = $("#level").val();
                var publicity = $("#publicity").val();
                var filePassword = $("#filePassword").val();
                var description = $("#description").val();
                var guest = "true";
                
                if(fileLength > 0){
                    for(var i = 0; i < fileLength; i++){
                        fileSize = fileSize+file[i].size;
                    }
                }
                

                    if(fileLength > 0) //No. of files
                    {
                        if(fileSize < 1000000000)
                        {
                    
                            var formdata = new FormData();
                    
                            for(var x=0; x < fileLength; x++){ formdata.append("file[]", _("file").files[x]); }
                            formdata.append("name[]", name);
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
                _("status").innerHTML = "Upload Failed";
                
                document.getElementById("stats").className = "alert alert-danger";
                
                var element = document.getElementById("stats");
                element.innerHTML = "<strong>ERROR: </strong>An Error has occured while uploading the file.";
                
                
            }
            
            function abortHandler(event)
            {
                document.getElementById("status").className = "alert alert-danger";
                
                var element = document.getElementById("status");
                element.innerHTML = "<strong>ABORT: </strong>The process has been aborted.";
                
            }

    </script>    
    
<!-- Main Content -->   
<div class="container">
    <h1 class="brand">GUEST DASHBOARD</h1>
    <div class="wrapper animated bounceInLeft">
      <div class="uploadForm">
          <h3 id="status"></h3>
        <h3> FILE ATTRIBUTES <p id="loaded_n_total"></p></h3>
          <div id="stats"class=""></div>
  
        
        <form method="POST" enctype="multipart/form-data">
            
          
            <input type="hidden" name="name[]" value="<?php echo $_SESSION['username']; ?>">
          <p class="full">
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
    
</body>
</html>

<?php 

    
        
        
        
    }
    
}
else
{ 
    header('Location: index.php'); 
} 

?>