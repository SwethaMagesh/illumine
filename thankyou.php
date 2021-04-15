<?php
    //this is the session check for this page
    include('session_check.php');
    include("dbconnect.php");
?>
<!DOCTYPE html>
<html>

<head>
    <title>Thank you! - Illumine</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta charset="UTF-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous">
    </script>
</head>

<body>
    <div class="display-6 mt-3 fw-narrow text-center text-primary mx-auto" style="width:90%;height:100%">
        Welcome
    </div>
    <hr style="width:90%" class="mx-auto">
    <div class="mx-auto p-5 fs-2 border border-2 rounded text-center" style="width: 70%;">
        <p>Thanks for registering with Illumine!! You can track your reads, wishlists and
            review books here. Hurray bookworms, you are almost there!!
        </p>
        <br />
        <a class="btn btn-primary mt-3 p-4 fs-4 mx-auto" style="text-decoration: none;" href="book.php">Start Exploring
            Books!!
        </a>
    </div>
</body>

</html>