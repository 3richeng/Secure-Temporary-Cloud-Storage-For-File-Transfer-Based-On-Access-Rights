<!DOCTYPE html>
<html>
<head>

    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR" rel="stylesheet">
    <script src="js/terms.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="css/index.css">
    <link rel="icon" href="css/logo.png">
    
    <title>Login</title>
</head>
<body>
        
<!-- Main Content -->  
<div id="container">
      <div id="left">
        <div id="signin">
            
          <div class="logo">
            <h1>THE FILE SOLUTION</h1>
            
          </div>
          <form>
              
            <div>
                <div class="" role="alert" id="error"></div>
              <label>Username</label>
              <input type="text" class="text-input" name="username" id="username"/>
            </div>
            <div>
              <label>Password</label>
              <input type="password" class="text-input" name="password" id="password" autocomplete="new-password"/>
            </div>
            <button type="button"  class="login-btn" onclick="login()">Sign In</button>
          </form>
          <div class="links">
            <a href="#" data-toggle="modal" data-target="#contact">Contact Adminstator</a>
          </div>
          <div class="or">
            <hr class="bar" />
            <span>OR</span>
            <hr class="bar" />
          </div>
        
          <a href="registerPage.php" class="register-btn" id="btn">Create an account</a>
            <br>
            <a href="guestindex.php" class="register-btn" id="btn">Guest</a>
        </div>
        <footer id="footer">
          <p>Copyright &copy; 2019, Multimedia University All Rights Reserved</p>
          <div>
            <a onclick="terms()">Terms and condition</a> | <a onclick="privacy()">Privacy Policy</a>
              
          </div>
        </footer>
      </div>
      <div id="right">
        <div id="showcase" style="background: url('css/index.jpg') no-repeat center center / cover;">
          <div class="showcase-content">
            <h2 class="quote" id="quote"></h2>
          </div>
        </div>
      </div>
</div>
    
<!-- Contact Admin Form -->
<div class="modal fade" id="contact" role="dialog" >
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div id="status"></div>
      <div class="modal-header">
          
        <h5 class="modal-title" id="exampleModalLongTitle">Contact Adminstrator</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
            <label>Subject</label>
            <input type="text" class="form-control" id="subject">
        </div>
        <div class="form-group">
            <label>Your Email</label>
            <input type="text" class="form-control" id="email">
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea class="form-control" id="description" rows="3" placeholder="Try to elaborate on your issues you are currently facing"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-outline-primary" onclick="contactAdmin()">Submit</button>
        <button type="button" class="btn btn-outline-danger" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>



<script>
    
function _(el)
{
    return document.getElementById(el);
}    

function login()
{
    var username = document.getElementById("username").value;
    var password = document.getElementById("password").value;
    
        $.ajax
        ({ 
            url: 'login.php',
            data: {username:username, password:password},
            type: 'post',
            success: function(data) 
            {
                
                
                if(data == "proceed")
                {location.href = "home.php";}
                else if (data == 'error_1')
                { _("quote").innerHTML = "<strong>ERROR DETECTED</strong></br> Missing Field Detected.";}
                else if (data == 'error_2')
                { _("quote").innerHTML = "<strong>ERROR DETECTED</strong></br> The username is not associated with any account";}
                else if (data == 'error_3')
                { _("quote").innerHTML = "<strong>ERROR DETECTED</strong></br> The password that you have entered is incorrect. Please try again";}
                else if (data == 'error_4')
                { _("quote").innerHTML = "Your account yet to be approved by the adminstration. Please contact the admin for any inquires.";}
                else
                { _("quote").innerHTML = "<strong>ERROR DETECTED</strong></br> An error has occur. Please try again.";}
                
            
                
            }
        }); 
}
    
    
function contactAdmin()
{
    var subject = _("subject").value;
    var email = _("email").value;
    var description = _("description").value;
    
    if(subject == "" || email == "" || description == ""){
        swal("Something went wrong!", "Missing Field Detected!", "error");
    }
    else
    {   //Email validation
        if(!email.match(/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/))
        {
            swal("Something went wrong!", "Please insert a valid email only!", "error");
        }
        else
        {
            $.ajax
            ({ 
                url: 'contactAdmin.php',
                data: {subject:subject, email:email, description:description},
                type: 'post',
                success: function(data) 
                {
                
                    if(data = "true"){
                        _("status").innerHTML = "<div class='alert alert-success'>SUCCESS: This request has been successfully delivered to the administrator</div>" 
                    }
                    else{
                        _("status").innerHTML = "<div class='alert alert-danger'>ERROR: The request has failed. Please try again.</div>" 
                    }
                
            
                
            }
        }); 
        }
        
        

        
    }
    
    
    
    
   
}
    
    var quotes = ["&ldquo;Advance Encryption Standard&rdquo;",
                  "&ldquo;256-bits Encryption&rdquo;",
                  "&ldquo;Amazon Web Server&rdquo;",
                  "&ldquo;Secure&rdquo;",
                  "&ldquo;Temporary Cloud Storage&rdquo;",
                  "&ldquo;AES 256-Bits&rdquo;",
                  "&ldquo;Powered By AWS&rdquo;",
                  "&ldquo;Secure Socket Layer&rdquo;",
                  "&ldquo;Transport Layer Security&rdquo;",
                  "&ldquo;SSL / TLS&rdquo;",
                  "&ldquo;Secure Hypertext Transfer Protocol&rdquo;"];
    
    var quoteGen = Math.floor(Math.random() * 11) + 0;        
    document.getElementById("quote").innerHTML = quotes[quoteGen];
    
    function privacy(){
    swal("Privacy Policy", "", "warning");
    }
        
</script> 



    
</body>
    






</html>