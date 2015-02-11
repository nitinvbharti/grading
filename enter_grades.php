<!DOCTYPE html>
<html>

<body>
<?php
	//In this page the faculty choose a particular course to enters grades and when finished he clicks on submit.
	include 'mainmenu.php';
	session_start();
	$faculty_id=$_SESSION['faculty_id'];
	$sql="select * from jan_may_2014_slots where FACULTY='$faculty_id'";
	echo "<h1><center><br><br><br>Select a course to enter grades for students</center></h1><br>";

	echo "<form name='grades' action='grade.php' method='post'>";
	echo "<center>Course: <select name='subnog'></center>";
	$result = mysqli_query($con,$sql);
	while($row = mysqli_fetch_array($result))
	{
	   $x=$row['ID'];
	   echo "<option value='$x'>".$x."</option>";
	}
	echo "</select>";
	echo "<input type='submit' name = 'enter_grades_submit' value='Enter'>";
	echo "</form>";
	?>
</body>
</html>