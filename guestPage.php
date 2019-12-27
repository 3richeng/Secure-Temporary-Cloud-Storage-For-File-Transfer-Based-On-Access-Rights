<!DOCTYPE html>

<?php
require 'db.php';
session_start();
$username = $_SESSION['username'];

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
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" integrity="sha384-GJzZqFGwb1QTTN6wy59ffF1BuGJpLSa9DkKMp0DgiMDm4iYMj70gZWKYbI706tWS" crossorigin="anonymous">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">
    <link rel="icon" href="css/logo.png">
    
    <title>Guest Session</title>
</head>
<body>
    
    <style>
        body
        {
            background: url(css/guestPage.jpg) no-repeat center center fixed;
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
            
    //Create session from user
    function createSession()
    {
        swal({
        title: "WARNING",
        text: "Do not create a session for untrusted users! Make sure the inserted email is the intended recepient email address!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        })
        .then((confirm) => { //approve
        if (confirm) 
        {
        var firstname = $("#firstname").val();
        var lastname = $("#lastname").val();
        var email = $("#email").val();
        var accesscode = $("#accesscode").val();
        var description = $("#description").val();
        //alert(firstname+lastname+accesscode+description);
            
        
        //Missing field
        if(_("firstname").value == "" || _("lastname").value == "" || _("email").value == "")
        {
            swal("Something went wrong!", "First or last name is missing", "error");   
        }
        else
        {   //Missing access code
            if(_("accesscode").value == "")
            {
                swal("Something went wrong!", "Access Code is missing", "error"); 
            }
            else
            {   //Strong access code validation
                if(!accesscode.match(/[0-9]/g) || !accesscode.match(/[a-z]/g) || !accesscode.match(/[A-Z]/g) || 
                   !accesscode.match(/\W+/) || accesscode.length < 10 )
                {
                    swal("Something went wrong!", "Please create a strong access code with a minimum of TEN characters including a UPPERCASE, LOWERCASE, NUMERIC and SPECIAL character ", "error");
                }
                else
                {
                    
                    //Email validation
                    if(!email.match(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/))
                    {
                        swal("Something went wrong!", "Please insert a valid email only!", "error");
                    }
                    else
                    {   //Insert to database
                        $.ajax
                        ({ 
                        url: 'generateGuest.php',
                        data: 
                        {
                            firstname:firstname, 
                            lastname:lastname,
                            email:email,
                            accesscode:accesscode,
                            description:description
                        },
                        type: 'post',
                        success: function(data) 
                        {
                            var data =JSON.parse(data);
                            console.log(data);
            
                            $('#uuid').html(data[0]);
                            $('#ac').html(data[1]);
                            $('#status').html('<div class="alert alert-success"><strong>SUCCESS: </strong>User session has been created and an email has been send to the recipient</div>');
                            
                           
                        }
                        });
                    }    
                }   
            }
        }
                  
        } 
        else //reject
        {
            swal("Request Canceled", "No Session Is Created.");
              
        }});
    }
    
    //Admin Login
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
            <a class="nav-item nav-link" href="uploadPage.php">Upload</a>
            <a class="nav-item nav-link" href="downloadPage.php">Download</a>
            <a class="nav-item nav-link" href="verifyPage.php">Verify</a>
            <a class="nav-item nav-link active" href="#">Guest</a>
            <a class="nav-item nav-link" href="#" data-toggle="modal" data-target="#adminLogin">Admin</a>
            <a class="nav-item nav-link" href="logout.php">Log Out</a>
        </div>
    </div>
</nav>

    
    
<!-- Main Content -->  
<div class="container">
    <h1 class="brand">GUEST DASHBOARD</h1>
    <div class="wrapper animated bounceInLeft">
      <div class="uploadForm">
          <h3 id="status"></h3>
        <h3> CREATE SESSION <p id="loaded_n_total"></p></h3>
          <div id="stats"class=""></div>
  
        <!--Form input-->
        <form method="POST" enctype="multipart/form-data" autocomplete="off">
            <p>
            <label>First Name</label>
                <input type="text" name="firstname" id="firstname" value=""/>
            </p>
            <p>
            <label>Last Name</label>
                <input  name="lastname" id="lastname" value=""/>
            </p>
            <p>
            <label>Recipient Email</label>
            <input type="text" class="text-input" name="email" id="email"/>
            </p>
          
          <p>
              

            <label>Access Code</label>
            <input type="password" name="accesscode" id="accesscode" maxlength="25" size="25" autocomplete="new-password">
          
          </p>
          <p class="full">
            <label>Description</label>
            <textarea name="description" id="description" rows="2" placeholder="" maxlength="255" size="255"></textarea>
          </p>
            <p>
            <label> Universally Unique Identification</label>
            <span id="uuid">null</span>
            </p>
            <p>
            <label>Access Code</label>
                <span id="ac">null</span>
            </p>
          <p class="full">
              <button type="button" name="submit" onclick="createSession()" id="upload">CREATE SESSION</button>
          </p>
        
        </form>
          <br>
        <div>
            <!--Get list of created session by user-->
            <table class="table">
            <thead>
                <tr>
                    <th>UUID</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>E-mail address</th>
                    
                </tr>    
            </thead>
            <tbody>
            <?php
        
                $sql = "SELECT * FROM guest WHERE sessionCreator = '$username' ORDER BY time ASC LIMIT 20;";
                $guest = mysqli_query($db_conn,$sql);
        
                while($row = mysqli_fetch_assoc($guest))
                {
                    $uuid = $row['uuid'];
                    $firstname = $row['first_name'];
                    $lastname = $row['last_name'];
                    $email = $row['email'];
            
                    echo "<tr>";
                    echo "<td>".$uuid."</td>";
                    echo "<td>".$firstname."</td>";
                    echo "<td>".$lastname."</td>";
                    echo "<td>".$email."</td>";
                    echo "</tr>";
                }
            ?>    
            </tbody>
            </table>
        </div>    
      </div>
    </div>
</div>
    

<!-- Main Content -->
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