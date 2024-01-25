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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="js/script.js"></script>
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
			echo "<a class='active-nav'href='index.php'>Home</a>";
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
			echo "<a class='active-nav' href='index.php'>Home</a>";
		    echo "<a href='projects-public.php'>Projects</a>";
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
		// connect database
		require_once ("connectdb.php");
		try {
		//Query DB to find the matching username/password
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

<main>
<section id="about-us">
	<h2 class="heading"><u> About Us </u></h2>
	<p> Sakura Software is an independent software development company that designs, creates and maintains software of all kinds! </p>
</section>

<h2 class="heading"><u>Meet The Team</u></h2>
<section id="team">
  <div class="team-columns">
    <div class="member-card">
      <img src="images/me-character.png" alt="Charlie Lake">
      <div class="education">
			<h2>  Charlie Lake </h2>
			<p> Howdy! I am one of the founders here at Sakura Software. I specialise in <em>Website Development</em> but have also dabbled in Java, Python and other smaller languages.  </p>
		</div>
		<div class="skills">
			<div class="skill">
				<div class="skill-img-column">
					<img src="images/java-icon.png" alt="Java icon">
				</div>
					<div class="skill-column">
						<div class="bar-container">
							<div class="bar-5"></div>
						</div>
					</div>
				</div>

			<div class="skill">
				<div class="skill-img-column">
					<img src="images/python-icon.png" alt="Python icon" >
				</div>
				<div class="skill-column">
				<div class="bar-container">
					<div class="bar-1"></div>
				</div>
				</div>
			</div>

			<div class="skill">
				<div class="skill-img-column">
					<img src="images/html-icon.png" alt="HTML icon">
				</div>
				<div class="skill-column">
				<div class="bar-container">
						<div class="bar-5"></div>
				</div>
				</div>
			</div>
		</div>
    </div>

    <div class="member-card">
    <img src="images/founder2.png" alt="Daisy Jones">
		<div class="education">
			<h2>  Daisy Jones </h2>
			<p> Hi there! I am another one of the founders for Sakura Software. I specialise in <em>Java</em> but also have some experience using Python and HTML </p>
		</div>

		<div class="skills">
			<div class="skill">
				<div class="skill-img-column">
					<img src="images/java-icon.png" alt="Java icon" >
				</div>
					<div class="skill-column">
						<div class="bar-container">
							<div class="bar-2"></div>
						</div>
					</div>
				</div>

			<div class="skill">
				<div class="skill-img-column">
					<img src="images/python-icon.png"  alt="Python icon" >
				</div>
					<div class="skill-column">
						<div class="bar-container">
							<div class="bar-5"></div>
						</div>
					</div>
				</div>

			<div class="skill">
				<div class="skill-img-column">
					<img src="images/html-icon.png" alt="HTML icon">
				</div>
					<div class="skill-column">
						<div class="bar-container">
							<div class="bar-1"></div>
						</div>
					</div>
				</div>
			</div>
    </div>

<div class="member-card">
  <img src="images/founder3.png" alt="Billy Dunne">
		<div class="education">
				<h2>  Billy Dunne </h2>
				<p> Hey! I am a senior software developer who specialises in <em> back-end development </em> though I can code using various different languages </p>
			</div>
			<div class="skills">
				<div class="skill">
					<div class="skill-img-column">
						<img src="images/java-icon.png" alt="Java icon" >
					</div>
				<div class="skill-column">
					<div class="bar-container">
						<div class="bar-5"></div>
					</div>
				</div>
			</div>

				<div class="skill">
					<div class="skill-img-column">
						<img src="images/python-icon.png" alt="Python icon">
					</div>
					<div class="skill-column">
						<div class="bar-container">
							<div class="bar-5"></div>
						</div>
					</div>
				</div>

				<div class="skill">
					<div class="skill-img-column">
						<img src="images/html-icon.png" alt="HTML icon">
					</div>
					<div class="skill-column">
						<div class="bar-container">
							<div class="bar-3"></div>
						</div>
					</div>
				</div>
			</div>
</div>
</div>
</section>

<section>
	<h2 class="heading"><u>What have people said?</u></h2>
	<div class="team-columns">

		<div class="prev-client">
			<div class="prev-client-header">
				<img src="images/aston.png" alt="Aston University Logo" style="width:30%;">
				<h3> Aston University </h3>
			</div>
			<p> One of our clients includes Aston University, who got in touch with us to help them to build and manage their website</p>
			<div class="testimonials">
				<blockquote>
					<p> “The guys at Sakura Software were super helpful, they communicated quickly and efficiently and ensured that everything was done perfectly!” </p>
					<footer>  - <strong>Nick</strong>: Professor at Aston University </footer>
				</blockquote>
			</div>
		</div>


		<div class="prev-client">
			<div class="prev-client-header">
				<img src="images/acss.png" alt="Aston university computer science society logo" style="width:12%;">
				<h3> Aston Computer Science Society</h3>
			</div>
			<p> Astons Computer Science Society (ACSS) also asked us to help build a few of their discord bots! We did this using python! </p>
			<div class="testimonials">
				<blockquote>
					<p> “Couldn't have asked for better service or better help! Thankyou!” </p>
					<footer>  - <strong>Fynnley</strong>: President of the ACSS </footer>
				</blockquote>
			</div>
		</div>

	</div>
</section>

</main>

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

</body>
</html>
