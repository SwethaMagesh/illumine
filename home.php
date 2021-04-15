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

    <link href="./g-scrolling-carousel/jquery.gScrollingCarousel.css" rel="stylesheet" />
    
    <title>Illumine</title>
    <style>
    .img-thumbnail {
        height: 199px !important;
        width: 133px !important;
    }

    .g-scrolling-carousel .items {
        padding: 5px 0;
    }

    .g-scrolling-carousel .items a {
        display: inline-block;
        /* notice the comments between inline-block items */
        margin-right: 10px;
        width: 70px;
        height: 50px;
        box-shadow: 0 0 5px #000;
        text-align: center;
    }

    figure.figure:hover {
        opacity: 0.5;
        cursor: pointer;
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
        // echo "$userid is userid";
        if(isset($_POST['markAsDone'])){ 
            task('markAsDone',$userid);
        }
        if(isset($_POST['backToWishList'])){ 
            task('backToWishList',$userid);
        }

        if(isset($_POST['startReading']))
        {
            task('startReading',$userid);
        }
        if(isset($_POST['removeFromList'])){
            task('removeFromList',$userid);
        }
        if(isset($_POST['backToReads'])){
            task('backToReads',$userid);
        }
        
        function task($postvar,$userid){ 
                $funName=$postvar;               
                include ('dbconnect.php');  
                if (!$conn) {
                    /*die("Connection failed: " . mysqli_connect_error());*/
                    header('Location:error.php'); //redirect to this page in case of error
                    exit;
                }              
                $bookid = $_POST['bookid'];
                $sql = "call ".$funName."($userid,$bookid);";
                if ($conn->query($sql) === true)
                {
                  echo "<script>console.log(" ;
                  echo "'Success ".$funName."')</script>";            
                }
                else
                {
                    echo "<script>console.log(";
                    echo "'FAILED ".$funName."')</script>";          
                }
                unset($_POST[$postvar]);
                $conn->close();
            
        }
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
                        <a class="nav-link" href="home.php">My bookshelf</a>
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

    <div class="alert alert-primary alert-dismissible " role="alert">
        Dear bookworm, welcome back!!
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <section id="CurrentListSection">
        <?php
include ('dbconnect.php');
$sql = "select bid,title from currentlist where uid=$userid;";
$result = $conn->query($sql);

?>
        <div class="display-6 fw-narrow text-primary text-center mx-auto" style="width:90%">
            Currently Reading <span class="badge bg-primary rounded-pill fs-5 "> <?php echo $result->num_rows ;?>
            </span>
        </div>
        <hr style="width:90%" class="mx-auto">
        <?php
if ($result->num_rows > 0)
{
    ?>
        <div class="g-scrolling-carousel mx-auto" style="width:80%">
            <div class="items">
                <?php
    // output data of each row
    while ($row = $result->fetch_assoc())
    {
        $bid = $row['bid'];
        $title = $row['title'];
         $path="./covers/".$bid.".jpg";
         if(!file_exists($path)){
            $path="./covers/cover.jpg";
         }
        ?>
                <div class="col-sm-2 mt-5 position-relative" style="display:inline-block;width:145px">
                    <a class="w-100 h-100" style="box-shadow:none!important"
                        href="<?php echo "./book.php?reqbid=".$bid ?>">
                        <figure class="figure">
                            <img src="<?php echo "$path" ?>" class="img-thumbnail rounded img-fluid" alt="Error">
                            <figcaption class="figure-caption text-truncate" style="max-width: 133px;">
                                <?php echo $title; ?></figcaption>
                        </figure>
                    </a>
                    <form method="POST">
                        <!-- have to remove book title / bid -->
                        <input style="display:none" type="text" value="<?php echo "$bid"; ?>" name="bookid">

                        <button id="btnGroupDrop3" type="button" class="btn position-absolute"
                            style="top:0px;right:-17px" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black"
                                class="bi bi-three-dots-vertical" viewBox="0 0 10 16">
                                <path
                                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                            </svg>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <button type="submit" name="markAsDone" class="dropdown-item">Mark as Done</button>
                            <button type="submit" name="backToWishList" class="dropdown-item">Back to
                                WishList</button>
                        </div>
                    </form>
                </div>
                <?php
    }?>
            </div>
        </div>
        <?php
}
else
{
    ?>
        <div class="text-center">
            <a class="fs-4 fw-italic text-dark" href="book.php">You are not reading any books. Explore more
                books
                here!</a>
        </div>
        <?php
}
?>
    </section>
    <br />
    <section id="WishlistSection">
        <br />
        <?php
include ('dbconnect.php');
$sql = "select bid,title from wishlist where uid=$userid;";
$result = $conn->query($sql);
?>
        <div class="display-6 fw-narrow text-primary text-center mx-auto" style="width:90%">
            Wishlist <span class="badge bg-primary rounded-pill fs-5"> <?php echo $result->num_rows ;?> </span>
        </div>
        <hr style="width:90%" class="mx-auto">

        <?php
if ($result->num_rows > 0)
{
    ?><div class="g-scrolling-carousel mx-auto" style="width:80%">
            <div class="items">
                <?php
    // output data of each row
    while ($row = $result->fetch_assoc())
    {
        $bid = $row['bid'];
        $title = $row['title'];

         $path="./covers/".$bid.".jpg";
         if(!file_exists($path)){
            $path="./covers/cover.jpg";
         }
        ?>
                <div class="col-sm-2 mt-5 position-relative" style="display:inline-block;width:145px">
                    <a class="w-100 h-100" style="box-shadow:none!important"
                        href="<?php echo "./book.php?reqbid=".$bid ?>">
                        <figure class="figure">
                            <img src="<?php echo "$path" ?>" class="img-thumbnail rounded img-fluid" alt="Error">
                            <figcaption class="figure-caption text-truncate" style="max-width: 133px;">
                                <?php echo $title; ?></figcaption>
                        </figure>
                    </a>
                    <form method="POST">
                        <!-- have to remove book title / bid -->
                        <input style="display:none" type="text" value="<?php echo "$bid"; ?>" name="bookid">

                        <button id="btnGroupDrop3" type="button" class="btn position-absolute"
                            style="top:0px;right:-17px" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black"
                                class="bi bi-three-dots-vertical" viewBox="0 0 10 16">
                                <path
                                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                            </svg>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop2">
                            <button type="submit" name="startReading" class="dropdown-item">Start Reading</button>
                            <button type="submit" name="removeFromList" class="dropdown-item">Remove from List</button>
                        </div>
                    </form>
                </div>

                <?php
    }
?>
            </div>
        </div>
        <?php
}
else
{
    ?>
        <div class="text-center">
            <a class="fs-4 fw-italic text-dark" href="book.php">You have no books in wishlist. Explore and add more
                books here!</a>
        </div>
        <?php
}
?>
    </section>
    <?php
$sql = "select bid,title from completedlist where uid=$userid;";
$result = $conn->query($sql);
?>
    <br />
    <section id="Finished">
        <br />
        <div class="display-6 fw-narrow text-primary text-center mx-auto" style="width:90%">
            Completed List <span class="badge bg-primary rounded-pill fs-5"> <?php echo $result->num_rows ;?> </span>
        </div>
        <hr style="width:90%" class="mx-auto">
        <?php
if ($result->num_rows > 0)
{
    ?>
        <div class="g-scrolling-carousel mx-auto" style="width:80%">
            <div class="items">
                <?php
    // output data of each row
    while ($row = $result->fetch_assoc())
    {
        $bid = $row['bid'];
        $title = $row['title'];
         $path="./covers/".$bid.".jpg";
         if(!file_exists($path)){
            $path="./covers/cover.jpg";
         }
        ?>
                <div class="col-sm-2 mt-5 position-relative" style="display:inline-block;width:145px">
                    <a class="w-100 h-100" style="box-shadow:none!important"
                        href="<?php echo "./book.php?reqbid=".$bid ?>">
                        <figure class="figure">
                            <img src="<?php echo "$path" ?>" class="img-thumbnail rounded img-fluid" alt="Error">
                            <figcaption class="figure-caption text-truncate" style="max-width: 133px;">
                                <?php echo $title; ?></figcaption>
                        </figure>
                    </a>
                    <form method="POST">
                        <!-- have to remove book title / bid -->
                        <input style="display:none" type="text" value="<?php echo "$bid"; ?>" name="bookid">

                        <button id="btnGroupDrop3" type="button" class="btn position-absolute"
                            style="top:0px;right:-17px" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="black"
                                class="bi bi-three-dots-vertical" viewBox="0 0 10 16">
                                <path
                                    d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0zm0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0z" />
                            </svg>
                        </button>

                        <div class="dropdown-menu" aria-labelledby="btnGroupDrop3">
                            <button type="submit" name="backToReads" class="dropdown-item">Reread</button>
                        </div>
                    </form>
                </div>
                <?php
    }
?>
            </div>
        </div>
        <?php
}
else
{
    ?>
        <div class="text-center">
            <a class="fs-4 fw-italic text-dark" href="book.php">You haven't read any books. Explore more books
                here!</a>
        </div>
        <?php
}
?>
        </div>
    </section>
    <br />
    <br />
    <br />
</body>
</html>