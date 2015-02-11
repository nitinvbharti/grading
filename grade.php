<!DOCTYPE html>
<html>

<head>
<style type="text/css">

.left_side 
      {
     float: left;
     margin: 30px 0 0 250px;
      }
.right_side 
     {
     float:right;
     margin: 0px 150px 0px 0px;
     }	  	  
</style>
</head>

<body>

  <?php
    include 'mainmenu.php';
    //Here Faculty can choose order of students based on rollno or total.
    //cutoff for each grade is given by the faculty based on which grade is automatically generated.
    //ALong with the automatically generated grade  faculty also finds W,I grades where he has to choose a particular grade and submit.
    //after submit the values get stored in the database.

      $x=0; 
      $grade_array = array();

      if(isset($_POST['submit_grade_allot']))
       {
        foreach($_POST as $key => $value)
        {
          if($key!="submit_grade_allot")
          {
            $rollno=substr($key, 0, 9);
            //echo "<br />".$rollno;
            $field=substr($key, 9,2);
            //echo "<br />".$field;
            mysqli_query($con,"update course_student set $field='$value' where rollno='$rollno'  ");
          }
        }
        echo "<b><font color='green'>Grades entered Successfully</font></b>";

        session_start();  
        $course_id=$_SESSION['course_id']; 

        echo "<br><br><b><center>COURSE ID  = $course_id </center></b><br> ";
        $sql="select * from course_student where course_id='$course_id' order by rollno";
        $result = mysqli_query($con,$sql);
        foreach ($grade_array as $key => $value) {
          echo $key. " " . $value;
        }
      }
      elseif(isset($_POST['rollno_select']))
      {
        session_start();
        $course_id=$_SESSION['course_id']; 

        echo "<br><br><b><center>COURSE ID  = $course_id </center></b><br> ";
        $sql="select * from course_student where course_id='$course_id' order by rollno";
        $result = mysqli_query($con,$sql);
      }

      elseif(isset($_POST['total_select']))
      {
        session_start();
        $course_id=$_SESSION['course_id']; 

        echo "<br><br><b><center>COURSE ID  = $course_id </center></b><br> ";
        $sql="select * from course_student where course_id='$course_id' order by tt desc";
        $result = mysqli_query($con,$sql);
      }

      elseif(isset($_POST['grade_cutoff_submit']))
      {
        $temp=0;
        $i=0;
        session_start();
        $course_id=$_SESSION['course_id']; 

        foreach($_POST as $key => $value)
        {
          if($key!="grade_cutoff_submit")
          {
            $array[$i]=$value;
            $i++;
          }
        }

        for($i=0;$i<5;$i++)
        {
          if($array[$i]<=0||$array[$i]<$array[$i+1])
            $temp=1;
        }
        if($array[5]<=0)
        {
          $temp=1;
        }
      	
        if($temp==1)
        {
          echo"<b><font color='red'>Error:Values entered are incorrect.</font></b><br>";
          echo"<b><font color='blue'>NOTE:Enter positive values such that cutoff(S) > cutoff(A) and so on.</font></b>";
        }
        else
        {
          foreach($_POST as $key => $value)
          {
            if($key!="grade_cutoff_submit")
            {
              $field=$key;
              mysqli_query($con,"update cutoff set $field='$value' where course_id='$course_id'  ");
            }
          }
          echo "<b><font color='green'>Cutoff marks for grades are entered Successfully</font></b><br>";
          echo "<b><font color='blue'>NOTE : Along with the grade generated through cutoff you will find W,I grades in the DropDownList .So select the appropriate option and click submit.</font></b>";
        }
        $sql1="select * from course_student where course_id='$course_id' order by tt desc";
        $result1 = mysqli_query($con,$sql1);	

        $sql2="select * from cutoff where course_id='$course_id'";
        $result2 = mysqli_query($con,$sql2);
        $row2 = mysqli_fetch_array($result2);

        while($row1 = mysqli_fetch_array($result1))
        {
          $key_for_array = $course_id . $row1['rollno'];
          if($row1['tt']>=$row2['s'])
            $grade_array[$key_for_array] = 'S';
          elseif($row1['tt']<$row2['s']&&$row1['tt']>=$row2['a'])
            $grade_array[$key_for_array] = 'A';
          elseif($row1['tt']<$row2['a']&&$row1['tt']>=$row2['b'])
            $grade_array[$key_for_array] = 'B';
          elseif($row1['tt']<$row2['b']&&$row1['tt']>=$row2['c'])
            $grade_array[$key_for_array] = 'C';
          elseif($row1['tt']<$row2['c']&&$row1['tt']>=$row2['d'])
            $grade_array[$key_for_array] = 'D';
          elseif($row1['tt']<$row2['d']&&$row1['tt']>=$row2['e'])
            $grade_array[$key_for_array] = 'E';
          else
            $grade_array[$key_for_array] = 'U';
        }

        $_SESSION['grade_arr'] = $grade_array;
        echo "<br><br><b><center>COURSE ID  = $course_id </center></b><br> ";
        $sql="select * from course_student where course_id='$course_id' order by tt desc ";
        $result = mysqli_query($con,$sql);
      } 

      // start of the page
      else
      { 
        session_start();
        // store session data
        $_SESSION['course_id']=$_POST['subnog'];
        $course_id=$_SESSION['course_id']; 
        $result = mysqli_query($con,"select * from course_student where course_id = '$course_id' order by tt desc");
        while($row = mysqli_fetch_array($result)) {
          $key_string = $course_id . $row['rollno'];
          $grade_array[$key_string] = 'N';
        }
        $_SESSION['grade_arr'] = $grade_array;
        echo "<br><br><b><center>COURSE ID  = $course_id </center></b><br> ";
        $sql="select * from course_student where course_id='$course_id' order by rollno";
        $result = mysqli_query($con,$sql);
        $count=mysqli_num_rows($result);
      }
     
  ?>


  <div class="left_side">
  <form action='grade.php' method='post' ><table align='center'>
  <?php echo"<br>" ?>
  <center>
                  <input name='rollno_select' type="submit" value="Sort by ROLLNO">
                  <input name='total_select' type="submit" value="Sort by TOTAL">
  </center>
  <?php
    /*if ($count != 1){
      echo "<br><br><br><br><br><br><br><br><b><center><font color='red'>NOTE : SQL table not present.</font></center></b>";  
    }
    */

    echo "<br>
    <tr>
    <th>ROLL NO</th>
    <th>NAME</th>
    <th>TOTAL</th>
    <th>GRADE</th>
    </tr>";
    $grade_array = $_SESSION['grade_arr'];
    if (isset($_POST['rollno_select'])) {
      ksort($grade_array);
    }
    foreach ($grade_array as $key => $value) {
      $course_id1 = substr($key,0,7);
      $roll_number = substr($key,7,9);
      echo "<tr>";
      echo "<td>" . $roll_number . "</td>";
      $sql_name="select * from students where rollnumber='$roll_number'";
      $result_name = mysqli_query($con,$sql_name);
      $row_name = mysqli_fetch_array($result_name);
      echo "<td>" . $row_name['name'] . "</td>";
      //$row['tt']=$row['q1']+$row['q2']+$row['ss']+$row['ms']+$row['es'];
      $result = mysqli_query($con, "select * from course_student where rollno = '$roll_number ' and course_id = '$course_id1'");
      //mysqli_query($con,"update course_student set tt=".$row['tt']." where rollno=".$row['rollno']."  ");
      $row = mysqli_fetch_array($result);
      echo "<td>".$row['tt']."</td>";
      echo '<td><select name="'.$row["rollno"].'gr">
        <option ';
      if($row["gr"]=="S"||$row["gr"]=="A"||$row["gr"]=="B"||$row["gr"]=="C"||$row["gr"]=="D"||$row["gr"]=="E"||$row["gr"]=="U")
        echo 'selected';
      echo '>'.$value.'</option>';      
      echo '<option ';
      if($row["gr"]=='W')
        echo 'selected';
      echo '>W</option>'; 
      echo '<option ';

      if($row["gr"]=='I')
        echo 'selected';
      echo '>I</option>';

      echo '</select>
      </td>'; 
      echo "</tr>";     
    }

    echo "</table><br>";
    echo "<br>";

  ?>
  <center><input name ='submit_grade_allot' type="submit" value="Submit"></center>
  </form>

  </div>

  <div class="right_side">
  <form action='grade.php' method='post' ><table align='center'>

  <?php
    $sql="select * from cutoff where course_id='$course_id'";
    $result = mysqli_query($con,$sql);
    $row = mysqli_fetch_array($result);

    echo "
    <tr>
    <th>GRADE</th>
    <th>CUTOFF MARKS</th>
    </tr>";


      echo "<tr>";
      echo "<td>" .'S'. "</td>";
      echo "<td><input name='s' type='number' value=".$row['s']."></td>";
      echo "</tr>";

      echo "<tr>";
      echo "<td>" .'A'. "</td>";
      echo "<td><input name='a' type='number' value=".$row['a']."></td>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<td>" .'B'. "</td>";
      echo "<td><input name='b' type='number' value=".$row['b']."></td>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<td>" .'C'. "</td>";
      echo "<td><input name='c' type='number' value=".$row['c']."></td>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<td>" .'D'. "</td>";
      echo "<td><input name='d' type='number' value=".$row['d']."></td>";
      echo "</tr>";
      
      echo "<tr>";
      echo "<td>" .'E'. "</td>";
      echo "<td><input name='e' type='number' value=".$row['e']."></td>";
      echo "</tr>"; 
      echo "</table><br>";
  ?>

  <center>
    <input name='grade_cutoff_submit' type="submit" value="Submit">
  </center>

  <?php  
    echo "</form>";
  ?>
  </div>

</body>
</html>
