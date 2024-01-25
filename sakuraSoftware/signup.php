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
            } else {
                ?>
            <div class="pages">
            <?php
                //User is NOT logged in
                echo "<a href='index.php'>Home</a>";
                echo "<a href='projects-public.php'>Projects</a>";
                echo "<a href='search.php'>Search</a>";
                ?>
            </div>
            <div class="users">
                <?php
                echo "<button class=\"login-btn\" onclick=\"document.getElementById('login-form').style.display='block'\">Log In</button>";
                echo "<a class='active-nav' href='signup.php'> Sign Up</a>";
            }
                ?>
            </div>
        </nav>

        <div class="login-form" id="login-form">
            <form method="post">
                <label for="username" class="form-label">Username:</label>
                <input type="text" id="username" name="username">

                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password">

                <input type="submit" name="submitted" value="Login"/>
            </form>
                <button onclick="document.getElementById('login-form').style.display='none'" class="close-btn">Close</button>
            </div>

            <?php
        
        //if the form has been submitted
        if (isset($_POST['submitted'])){
            if ( !isset($_POST['username'], $_POST['password']) ) {
            // Could not get the data that should have been sent.
            exit('Please fill both the username and password fields!');
            }
            // connect DB
            require_once ("connectdb.php");
            try {
            //Query DB to find the matching username/password
            //using prepare to prevent SQL injection.
                $stat = $db->prepare('SELECT password FROM users WHERE username = ?');
                $stat->execute(array($_POST['username']));
                
                // fetch the result row and check 
                if ($stat->rowCount()>0){  // matching username
                    $row=$stat->fetch();

                    if (password_verify($_POST['password'], $row['password'])){ //matching password
                        
                        //??recording the user session variable and go to index page?? 
                    session_start();
                        $_SESSION["username"]=$_POST['username'];
                        header("Location:index.php");
                        exit();
                    
                    } else {
                    echo "<p style='color:red'>Error logging in, password does not match </p>";
                    }
                } else {
                //else display an error
                echo "<p style='color:red'>Error logging in, Username not found </p>";
                }
            }
            catch(PDOException $ex) {
                echo("Failed to connect to the database.<br>");
                echo($ex->getMessage());
                exit;
            }

        }
        ?>

<h1 class="name"> Sakura Software </h1>
    </div>
    </section>
    </header>


    <h2 class="heading"> Sign Up! </h2>
<div id="slideshow-container">
  <!-- Image Slideshow -->

  <?php
//if the form has been submitted
if (isset($_POST['submitted'])){
    #prepare the form input

    // connect to the database
    require_once('connectdb.php');
        
    $username=isset($_POST['username'])?$_POST['username']:false;
    $password=isset($_POST['password'])?password_hash($_POST['password'],PASSWORD_DEFAULT):false;
    $email=isset($_POST['email'])?$_POST['email']:false;
    echo $username;
    if (!($username)){
        echo "Username wrong!";
        exit;
        }
    if (!($email)){
        echo "email wrong!";
        exit;
        }
    if (!($password)){
        exit("password wrong!");
        }
    try{
        
        #register user by inserting the user info 
        $stat=$db->prepare("insert into users values(default,?,?,?)");
        $stat->execute(array($username, $password, $email));
        
        $uid=$db->lastInsertId();  	
        
    }
    catch (PDOexception $ex){
        echo "Sorry, a database error occurred! <br>";
        echo "Error details: <em>". $ex->getMessage()."</em>";
    }
 
    }
    ?>
<section id="register">
    <p style="margin-left:35%;"> Fill out the form below to register! </p>
	<form name="contactForm"  method="post" action="signup.php" style="margin-left:26%;">
        <table>
    <tr>
        <td>Username:</td>
        <td><input type="text" name="username" class="form-input"/></td>
    </tr>
    <tr>
        <td>Email:</td>
        <td><input type="email" name="email" class="form-input"/></td>
    </tr>
    <tr>
        <td>Password:</td>
        <td><input type="text" name="password" class="form-input"/></td>
    </tr>
    <tr>
        <td colspan="2"><input type="submit" value="Register" class="register-btn"/></td>
    </tr>
    <input type="hidden" name="submitted" value="true" class="form-input"/>
    </table>

	</form>
    </section>

        </div>
        </div>
    </div>


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
