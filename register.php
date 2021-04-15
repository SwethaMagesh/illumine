<html>

<head>
    <meta charset="utf-8">
    <title>Register - Illumine</title>
    <!-- <link rel="stylesheet" href="home.css"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <script src="register.js" type="text/javascript"></script>
</head>
<?php
include("dbconnect.php");
if(isset($_POST['Submit']))
{
    $uname=trim($_POST['username']);
    $email=trim($_POST['email']);
    $password=trim($_POST['password']);
   
        $sql="insert into user (name,pwd,email) values ('$uname','$password','$email') ";
        if($conn->query($sql)===true){
            
        $sql2="select uid from user where email='".$email."'";
        $res=$conn->query($sql2);
        if($res->num_rows>0){
            session_start();
            //  $res->fetch_assoc()['uid'];
            $_SESSION['userid'] = $res->fetch_assoc()['uid'];
            $_SESSION['name'] = $uname;       
            $_SESSION['email'] = $email;  
            $_SESSION['logged_in'] = true;
            header('location:thankyou.php');
        }
       
?>
<script>
console.log('<?php echo "DONE!!"; ?>')
</script>
<div class="alert alert-warning alert-dismissible fade show" role="alert"> Thank you for registering!! Login
    <a href="login.php">HERE</a>
</div>
<?php
         }
         else{
?>
<script>
console.log('<?php echo "FAILED update";?>')
</script>
<div class="alert alert-warning alert-dismissible fade show" role="alert"> Email has already been taken! Please check
    again!!! </div>
<?php
         }         
         $conn->close();
     }
?>
<body>
    <div class="main-container">
        <div class="text">
            <h3>Start tracking your reads!</h3>
            <h6>Register yourself as an Illumine member now!</h6>
        </div>
        <form class='needs-validation' method="post" novalidate>
            <div id="firststep">
                <div class="header">Personal Details</div>
                <hr width="100%" style="margin:7px 0px;border:none;border-bottom:0.5px solid">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                        required>
                    <div class="invalid-feedback"> Please choose a username. </div>
                </div>
                <div class="form-group">
                    <label for="email">Email address</label>
                    <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp"
                        placeholder="Email" required>
                    <div class="invalid-feedback"> Please enter a valid email id. </div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-icons">
                        <input type="password" class="form-control" name="password" id="password" placeholder="Password"
                            required> <i id="pass-status" class="fa fa-eye" aria-hidden="true"
                            onClick="viewPassword()"></i>
                    </div>
                </div>
                <div class="footer">
                    <button type="submit" name="Submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
    <div class="main-container" id="redirect"> Already Registered? <a href="login.php?type=any">Login here</a> </div>
    <script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    </script>
    <script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function() {
        'use strict';
        window.addEventListener('load', function() {
            // Fetch all the forms we want to apply custom Bootstrap validation styles to
            var forms = document.getElementsByClassName('needs-validation');
            // Loop over them and prevent submission
            var validation = Array.prototype.filter.call(forms, function(form) {
                form.addEventListener('submit', function(event) {
                    if (form.checkValidity() === false) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }, false);
    })();
    </script>
</body>

</html>