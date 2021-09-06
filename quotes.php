<!doctype html>

<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <title>Illumine</title>
    <style>
    .fw-bold {
        font-weight: 500 !important;
    }

    .nav-item {
        font-size: 1.5rem;
        font-weight: 300;
    }
    </style>
</head>

<body style="width:100%;overflow-x:hidden">
    <?php
        include('session_check.php');
        $userid=$_SESSION['userid'];
        include('dbconnect.php');
        // echo "$userid is userid";
        ?>
    <nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand text-primary" style="font-style:italic;font-size:1.5rem" href="#">ILLUMINE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ">
                    <li class="nav-item px-4">
                        <a class="nav-link" href="index.php">My bookshelf</a>
                    </li>
                    <li class="nav-item px-4">
                        <a class="nav-link" href="book.php">Explore Books </a>
                    </li>
                    <li class="nav-item px-4">
                        <a class="nav-link" href="review.php">Reviews</a>
                    </li>
                    <li class="nav-item px-4">
                        <a class="nav-link" href="quotes.php">Quotes</a>
                    </li>
                    <li class="nav-item px-4 ">
                        <a class="nav-link" href="logout.php">Logout!</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <section id="Quotes">
        <br />
        <br />
        <?php
$sql = "select Quote,title from user_bookquotes natural join books where uid=$userid ;";
$result = $conn->query($sql);
?>
        <div class="quotes">
            <div class="display-6 fw-narrow text-center text-primary mx-auto" style="width:90%">
                Favourite Quotes
            </div>
            <hr style="width:90%" class="mx-auto">
            <?php
if ($result->num_rows > 0)
{
    // output data of each row
    while ($row = $result->fetch_assoc())
    {
        $quote = $row['Quote'];
        $title = $row['title'];
?>
            <div class="card fs-5 p-3 mt-3 mx-auto" style="width:80%">
                <blockquote class="blockquote mb-0">
                    <p><?php echo "$quote"; ?></p>
                    <footer class="blockquote-footer text-end fw-bold text-primary"><cite
                            title="Source Title"><?php echo "$title"; ?></cite>
                    </footer>
                </blockquote>
            </div>
            <?php
    }
}
else
{
    ?>
            <div class="text-center">
                <a class="fs-4 fw-italic text-dark" href="book.php">You haven't added any quotes. Explore here!</a>
            </div>
            <?php
}
?>

        </div>
    </section>
    <br />
    <br />
</body>
</html>