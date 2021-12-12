<?php
if(isset($_POST['search'])){
    if(isset($_POST['bname']))
    {
        // echo "<script>console.log('".$_POST['bname']."')</script>";
        include('dbconnect.php');
        $sql="SELECT bid from books where title='".addslashes($_POST['bname'])."'";
        $bidres=$conn->query($sql);
        if($bidres->num_rows>0){
            $nextRequest=$bidres->fetch_assoc()['bid'];
            // echo "<script>console.log('".$nextRequest."')</script>";
            $url='Location:./book.php?reqbid='.$nextRequest;  
            unset($_POST['search']);
            header($url);        
        }       
    }    
    else{
        echo "Didn't search yet!";
    }
    unset($_POST['search']);
}
?>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous">
    </script>
    <title>Illumine</title>
    <style>
    .img-thumbnail {
        height: 199px !important;
        width: 133px !important;
    }

    .nav-item {
        font-size: 1.5rem;
        font-weight: 300;
    }
    .modal label{
        font-weight: 500 !important;
    }
    </style>
</head>

<body style="width:100%;overflow-x:hidden"><?php

include ('session_check.php');
$userid = $_SESSION['userid'];
if (isset($_REQUEST['reqbid']))
{
    $reqbid = $_REQUEST["reqbid"];
}
else
{
    $reqbid = 'any';

}

echo "<script>console.log('reqbid= $reqbid')</script>";
//try to var_dump($_POST);

if (isset($_POST['markAsDone']))
{
    echo "<script>console.log('mark as done')</script>";
    task('markAsDone', $userid);
}
if (isset($_POST['addToRList']))
{
    echo "<script>console.log('ADD to wish list')</script>";
    task('addToRList', $userid);
}
if (isset($_POST['startReading']))
{
    echo "<script>console.log('StartReading')</script>";
    task('startReading', $userid);
}
if (isset($_POST['removeFromList'])) {
    task('removeFromList', $userid);
}
if (isset($_POST['backToReads'])) {
    task('backToReads', $userid);
}

