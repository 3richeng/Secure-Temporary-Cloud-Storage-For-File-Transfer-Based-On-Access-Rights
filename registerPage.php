<!DOCTYPE html>
<html>
<head>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="js/terms.js"></script>
    
    <link href="https://fonts.googleapis.com/css?family=Noto+Sans+KR" rel="stylesheet">
    <link rel="stylesheet" href="css/registerPage.css">
    <link rel="icon" href="css/logo.png">
    
    <title>Login</title>
    
</head>
<body>
    
<!-- Main Content -->
<div id="container">
      <div id="left">
        <div id="signup">
            
          <div class="logo">
            <h1>REGISTERATION</h1>
            
          </div>
          <form>
              
            <div>
                <div class="" role="alert" id="error"></div>
              <label>Username</label>
              <input type="text" class="text-input" name="username" id="username"/>
            </div>
              <div>
                <div class="" role="alert" id="error"></div>
              <label>Email Address</label>
              <input type="text" class="text-input" name="email" id="email"/>
            </div>
              <div>
                <div class="" role="alert" id="error"></div>
              <label>First Name</label>
              <input type="text" class="text-input" name="firstname" id="firstname"/>
            </div>
              <div>
                <div class="" role="alert" id="error"></div>
              <label>Last Name</label>
              <input type="text" class="text-input" name="lastname" id="lastname"/>
            </div>
            <div>
              <label>Password</label>
              <input type="password" class="text-input" name="password" id="password" autocomplete="new-password"/>
            </div>
            <button type="button"  class="register-btn" onclick="register()">Sign Up</button>
          </form>
          <div class="or">
            <hr class="bar" />
            <span>OR</span>
            <hr class="bar" />
          </div>
          <a href="index.php" class="return-btn" id="btn">Return to Login</a>
        </div>
        <footer id="footer">
          <p>Copyright &copy; 2019, Multimedia University All Rights Reserved</p>
          <div>
            <a onclick="terms()">Terms and condition</a> | <a onclick="privacy()">Privacy Policy</a>
              
          </div>
        </footer>
      </div>
      <div id="right">
        <div id="showcase">
          <div class="showcase-content">
            <h2 class="quote" id="quote"></h2>
          </div>
        </div>
      </div>
</div>


<script>

//Register Function
function register(){
    var username = document.getElementById("username").value;
    var email = document.getElementById("email").value;
    var firstname = document.getElementById("firstname").value;
    var lastname = document.getElementById("lastname").value;
    var password = document.getElementById("password").value;
    
        $.ajax
        ({ 
            url: 'register.php',
            data: {username:username, 
                   email:email,
                   firstname:firstname,
                   lastname:lastname,
                   password:password},
            type: 'post',
            success: function(data) 
            {
            
                if(data == 1){
                    swal ( "Oops" ,  "Missing field detected!" ,  "error" );
                    document.getElementById("quote").innerHTML = "Missing field detected.";
                }
                else if(data == 2){
                    swal ( "Oops" ,  "Invalid characters for first and last name!" ,  "error" );
                    document.getElementById("quote").innerHTML = "Invalid characters for first and last name.";
                }
                else if(data == 3){
                    swal ( "Oops" ,  "Invalid email!" ,  "error" );
                    document.getElementById("quote").innerHTML = "Invalid email.";
                }
                else if(data == 4){
                    swal ( "Oops" ,  "The username or email is taken. Try another" ,  "error" );
                    document.getElementById("quote").innerHTML = "The username or email is taken. Try another";
                }
                else if(data == 5){
                    swal ( "Oops" ,  "Please use a strong password! The Password must at least contain 10 letters including at least a numeric character, a special character, a uppercase and a lowercase letter. " ,  "error" );
                    document.getElementById("quote").innerHTML = "Please use a strong password.";
                }
                else{
                    swal ( "Success" ,  "Your account has been submitted to the administrator for confirmation. Once approved, you are welcomed to login to the web page!" ,  "success" );
                    document.getElementById("quote").innerHTML = "Your account has been submitted to the admin for confirmation.";
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
                      "&ldquo;Powered By AWS&rdquo;",];
    
    var quoteGen = Math.floor(Math.random() * 7) + 0;        
    document.getElementById("quote").innerHTML = quotes[quoteGen];    

    function privacy(){
    swal("Privacy Policy", "", "warning");
    }



        
    
</script>
    
</body>
    






</html>