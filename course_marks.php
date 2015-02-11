<!DOCTYPE html>
<html>

<body>
  <?php
    include 'mainmenu.php';
    //Faculty enters marks for each rollno and clicks on submit.
    //When submitted the values are checked with cutoff values and then stored in database.
    //If the valued are exceeding cutoff then error message is shown.
    if(isset($_POST['submit']))
    {
      session_start();
      $course_id=$_SESSION['course_id']; 
      $x=1;
      $student_marks = array(); // i addead
      foreach($_POST as $key => $value) // rollnumber.exam and marks
      {
        if($key!="submit")
    	  {
      	  $rollno=substr($key, 0, 9);
      	  $field=substr($key, 9,2);
      	  $sql="select * from cutoff where course_id='$course_id'";
      	  $result = mysqli_query($con,$sql);
      	  $row = mysqli_fetch_array($result);
      	  $c="c";
      	  $temp=$field.$c;
      	  if($value>$row[$temp]||$value<0) // if entered marks is greater than the cutoff
          {
            $x=0;
            echo "<b><font color='blue'>$key is invalid.<br></font></b>";
      	  }
      	  else
          {
            $student_marks[$key] = $value;  //i added
          }
        }  
      }
      if($x==1)
      {
      	foreach ($student_marks as $key => $value){ // i added the foreach loop
          $rollno=substr($key, 0, 9);
      		$field=substr($key, 9,2);
      		mysqli_query($con,"update course_student set $field='$value' where rollno='$rollno'  ");
      	}
    	  $sql2 ="select * from course_student where course_id='$course_id'";
    	  $result2 = mysqli_query($con,$sql2);
        while($row2 = mysqli_fetch_array($result2))
    	  {
          $row=$row2['q1']+$row2['q2']+$row2['ss']+$row2['ms']+$row2['es'];
          $rollno=$row2['rollno'];
          mysqli_query($con,"update course_student set tt='$row' where rollno='$rollno'");
    	  }
        echo"<b><font color='green'>Marks saved Successfully.</font></b>";
      }
      else
      {    
        echo"<b><font color='red'>Error:The values were not stored in the db.</font></b>";
      }
    } 
     
     /////////////////////////////////////////////////////////////////////////////
     
     
    else
    {
      session_start();
      $_SESSION['course_id']=$_POST['subno'];
      $course_id=$_SESSION['course_id'];
    }
    $sql1="select * from cutoff where course_id='$course_id'";
    $result1 = mysqli_query($con,$sql1);
    $count=mysqli_num_rows($result1);
    $row1 = mysqli_fetch_array($result1);
    if($count != 1){
      echo "<br><br><br><br><br><br><br><br><b><center><font color='red'>NOTE : SQL table not present.</font></center></b>";
    }

    elseif($row1['q1c']==0&&$row1['q2c']==0&&$row1['ssc']==0&&$row1['msc']==0&&$row1['esc']==0)
    {
      echo "<br><br><br><br><br><br><br><br><b><center><font color='red'>NOTE : Please Enter Marks Distribution for this course in HOME page before entering marks.</font></center></b>";
      exit;
    }

    else{ 
      echo "<br><br><b><center>COURSE ID  = $course_id </center></b><br><br> ";
      $sql="select * from course_student where course_id='$course_id' order by rollno";
      $result = mysqli_query($con,$sql);
      echo "<form action='course_marks.php' method='post' ><table align='center'>;
      <tr>
      <th>ROLL NO</th>
      <th>NAME</th>";
      // only display exams which have weightage
      if($row1['q1c']>0)
        echo '<th>QUIZ 1</th>';
      if($row1['q2c']>0)
        echo '<th>QUIZ 2</th>';
      if($row1['ssc']>0)
        echo '<th>ASSIGNMENT</th>';
      if($row1['msc']>0)
        echo '<th>MID SEM</th>';
      if($row1['esc']>0)
        echo '<th>END SEM</th>';
      echo '</tr>';


      while($row = mysqli_fetch_array($result))
      {
        echo "<tr>";
        echo "<td>" . $row['rollno'] . "</td>";
        $sql2="select * from students where rollnumber='$row[rollno]'";
        $result2 = mysqli_query($con,$sql2);
        $row2 = mysqli_fetch_array($result2); // to find the name of the student
        echo "<td>" . $row2['name'] . "</td>";
        if($row1['q1c']>0)
          echo "<td><input type='number' name='".$row['rollno']."q1' value=".$row['q1']."></td>";
        if($row1['q2c']>0)
          echo "<td><input type='number' name='".$row['rollno']."q2'  value=".$row['q2']."></td>";
        if($row1['ssc']>0)
          echo "<td><input type='number' name='".$row['rollno']."ss'  value=".$row['ss']."></td>";
        if($row1['msc']>0)
          echo "<td><input type='number' name='".$row['rollno']."ms'  value=".$row['ms']."></td>";
        if($row1['esc']>0)
          echo "<td><input type='number' name='".$row['rollno']."es'  value=".$row['es']."></td>";
        echo "</tr>";
      }
      echo "</table><br><br >";

      echo "<center><input name ='submit' type=\"submit\" value=\"Submit\"></form></center>";
    }
  ?>
</body>
</html>