function task($postvar, $userid)
{
    echo "<script>console.log('".$postvar."')</script>";
    $funName = $postvar;
    include ('dbconnect.php');
    if (!$conn)
    {        
        header('Location:error.php');
        exit;
    }
    $bookid = $_POST['bookid'];
    $sql = "call " . $funName . "($userid,$bookid);";
    if ($conn->query($sql) === true)
    {
        echo "<script>console.log('Success $funName')</script>";
    }
    else
    {
        echo "<script>console.log('";
        echo "FAILED " . $funName . "')</script>";
    }
    unset($_POST[$postvar]);
    $conn->close();
}
include ('dbconnect.php');
if (!$conn)
{
    /*die("Connection failed: " . mysqli_connect_error());*/
    header('Location:error.php'); //redirect to this page in case of error
    exit;
}
if ($reqbid != 'any')
{
    
    $sql = "SELECT b.bid as bid,title,year,genre,name as author,status,onelinereview as review FROM books b natural join author left outer join  userbookshelf u  on b.bid=u.bid and uid=$userid   WHERE b.bid=$reqbid ;";
    
    //resultant array getting stored in this variable
    if($result = $conn->query($sql))
    $specificrow= $result->fetch_assoc();
    $conn->close();
}
if (isset($_POST['add_book']))
{
    // echo "Adding book to DB";
    include ('dbconnect.php');
    $title = addslashes(trim($_POST['title']));
    $genre = trim($_POST['genre']);
    $year = $_POST['year'];
    $author = addslashes(trim($_POST['author']));
    $sql = "call insertBookWithAuthor('$title','$genre',$year,'$author',@nextbid)";
    // echo "$sql";

    $sql2 = "Select @nextbid as bid;";
    // echo "$sql2";
    $res = $conn->query($sql);
    if ($res === true)
    {
        echo "<script> console.log('INSERTED successfully')</script>";
        $result = $conn->query($sql2);
        if ($result->num_rows > 0)
        {
            $file_name = $result->fetch_object()->bid;
        }
        unset($_POST['add_book']);
        // unset($_FILES['image']);
        $conn->close();
        echo "<script>console.log('DONE');alert('Successfully inserted')</script>";
        if (isset($_FILES['image']))
        {
            $errors = array();
            // $file_name = $_FILES['image']['name']; // has to be the inserted bid
            $file_size = $_FILES['image']['size'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_type = $_FILES['image']['type'];
            $explode = (explode('.', $_FILES['image']['name']));
            $file_ext = strtolower(end($explode));
            $extensions = array(
                "jpeg",
                "jpg",
                "png"
            );
            if (in_array($file_ext, $extensions) === false)
            {
                $errors[] = "Extension not allowed, please choose a JPEG or PNG file.";
                echo "<script>alert('Check the extension of the file'</script>";
            }
            if ($file_size > 2097152)
            {
                $errors[] = 'Upload a smaller pic';
            }
            if (empty($errors) == true)
            {
                move_uploaded_file($file_tmp, "./covers/" . $file_name . '.jpg');
                echo "<script>console.log('loaded image')</script>";
            }
            else
            {
                echo " <script> console.log('Failed upload!!')</script>";
            }
        }
    }
    else
    {
        unset($_POST['add_book']);
        $conn->close();
        echo " <script> console.log('Failed insert!!')</script>";
    }
}
//    adding review
if (isset($_POST['add_review']))
{
    // echo "Adding review to DB";
    include ('dbconnect.php');
    $bookid = $_POST['bookid'];
    $rating = $_POST['rating'];
    $oneline = addslashes(trim($_POST['onelinereview']));
    $suggest = addslashes(trim($_POST['suggestedby']));
    $lesson = addslashes(trim($_POST['lessonlearnt']));
    $sql = "call createReview($userid,$bookid,$rating,'$oneline','$suggest','$lesson');";
    if ($conn->query($sql) === true)
    {
        echo " <script> console.log('DONE!!')</script>";
    }
    else
    {
        echo " <script> console.log('Failed!!')</script>";
    }
    unset($_POST['add_review']);
    $conn->close();
}
// ADDING quotes
if (isset($_POST['add_quote']))
{
    // echo "Adding quotes to DB";
    include ('dbconnect.php');
    $bookid = $_POST['bookid'];
    $quote = addslashes(trim($_POST['quote']));
    $sql = "call insertQuotes($userid,$bookid,'$quote');";
    if ($conn->query($sql) === true)
    {
        echo "<script>console.log('DONE!!')</script>";
    }
    else
    {
        echo "<script>console.log('Failed insert!!')</script>";
    }
    unset($_POST['add_quote']);
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
    <div class="mx-auto p-3 gap-3" style="width:70%">
        <form class='p-3 text-center' method="POST" action="">
            <label for="bookNameSearchBar" class="form-label display-4 text-primary mx-auto">Search for books</label>
            <input class="form-control md-2" list="datalistOptions" id="bookNames" name="bname"
                placeholder="Type to search...">
            <datalist id="datalistOptions">
                <?php
            include('dbconnect.php');
            $sql="select bid,title,name as author from books natural join author;";
            $res=$conn->query($sql);
            
            for ($x = 0; $x < $res->num_rows; $x++){
                $row = $res->fetch_assoc();  
                echo "<option value=\"".$row['title']."\">" ;
                // echo "<script>console.log('".$row['title']."')</script>";
                
               }
        ?>
            </datalist>

            <button type="submit" name="search" class="btn btn-primary md-5 mt-3 me-3">Search</button>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mt-3 me-3" data-bs-toggle="modal" data-bs-target="#addBook">
                Add a new Book
            </button>
        </form>
    </div>
    <div class="may-hide row mt-4 p-3 rounded border border-secondary mx-auto" style="width:60%">
        <div class="col-md-3">
            <?php
         $path="./covers/".$specificrow['bid'].".jpg";
         if(!file_exists($path)){
            $path="./covers/cover.jpg";
         }
        ?>
            <img id="img" class="rounded img-thumbnail" src="<?php echo $path; ?>">
        </div>
        <div class="col-md-9 bookdetails">
            <div class="row">
                <i class="text-primary fw-narrow fs-4 col-md-4 ">Title</i>
                <div class="col-md-8 fw-narrow fs-5"><?php echo $specificrow["title"]; ?> <br /></div>
            </div>
            <div class="row">
                <i class="text-primary fw-narrow fs-4 col-md-4">Genre</i>
                <div class="col-md-8 fw-narrow fs-5"><?php echo $specificrow["genre"]; ?> <br /></div>
            </div>
            <div class="row">
                <i class="text-primary fw-narrow fs-4 col-md-4">Author</i>
                <div class="col-md-8 fw-narrow fs-5"><?php echo $specificrow["author"]; ?> <br /></div>
            </div>
            <div class="row">
                <i class="text-primary fw-narrow fs-4 col-md-4">Release Year</i>
                <div class="col-md-8 fw-narrow fs-5"><?php echo $specificrow["year"]; ?> <br /></div>
            </div>

            <form method="POST" action="">
                <input type=text value=<?php echo $specificrow['bid']?> style="display:none" name="bookid">

                <?php 
                $displayStatus="";
                if(isset($specificrow['status']))
                $status=$specificrow['status'];
                else{
                $status="";
                $displayStatus="Not yet started";
                
                }
                if($status=="DONE"){
                    if(empty($specificrow['review']))
                    {
                    $displayStatus= "Book finished";
                
                ?>
                <br />
                <button type="button" class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#addReview">
                    Add a new Review
                </button>

                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuote">
                    Add a Quote
                </button>
                <br/><br/>
                <button type="submit" name="backToReads" class="btn btn-primary">
                   Reread
                </button>
                

                <?php
                    
                    }
                    else{
                        $displayStatus= "Book reviewed";
                ?>
                <br />
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addQuote">
                    Add a Quote
                </button>
                <button type="submit" name="backToReads" class="btn btn-primary">
                   Reread
                </button>
                <?php
                   }
                }
                if($status=="WISHLIST"){
                    $displayStatus= "In WishList";
                ?>
                <br /><button class="btn btn-primary" type="submit" name="startReading">Start Reading</button>
                <?php
                }
                if($status=="CURRENT"){
                    $displayStatus= "Now reading";
                    ?>
                <br /><button class="btn btn-primary" type="submit" name="markAsDone">Mark As Done</button>
                <?php
                }
                if(empty($status)){
                    $displayStatus= "Not yet started"; 
                    ?>
                <br /><button class="btn btn-primary" type="submit" name="addToRList">Add To WishList</button>
                <?php
                }
                ?>

            </form>
            <br />
            <div class="row">
                <i class="text-primary fw-narrow fs-4 col-md-4">Status</i>
                <div class="col-md-8 fw-narrow fs-5"><?php echo $displayStatus; ?></div>
            </div>
            <br />

        </div>
    </div>
    <br />
    <br />
    <span>
        <!-- Button trigger modal -->

        <!-- Modal -->
        <div class="modal fade" id="addReview" tabindex="-1" aria-labelledby="addReview" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addReview">Add a Review</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="needs-validation" method="POST">
                        <div class="modal-body mb-3">
                            <div class="form-group mb-3">
                                <label for="rating" class="form-label">Rate out of 5</label>
                                <input id="rating" class="form-control" type="number" min="1" max="5"
                                    placeholder="Rating" name="rating" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="onelinereview" class="form-label">Review</label>
                                <textarea id="onelinereview" class="form-control" type="text"
                                    placeholder="Write your review here" name="onelinereview"></textarea>
                            </div>
                            <div class="form-group mb-3">
                                <label for="suggestedBy" class="form-label">Suggested By</label>
                                <input id="suggestedBy" class="form-control" type="text" placeholder="Suggested by"
                                    name="suggestedby">
                            </div>
                            <div class="form-group mb-3">
                                <label for="lessonLearnt" class="form-label">Lesson Learnt</label>
                                <textarea id="lessonLearnt" class="form-control" type="text" placeholder="Lessons"
                                    name="lessonlearnt" required></textarea>
                            </div>
                            <!-- have to remove book title / bid -->
                            <script>
                            console.log('<?php echo $specificrow['bid']; ?>')
                            </script>
                            <input type="text" value="<?php echo $specificrow['bid']; ?>" name="bookid"
                                style="display:none">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="add_review">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </span>
    <br />
    <br />
    <span>

        <!-- Modal -->
        <div class="modal fade" id="addBook" tabindex="-1" aria-labelledby="addNewBook" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewBook">Add a new book</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="needs-validation" method="POST" enctype="multipart/form-data">
                        <div class="modal-body mb-3">
                            <div class="form-group mb-3">
                                <label for="BookTitle" class="form-label">Book Title</label>
                                <input id="BookTitle" class="form-control" type="text" placeholder="BOOK TITLE"
                                    name="title" required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="Genre" class="form-label">Genre</label>
                                <input id="Genre" class="form-control" type="text" placeholder="GENRE" name="genre">
                            </div>
                            <div class="form-group mb-3">
                                <label for="Year" class="form-label">Year</label>
                                <input id="Year" class="form-control" type="number" placeholder="YEAR" name="year"
                                    required>
                            </div>
                            <div class="form-group mb-3">
                                <label for="Author" class="form-label">Author</label>
                                <input id="Author" class="form-control" type="text" placeholder="AUTHOR" name="author"
                                    required>
                            </div>
                            <!-- upload cover -->
                            <label for="cover" class="form-label">Upload cover</label>
                            <br />
                            <input type="file" name="image" value="Upload cover">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="add_book">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </span>
    <br />
    <br />
    <span>

        <!-- Modal -->
        <div class="modal fade" id="addQuote" tabindex="-1" aria-labelledby="addNewQuote" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addNewQuote">Add a new quote</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form class="needs-validation" id = "quoteInsert" method="POST">
                        <div class="modal-body mb-3">
                            <div class="form-group mb-3">
                                <label for="QuoteTitle" class="form-label">Quote</label>
                                <textarea id="QuoteTitle" form="quoteInsert" class="form-control" placeholder="QUOTE TITLE"
                                    name="quote" required></textarea>
                            </div>
                            <!-- have to remove book title / bid -->
                            <input type="text" value="<?php echo $specificrow['bid']; ?>" name="bookid"
                                style="display:none">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" name="add_quote">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </span>
    <?php
    if($reqbid=="any"){
        echo "<script>document.getElementsByClassName('may-hide')[0].style.display='none'</script>";

    }?>
</body>

</html>