<!DOCTYPE html>
<html>
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script src="js/terms.js"></script>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR" rel="stylesheet">
    <link rel="stylesheet" href="css/index.css">
    <link rel="icon" href="css/logo.png">


    <title>Guest Login</title>
</head>
<body>
<div id="container">
      <div id="left">
        <div id="signin">
            
          <div class="logo">
            <h1>GUEST LOGIN</h1>
            
          </div>
          <form>
              
            <div>
                <div class="" role="alert" id="error"></div>
              <label>Unique Identification</label>
              <input type="text" class="text-input" name="uuid" id="uuid"/>
            </div>
            <div>
              <label>Access Code</label>
              <input type="password" class="text-input" name="accesscode" id="accesscode" autocomplete="new-password"/>
            </div>
            <button type="button"  class="login-btn" onclick="guest()">Sign In</button>
          </form>

          <div class="or">
            <hr class="bar" />
            <span>OR</span>
            <hr class="bar" />
          </div>
        
            <br>
            <a href="index.php" class="register-btn" id="btn">Return</a>
        </div>
        <footer id="footer">
          <p>Copyright &copy; 2019, Multimedia University All Rights Reserved</p>
          <div>
            <a onclick="terms()">Terms and condition</a> | <a onclick="privacy()">Privacy Policy</a>
              
          </div>
        </footer>
      </div>
      <div id="right" >
        <div id="showcase" style="background: url('css/guest.jpg') no-repeat center center / cover;">
          <div class="showcase-content">
            <h2 class="quote" id="quote"></h2>
          </div>
        </div>
      </div>
</div>



<script>

//Guest login
function guest(){
    
    
    var uuid = document.getElementById("uuid").value;
    var accesscode = document.getElementById("accesscode").value;
    
        $.ajax
        ({ 
            url: 'guestLogin.php',
            data: {uuid:uuid, accesscode:accesscode},
            type: 'post',
            success: function(data) 
            {
                
                
                if(data == "true"){
                    
                    location.href = "guestUpload.php?login=successful";
                }
                
                else if(data == "error_1"){
                    swal ( "Oops" ,  "Missing Field" ,  "error" );
                    document.getElementById("quote").innerHTML = "<strong>ERROR DETECTED</strong></br>Missing Field Detected!";
                }
                else if(data == "error_2"){
                    swal ( "Oops" ,  "UUID Doesnt Exist" ,  "error" );
                    document.getElementById("quote").innerHTML = "<strong>ERROR DETECTED</strong></br>UUID Doesnt Exist!";
                }
                else if(data == "error_3"){
                    swal ( "Oops" ,  "Invalid Access Code" ,  "error" );
                    document.getElementById("quote").innerHTML = "<strong>ERROR DETECTED</strong></br>Invalid Access Code!";
                }
                else if(data == "error_4"){
                    swal ( "Oops" ,  "Session Expired" ,  "error" );
                    document.getElementById("quote").innerHTML = "Session Expired!";
                }
                else if(data == "error_5"){
                    swal ( "Oops" ,  "Something Went Wrong" ,  "error" );
                    document.getElementById("quote").innerHTML = "<strong>ERROR DETECTED</strong></br>Something Went Wrong!";
                }
                else{
                   swal ( "Oops" ,  "Something Went Wrong" ,  "error" );
                
                }
                
                
            }
        }); 
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