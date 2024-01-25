<?php
session_start(); // Start the session (if not already started)
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Sakura Software</title>
	<link rel="icon" href="images/our-logo.png" type="image/x-icon">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css">
</head>

<body>
<header>
<section id="main-header">
  <div class="banner-container">
    <img src="images/index-banner3.png" alt="image of cherry blossom trees used as a banner" class="banner">
    <nav class="nav">
        <?php
            //If user is logged in
            if (isset($_SESSION['username'])) {
            ?>
            <div class="pages">
            <?php
                //User IS logged in
                echo "<a href='index.php'>Home</a>";
                echo "<a href='projects-logged.php'>Projects</a>";
                echo "<a href='search.php'>Search</a>";
                ?>
            </div>
            <div class="users">
                <?php
                echo "<a href='logout.php'> Log Out </a>";
            } 
            ?>
    </nav>
    
<h1 class="name"> Sakura Software </h1>
    </div>
    </section>
    </header>


    <h2 class="heading"> Add A Project </h2>
<div id="slideshow-container">

  <?php
//if the form has been submitted
if (isset($_POST['submitted'])){
    #prepare the form input

    // connect to the database
    require_once('connectdb.php');
        
    $pid=$db->lastInsertId();
    $title=isset($_POST['title'])?$_POST['title']:false;
    $start_date=isset($_POST['start_date'])?$_POST['start_date']:false;
    $end_date=isset($_POST['end_date'])?$_POST['end_date']:false;
    $description=isset($_POST['description'])?$_POST['description']:false;
    $phase=isset($_POST['phase'])?$_POST['phase']:false;
    // get the users ID
    try {
        if (isset($_SESSION['username'])) {
            $stat = $db->prepare('SELECT uid FROM users WHERE username = ?');
            $stat->execute(array($_SESSION['username']));
            // fetch the result row and check 
            if ($stat->rowCount() > 0) {  // matching username
                $row = $stat->fetch();
                $user_id = $row['uid'];
            }
        }
    } catch(PDOException $ex) {
        echo("Failed to connect to the database.<br>");
        echo($ex->getMessage());
        exit;
    }
    //if not nullable data is missing give warning
    if (!($title)){
        echo "Please enter a title!";
        exit;
        }
    if (!($start_date)){
        echo "Please enter a start date!";
        exit;
        }
    if (!($description)){
        exit("Please write a descrtiption!");
        }
    if (strlen($title) > 50){
        exit("Title is too long!");
        }
    if (strlen($description) > 255){
        exit("Description is too long!");
        }
    if ($start_date > $end_date){
        exit("Start date must be before end date!");
        }
    if ($phase !== 'design' && $phase !== 'development' && $phase !== 'testing' && $phase !== 'deployment' && $phase !== 'complete'){
        exit("Invalid phase option!");
          }
    try{
        #register user by inserting the user info 
        $stmt = $db->prepare("INSERT INTO projects (pid, title, start_date, end_date, phase, description, uid) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$pid, $title, $start_date, $end_date, $phase, $description, $user_id]); 
        echo "Your project has been added!";
        
    }
    catch (PDOexception $ex){
        echo "Sorry, a database error occurred! <br>";
        echo "Error details: <em>". $ex->getMessage()."</em>";
    }
 
    }

    ?>
    <!-- Form for the user to add a project -->
<section id="register">
    <p style="margin-left:35%;"> Fill out the form below to add a project! </p>
	<form name="contactForm" method="post" action="add.php">
  <table style="margin-left:25%;">
    <tr>
      <td>Title:</td>
      <td><input type="text" name="title" class="form-input"></td>
    </tr>
    <tr>
      <td>Start Date:</td>
      <td><input type="date" name="start_date" class="form-input"></td>
    </tr>
    <tr>
      <td>End Date:</td>
      <td><input type="date" name="end_date" class="form-input"></td>
    </tr>
    <tr>
      <td>Phase:</td>
      <td>
        <select name="phase" class="form-input">
          <option value="design">Design</option>
          <option value="development">Development</option>
          <option value="testing">Testing</option>
          <option value="deployment">Deployment</option>
          <option value="complete">Complete</option>
        </select>
      </td>
    </tr>
    <tr>
      <td>Description:</td>
      <td><input type="text" name="description" class="form-input"></td>
    </tr>
  </table>
  <input type="submit" value="Add" class="register-btn"></br></br>
  <input type="hidden" name="submitted" value="true" class="form-input">
</form>
    </section>

        </div>
        </div>
        </div>
    </div>

    <!-- Footer Section -->
  <footer>
  	<div id="footer">
			<div class="footer-column">
				<p><strong>Charlie Lake</strong></p>
				<p> 220093705@aston.ac.uk<br>
				SUM: 220093705 </p>
			</div>

		<div class="footer-column">
  		<a href="https://gb.linkedin.com/"><img src="images/linked-logo.png" alt="linked-in logo"></a>
  		<a href="https://github.com/"><img src="images/github-logo.png" alt="github logo"></a>
  		<a href="https://twitter.com/"><img src="images/twitter-logo.png" alt="twitter logo"></a>
		</div>

		<div class="footer-column">
		</div>
  	</div>
  </footer>

	<script src="js/script.js"></script>
  </body>
  </html>
