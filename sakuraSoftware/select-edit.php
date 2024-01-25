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

    <h2 class="heading"> Edit Your Projects </h2>
<div id="slideshow-container">

  <?php
    require_once('connectdb.php');
    
    // get the users ID
    try {
        if (isset($_SESSION['username'])) {
            $stat = $db->prepare('SELECT uid FROM users WHERE username = ?');
            $stat->execute(array($_SESSION['username']));
            // fetch the result row and check 
            if ($stat->rowCount() > 0) {  // matching username
                $row = $stat->fetch();
                $user_id = $row['uid'];
                $query="SELECT  * FROM  projects WHERE uid = $user_id ";
                //run  the query
                $rows =  $db->query($query);
                
            //display the course list in a table 	
                if ( $rows && $rows->rowCount()> 0) {
        ?>
    
        <table style="width:100%; margin-left:40px;">
        <thead>
        <tr>
            <th>Title</th>
            <th>Start Date</th>
            <th>Description</th>
            <th>Select Edit</th>
        </tr>   
        </thead>         
    
        <tbody>
            <?php
            // Fetch and  print all  the records.
            while ($row = $rows->fetch()) {
                $pid = $row['pid'];
                echo "<tr><td align='left'>" . $row['title'] . "</td>";
                echo "<td align='left'>" . $row['start_date'] . "</td>";
                echo "<td align='left'>" . $row['description'] . "</td>";
                echo "<td align='left'>";
                echo "<form method='POST'>";
                echo "<button class='login-btn' onclick=\"document.getElementById('login-form').style.display='block'\" name='pid' value='$pid'>Edit Project</button>";
                echo "</form>";
                echo "</td></tr>\n";
            }
            echo '</tbody></table>';
            }
            else {
                echo  "<p>No  project in the list.</p>\n"; //no match found
            }
        }
    }
        } 
        catch(PDOException $ex) {
            echo("Failed to connect to the database.<br>");
            echo($ex->getMessage());
            exit;
        } 
        if (isset($_POST['pid'])) {
            $pid_set = $_POST['pid'];
        } else {
        }
        ?>

        <p style="margin-left:42.5%;margin-top:20px;"> Please fill out the form with the new data! </p>

        <div id="login-form">
        <form method="post">
    <table style="margin-left:40%;margin-top:30px;">
        <tr>
            <td>ID:</td>
            <td><input type="number" name="auto-id" readonly="readonly" value="<?php echo $pid_set; ?>"></td>
        </tr>
        <tr>
            <td>Title:</td>
            <td><input type="text" name="title" class="form-input"></td>
        </tr>
        <tr>
            <td>Start_Date:</td>
            <td><input type="date" name="start_date" class="form-input"></td>
        </tr>
        <tr>
            <td>End-date:</td>
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
    <div style="margin-left:50%;margin-top:20px;">
        <input type="hidden" name="pid" value="<?php echo $pid; ?>">
        <input type="submit" value="Change" name="submitted">
        <input type="hidden" name="submitted" value="true" class="form-input">
        <button onclick="document.getElementById('edit-form').style.display='none'">Close</button>
    </div>
</form>

	</div>

    <?php 
    if (isset($_POST['submitted'])){
        #prepare the form input
    
        // connect to the database
        require_once('connectdb.php');
        $input_pid = isset($_POST['auto-id'])?$_POST['auto-id']:false;
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
            exit("Please write a description!");
            }
        try{
    
            #register user by inserting the user info 
            $stmt = $db->prepare("UPDATE projects SET title = ?, start_date = ?, end_date = ?, phase = ?, description = ? WHERE pid = ? AND uid = ?");
            $stmt->execute([$title, $start_date, $end_date, $phase, $description, $input_pid, $user_id]); 
            echo "Your project has been updated!";
        }
        catch (PDOexception $ex){
            echo "Sorry, a database error occurred! <br>";
            echo "Error details: <em>". $ex->getMessage()."</em>";
        }
        }
        if (isset($_POST['pid'])) {
            $pid_set = $_POST['pid'];
        } else {
            
        }
        ?>

    </div> <!-- any this after this close div will not be put into the main section -->
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