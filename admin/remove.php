<?php
  session_start();
  if ($_SESSION['pass']) {
    if (isset($_GET['id'])) {
      $id = $_GET['id'];
      include '../db.php';
      $q = $db->prepare("delete from users where username = ?");
      $q->bind_param('s',$id);
      if ($q->execute()) {
        $_SESSION['msg'] = "User successfuly removed.";
      } else {
        $_SESSION['msg'] = "Request wasn't completed, please try again.";
      }
      $q->close();
      $db->close();
      if (isset($_GET['staff'])) {
        if($_GET['staff'] == "true"){
          header("Location:view-staffs.php");
        }
        else {
          header("Location:view-students.php");
        }
      }
      else {
        header("Location:/");
      }
      exit;
    }
    else {
      header("Location:home.php");
      exit;
    }
  }
  else {
    header("Location:logout.php");
    exit;
  }
?>
