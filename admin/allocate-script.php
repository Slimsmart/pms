<?php
  session_start();
  if (isset($_SESSION['pass'])) {
    if (isset($_POST['allocate-form'])) {
      include '../db.php';
      $staff = $_POST['staff'];
      $q = $db->prepare("insert into allocation (staff, Student) values (?, ?)");
      foreach ($_POST as $key => $value) {
        if ( ($key == "staff") || ($key == "allocate-form") ) {
          continue;
        }
        $q->bind_param('ss',$staff, $value);
        $q->execute();
      }
      $q->close();
      $db->close();
      header("Location:allocate.php");
      exit;
    }
    else {
      header("Location:index.php");
      exit;
    }
  }
  else {
    header("Location:logout.php");
    exit;
  }
?>
