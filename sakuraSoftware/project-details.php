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
		    echo "<a class='active-nav' href='projects-logged.php'>Projects</a>";
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
		    echo "<a class='active-nav' href='projects-public.php'>Projects</a>";
            echo "<a href='search.php'>Search</a>";
            ?>
        </div>
        <div class="users">
            <?php
			echo "<button class=\"login-btn\" onclick=\"document.getElementById('login-form').style.display='block'\">Log In</button>";
			echo "<a href='signup.php'> Sign Up</a>";
		}
            ?>
        </div>
	  </nav>

	  <div class="login-form" id="login-form">
		<form method="post">
			<label for="username">Username:</label>
			<input type="text" id="username" name="username">

			<label for="password">Password:</label>
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
					
					//recording the user session variable and go to index page
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

<h2 class="heading"> Our Projects! </h2>
<div id="slideshow-container">

    <?php
        require_once ('connectdb.php');  
        try {
            $data=$_GET['data'];
            $query="SELECT projects.*, users.email
            FROM projects
            INNER JOIN users ON projects.uid = users.uid
            WHERE projects.pid='$data'";
            //run  the query
            $rows =  $db->query($query);
            
        //display the course list in a table 	
            if ( $rows && $rows->rowCount()> 0) {
    ?>

    <table style="width:100%; margin-left:4%;">
    <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Phase</th>
        <th>Description</th>
        <th>Email</th>
    </tr>            

        <?php
		// Fetch and  print all  the records.
			while  ($row =  $rows->fetch())	{
				echo  "<tr><td align='left'>" . $row['pid'] . "</td>";
				echo  "<td align='left'>" . $row['title'] . "</td>";
                echo  "<td align='left'>" . $row['start_date'] . "</td>";
                echo  "<td align='left'>" . $row['end_date'] . "</td>";
                echo  "<td align='left'>" . $row['phase'] . "</td>";
                echo  "<td align='left'>" . $row['description'] . "</td>";
				echo "<td align='left'>". $row['email'] . "</td></tr>\n";
			}
			echo  '</table>';
		}
		else {
			echo  "<p>No  project in the list.</p>\n"; //if no match found
		}
	}
	catch (PDOexception $ex){
		echo "Sorry, a database error occurred! <br>";
		echo "Error details: <em>". $ex->getMessage()."</em>";
	}
    ?>
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
