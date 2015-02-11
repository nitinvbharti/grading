<!DOCTYPE html>
<html>
  <body>
    
    <?php
      include 'mainmenu.php';
      if(isset($_POST['max_submit']))//As the faculty clicks on submit the given input gets stored in the database.
      {
        $temp=0;
        $total = 0;
        $max_marks = array();
        session_start();
        $course_id=$_SESSION['course_id']; 
        $count = 0;
        foreach($_POST as $key => $value)
        {
          $max_marks[$key] = $value;
          if($key!="submit")
          {
            $count = $count + $value;
          }
        }
        if($count != 100){
          $temp = 1;
        }
        if ($temp == 0){
          foreach($max_marks as $key => $value)
          {
            if($key!="submit")
            {
              $field=substr($key, 0, 3);
              if($value>=0){
                mysqli_query($con,"update cutoff set $field='$value' where course_id='$course_id'  ");    
              }
              else{
                $temp=1;
              }
            }
          }
        }
        if($temp==0)
          echo "<b><font color ='green'>CUTOFF marks saved successfully</font></b>";
        else
          echo"<b><font color='red'>Error:The values entered cannot be negative. Also the sum of the exams must be 100. </font></b>";
      }    
      else
      {
        //In this page the faculty enters max marks for each attribute and selects submit.
        session_start();
        // store session data
        $_SESSION['course_id']=$_POST['course_sub'];
        $course_id=$_SESSION['course_id']; 
      }
      $sql="select * from cutoff where course_id='$course_id'";  
      $result = mysqli_query($con,$sql);
      $count=mysqli_num_rows($result);  
      if ($count == 1) {
        echo "<br><br><b><center>COURSE ID  = $course_id </center></b><br><br> ";     
        echo "<font color='blue'><b><center>NOTE : Enter max marks for each column.</center></b></font><br><br>";  
        echo "<form action='cutoff.php' method='post' ><table align='center'>
        <tr>
        	<th>QUIZ 1</th>
        	<th>QUIZ 2</th>
        	<th>ASSIGNMENT</th>
        	<th>MID SEM</th>
        	<th>END SEM</th>
        </tr>";
        $row = mysqli_fetch_array($result);
        echo "<tr>";             
      	echo "<td><input type='number' name='q1c".$row['course_id']."'  value=".$row['q1c']."></td>";
      	echo "<td><input type='number' name='q2c".$row['course_id']."'  value=".$row['q2c']."></td>";
        echo "<td><input type='number' name='ssc".$row['course_id']."'  value=".$row['ssc']."></td>";
    	  echo "<td><input type='number' name='msc".$row['course_id']."'  value=".$row['msc']."></td>";
    	  echo "<td><input type='number' name='esc".$row['course_id']."'  value=".$row['esc']."></td>";
    	  echo "</tr>";
        echo "</table><br><br >";
            
        echo "<center><input name =\"max_submit\" type=\"submit\" value=\"Submit\"></form></center>";
          
      }
        
      else{
        echo "</br></br><center><b>SQL table not present</b><center>";
    	}
	
	?>



  </body>
</html>
