<?php
  session_start();
  if (isset($_SESSION['pass'])) {
    if (isset($_POST['addst'])) {
      include '../db.php';
      $id = $_POST['studentid'];
      $name = $_POST['name'];
      $phone = $_POST['phone'];
      $session = $_POST['session'];
      $staff = 0;
      $password = md5($id);
      $q = $db->prepare("insert into users (username, password, name, phone, staff, session) values (?, ?, ?, ?, ?, ?)");
      $q->bind_param('ssssss',$id,$password,$name,$phone,$staff,$session);
      if ($q->execute()) {
        $_SESSION['success'] = "Student added successfuly.";
        $q->close();
        $db->close();
        header("Location:view-students.php");
        exit;
      }
      else {
        echo $db->error;
        exit;
        $_SESSION['errmsg'] = "Request not completed, please try again.";
        $q->close();
        $db->close();
        header("Location:add-student.php");
        exit;
      }
    }
    else {
      header("Location:/");
      exit;
    }
  }
  else {
    header("Location:logout.php");
    exit;
  }
?>
