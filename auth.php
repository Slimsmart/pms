<?php
  session_start();
  if(isset($_POST['login'])){
    include("db.php");
    $q = $db->prepare("select password, staff from users where username = ?");
    $username = $_POST['username'];
    $password = $_POST['password'];

    $q->bind_param('s',$username);
    $q->execute();
    $q->store_result();
    $n = $q->num_rows;
    if($n > 0){
      $q->bind_result($dbpass, $staff);
      $q->fetch();
      $q->free_result();
      $q->close();
      $db->close();
      if($dbpass == md5($password)){
        if ($staff == "0") {
          $_SESSION['stupass'] = $_POST['username'];
          header("Location:student/");
          exit;
        }
        else {
          $_SESSION['stapass'] = $_POST['username'];
          header("Location:staff/");
          exit;
        }
      }
      else{
          $_SESSION['errmsg']="Invalid username or password";
          header("Location:login.php");
          exit;
      }
    }
    else{
      $q->close();
      $db->close();
      $_SESSION['errmsg']="Invalid username or password";
      header("Location:login.php");
      exit;
    }
  }
  else {
    header("Location:index.php");
    exit;
  }
?>
