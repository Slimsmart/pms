<?php
  session_start();
  if(isset($_POST['login'])){
    include("../db.php");
    $q = $db->prepare("select password from admin where username = ?");
    $username = $_POST['username'];
    $password = $_POST['password'];

    $q->bind_param('s',$username);
    $q->execute();
    $q->store_result();
    $n = $q->num_rows;
    if($n > 0){
      $q->bind_result($dbpass);
      $q->fetch();
      $q->free_result();
      $q->close();
      $db->close();
      if($dbpass == $password){
        $_SESSION['pass']=$_POST['username'];
        header("Location:home.php");
        exit;
      }
      else{
          $_SESSION['errmsg']="Invalid username or password";
          header("Location:index.php");
          exit;
      }
    }
    else{
      $q->close();
      $db->close();
      $_SESSION['errmsg']="Invalid username or password";
      header("Location:index.php");
      exit;
    }
  }
  else {
    header("Location:../");
    exit;
  }
?>
