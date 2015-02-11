<!DOCTYPE html>
<html>

<head>
    <link rel = "stylesheet" href = "navbar.css"/>
	<style>
	table,th,td
	{
		align:center;
		padding:4px;
		border:2px solid blue;
		border-collapse:collapse;
		text-align:center;
	}
	</style>
</head>

<body>
	<center><img src="home.png" alt="some_text"></center>

	<?php         
		// this page consists of main menu which is included in every other page;            
		echo "<br><br>";
		// connect to the database iiitdm
		$con=mysqli_connect("localhost","root","1234");
		mysql_select_db("iiitdm");
		// Check connection
		if (mysqli_connect_errno())
  		{
  			echo "Failed to connect to MySQL: " . mysqli_connect_error();
  		}
	?>
	<ul id="menu">
	<li><a href="welcome.php">Home</a></li>
	<li><a href="enter_marks.php">Enter Marks</a></li> 
	<li><a href="enter_grades.php">Enter Grades</a></li> 
	<li><a href="view.php">View</a></li> 
	<li><a href="email.php">GENERATE EMAIL</a></li> 
	<li><a href="logout.php">Logout</a></li> 
	</ul>
	<br><br>

</body>
</html>