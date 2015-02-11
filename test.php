<!DOCTYPE html>
<html>

<head>
  <style>
  table,th,td
  {
    width:350px; 
    padding:4px;
    border:2px solid blue;
    border-collapse:collapse;
    text-align:center;
  }
  </style>
</head>

<body>



  <center><img src="home.png" alt="some_text"></center>
  <br><br><br><br>

  <h2><center>Faculty Login </center></h2> 
  <form name='login' action="<?php echo $_SERVER['PHP_SELF'];?>" method='post'>
    <table align = "center">
      <tr>
        <td> Faculty ID </td>
        <td><input type="text" name="faculty_id" required></td>
      </tr>
      <tr>
        <td> Password </td>
        <td><input type="password" name="password" required></td>
      </tr>
      <tr>
  			<td><input type="reset" value="Reset"/>
        <td><input name="submit" type="submit" value="Submit"/>
                  
      </tr>
    </table>

  </form>

  <?php
    if(isset($_POST['submit']))   // this is a login page where we check Faculty ID and password are matched with the database.If so directed to welcome.php page else error is shown.
    {
      session_start();
      $_SESSION['faculty_id']=$_POST['faculty_id'];
      $_SESSION['password']=$_POST['password'];
      $faculty_id=$_SESSION['faculty_id'];
      $password=$_SESSION['password'];
      
      $con=mysqli_connect("localhost","root","1234","iiitdm");
      // Check connection
      if (mysqli_connect_errno())
      {
        echo "Failed to connect to MySQL: " . mysqli_connect_error();
      } 
      $sql="select * from faculty where faculty_id='$faculty_id' and password='$password'";
      $result = mysqli_query($con,$sql);
      // Mysql_num_row counts table row
      $count=mysqli_num_rows($result);
      if($count==1)
      {
        header("Location: http://localhost/Grading/welcome.php");
      }
      else
      {
        echo "<br><br><b><font color='red'><center>Invalid ID or Password</center></font></b>";
      }
    }
   ?>

</body>
</html>



