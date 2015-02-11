<?php
  //code related to excel sheet generation.
  if(isset($_POST['excel']))
  {
    session_start();
    $course_id=$_SESSION['course_id'];
    $sql="select * from course_student where course_id='$course_id' order by rollno";
    $con=mysqli_connect("localhost","root","root","iiitdm");
    $result = mysqli_query($con,$sql);
    header("Content-Type: application/vnd.ms-excel");
    echo 'COURSE ID = '.$course_id ."\n";
    echo 'ROLL NO'."\t".'NAME'."\t".'PRE-END SEM'."\t".'END SEM'."\t".'TOTAL'."\t".'GRADE'."\n";
    while($row = mysqli_fetch_array($result))
    {
      $sql2="select * from students where rollnumber='$row[rollno]'";
      $result2 = mysqli_query($con,$sql2);
      $row2 = mysqli_fetch_array($result2);
      $pes=$row['q1']+$row['q2']+$row['ss']+$row['ms'];
      echo  $row['rollno']."\t".$row2['name']."\t".$pes."\t".$row['es']."\t".$row['tt']."\t".$row['gr']."\n";
    }
    header("Content-disposition: attachment; filename=$course_id.xls");
    exit;
  }
?>

<!DOCTYPE html>
<html>
<body>
<?php
  //Here,FACULTY after reverification will click on Generate EMAIL.when clicked on it an email will be sent to every student of that course regarding marks and grade of that student.
  include 'mainmenu.php';
  session_start();
  $faculty_id=$_SESSION['faculty_id'];

  if(isset($_POST['email']))
  {
    $course_id=$_SESSION['course_id'];
    /**
    * Simple example script using PHPMailer with exceptions enabled
    * @package phpmailer
    * @version $Id$
    */
    require 'mailer/class.phpmailer.php';
    try
    {
      $mail = new PHPMailer(true); //New instance, with exceptions enabled
    	////$body             = file_get_contents('contents.html');
      //$body             = preg_replace('/\\\\/','', $body); //Strip backslashes

      $mail->IsSMTP();                           // tell the class to use SMTP
    	$mail->SMTPAuth   = true;                  // enable SMTP authentication
    	$mail->SMTPSecure ="ssl";
      $mail->Port       = 465;                    // set the SMTP server port
      $mail->Host       = "smtp.gmail.com"; // SMTP server
      $mail->Username   = "iiitgrading@gmail.com";     // SMTP server username
      $mail->Password   = "medhaisanidiot";            // SMTP server password

      //$mail->IsSendmail();  // tell the class to use Sendmail

      //$mail->AddReplyTo("name@domain.com","First Last");

      $mail->From       = "iiitgrading@gmail.com";
      $mail->FromName   = "MAIN";
	
      $sql="select * from course_student where course_id = '$course_id'";
      $result = mysqli_query($con,$sql);
      while($row = mysqli_fetch_array($result))
      {
        $course_id=$row['course_id'];
        $rollno=$row['rollno'];
        $q1=$row['q1'];
        $q2=$row['q2'];
        $ss=$row['ss'];
        $ms=$row['ms'];
        $es=$row['es'];
        $tt=$row['tt'];
        $gr=$row['gr'];
        
        $to = "coe12b007@iiitdm.ac.in";
        $mail->AddAddress($to);
      	$mail->Subject  = "Exam Result";
      	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test
      	$mail->WordWrap   = 80; // set word wrap
        $msg ='
        <table align="center" border=1 cellpadding=10>
        <tr>
        <td>COURSE_ID</td>
        <td>ROLLNO</td>
        <td>QUIZ 1</td>
        <td>QUIZ 2</td>
        <td>ASSIGNMENT</td>
        <td>MID SEM</td>
        <td>END SEM</td>
        <td>TOTAL</td>
        <td>GRADE</td>
        </tr>

        <tr>    
        <td>'.$course_id.'</td>
        <td>'.$rollno.'</td>
        <td>'.$q1.'</td>
        <td>'.$q2.'</td>
        <td>'.$ss.'</td>
        <td>'.$ms.'</td>
        <td>'.$es.'</td>
        <td>'.$tt.'</td>
        <td>'.$gr.'</td>
        </tr>
        </table>';
	
        $mail->MsgHTML($msg);

      	$mail->IsHTML(true); // send as HTML

      	if($mail->Send())
        echo '<b><font color ="green">EMAIL has been sent.</font></b>';
        else
        echo 'not sent!'.$mail->ErrorInfo;
      }
    } 
    catch (phpmailerException $e) 
    {
      echo $e->errorMessage();
    }

  }

  if(isset($_POST['submit']))
  {
    $_SESSION['course_id']=$_POST['subnov'];
    $course_id=$_SESSION['course_id'];
    $sql="select * from course_student where course_id='$course_id' order by rollno";

    $result = mysqli_query($con,$sql);
    echo "<br><br><b><center>COURSE ID  = $course_id </center></b><br>";
    echo "<form action='email.php' method='post' ><table align='center'>";
    echo "<center><input name='excel' type='submit' value='Generate EXCELSHEET'><input name='email' type='submit' value='Generate EMAIL'></center>";
    
    echo "<br>
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
   
    echo "</table></form><br>";
  }

  else
  {
    $sql="select * from jan_may_2014_slots where FACULTY='$faculty_id'";
    echo "<h1><center><br><br><br>Select a course to generate mail.</center></h1><br>";

    echo "<form name='view' action='email.php' method='post'>";
    echo "<center><b>Course:<b> <select name='subnov'></center>";
    $result = mysqli_query($con,$sql);
    while($row = mysqli_fetch_array($result))
    {
      $x=$row['ID'];
      echo "<option value='$x'>".$x."</option>";
    }
    echo "</select>";
    echo "<input name='submit' type='submit' value='Enter'>";
    echo "</form>";
  }

?>
</body>
</html>