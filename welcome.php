<!DOCTYPE html>
<html>

<body>
  <?php
  //In this page we find course details of particular faculty along with marks distribution buttons,if we click those buttons it is directed to cutoff.php.

  include 'mainmenu.php';

  session_start();
  $faculty_id=$_SESSION['faculty_id'];
  $password=$_SESSION['password'];
  $sql="select * from faculty where faculty_id='$faculty_id' and password='$password'";

  $result = mysqli_query($con,$sql);
  // Mysql_num_row is counting table row
  $count=mysqli_num_rows($result);

  if($count==1)
  {
    $row = mysqli_fetch_array($result);
    echo "<br><br><h3>Welcome ". $row['name']."</h3>";
    echo "<h4><center>Courses Offered:</center></h4>";
    $sql="select * from jan_may_2014_slots where FACULTY='$faculty_id'";
    $result = mysqli_query($con,$sql);

    echo "<form action='cutoff.php' method='post' >";
    echo "<table align='center'>
    <tr>

      <th> SELECT</th>
      <th>NAME</th>
      <th>COURSE ID</th>

    </tr>";

    while($row = mysqli_fetch_array($result))
      // each iteration of while loop corresponds to each record
      {

        echo "<tr>";
        echo "<td><input type = \"radio\" name = \"course_sub\" value = \"" . $row['ID'] ."\" ></td>";
        echo "<td>" . $row['ID'] . "</td>";
        echo "<td>" . $row['NAME'] . "</td>";
        echo "</tr>";
      }
      echo "</table>";
      echo "</br><center><input type = \"submit\" name = \"course_submit\" value = \"Submit\" <\center>";
      echo "</form><br><br>";

  }

  mysqli_close($con);

  ?>

</body>
</html>