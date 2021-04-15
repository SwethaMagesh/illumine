<html>

<head>
    <meta charset="utf-8">
    <title>LOGIN - Illumine</title>
    <!-- <link rel="stylesheet" href="home.css"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="logo.png">
    <link rel="stylesheet" href="register.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
        integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    <script src="register.js" type="text/javascript"> </script>
</head>

<body>
    <?php
include('dbconnect.php');
session_start();
if(isset($_REQUEST['type'])){
    $type=$_REQUEST["type"];
}

else{
    $type='any';
}

if(isset($_POST['login']))
{
    $email=trim($_POST['email']);
    $password=trim($_POST['password']);
    $sql="select uid, name, email, pwd from user where email='$email' and pwd='$password'";
    $result=$conn->query($sql);
    // echo "$result";
    if($result && $result->num_rows===1){
        // session 
        $row = $result->fetch_assoc();
        $_SESSION['userid'] = $row['uid'];
        $_SESSION['name'] = $row['name'];       
        $_SESSION['email'] = $email;  
        $_SESSION['logged_in'] = true;
        header('location:home.php');
    }
    else{
        session_abort();
        header('location:login.php?type=redirect');
    }
}

?>
    <div class="main-container">
        <div class="text">
            <h3>Welcome back !</h3>
            <h6>Books are waiting!</h6>
        </div>
        <form class='needs-validation' method="post" novalidate>
            <div id="firststep">
                <div class="header">Login</div>
                <hr width="100%" style="margin:7px 0px;border:none;border-bottom:0.5px solid ">
                <?php if($type==="redirect") { ?>

                <span style="color:red;margin-bottom:10px">Username, password or user type is incorrect! Try
                    again!</span>

                <?php } ?>
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
                    <button type="submit" name="login" class="btn btn-primary">Login</button>
                </div>
            </div>
        </form>
    </div>
    <div class="main-container" id="redirect"> Want to register? <a href="register.php">Create a new account</a> </div>
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