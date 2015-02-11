<!DOCTYPE html>
<html>

<body>
  <?php
    //In this page the faculty can view complete details of how students performed in a particular course.
    include 'mainmenu.php';
    session_start();
    $faculty_id=$_SESSION['faculty_id'];
    if(isset($_POST['viewsubmit']))
    {
       $_SESSION['course_id']=$_POST['subnov'];
       $course_id=$_SESSION['course_id'];
       $sql="select * from course_student where course_id='$course_id' order by rollno";

       $result = mysqli_query($con,$sql);
       echo "<br><br><b><center>COURSE ID  = $course_id </center></b><br><br> ";
       echo "<table align='center'>
       <tr>
       <th>ROLL NO</th>
       <th>NAME</th>
       <th>QUIZ 1</th>
       <th>QUIZ 2</th>
       <th>ASSIGNMENT</th>
       <th>MID SEM</th>
       <th>END SEM</th>
       <th>TOTAL</th>
       <th>GRADE</th>
       </tr>";

       while($row = mysqli_fetch_array($result))
       {
         echo "<tr>";
         echo "<td>" . $row['rollno'] . "</td>";
    	   $sql2="select * from students where rollnumber='$row[rollno]'";
         $result2 = mysqli_query($con,$sql2);
         $row2 = mysqli_fetch_array($result2);
         echo "<td>" . $row2['name'] . "</td>";
         echo "<td>".$row['q1']."</td>";
         echo "<td>".$row['q2']."</td>";
         echo "<td>".$row['ss']."</td>";
         echo "<td>".$row['ms']."</td>";
         echo "<td>".$row['es']."</td>";
         echo "<td>".$row['tt']."</td>";
         echo "<td>".$row['gr']."</td>";
         echo "</tr>";
       }
       echo "</table><br>";

    }
     /////////////////////////////////////////////
    else
    {
      $sql="select * from jan_may_2014_slots where FACULTY='$faculty_id'";
      echo "<h1><center><br><br><br>Select a course to view its details.</center></h1><br>";
      echo "<form name='view' action='view.php' method='post'>";
      echo "<center><b>Course:<b> <select name='subnov'></center>";
      $result = mysqli_query($con,$sql);
      while($row = mysqli_fetch_array($result))
      {
        $x=$row['ID'];
        echo "<option value='$x'>".$x."</option>";
      }
      echo "</select>";
      echo "<input name='viewsubmit' type='submit' value='Enter'>";
      echo "</form>";
    }

  ?>
</body>
</html>