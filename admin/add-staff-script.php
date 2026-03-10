<?php
  session_start();
  if (isset($_SESSION['pass'])) {
    if (isset($_POST['addsf'])) {
      include '../db.php';
      $id = $_POST['staffid'];
      $name = $_POST['name'];
      $phone = $_POST['phone'];
      $staff = 1;
      $password = md5($id);
      $q = $db->prepare("insert into users (username, password, name, phone, staff) values (?, ?, ?, ?, ?)");
      $q->bind_param('sssss',$id,$password,$name,$phone,$staff);
      if ($q->execute()) {
        $_SESSION['success'] = "Staff added successfuly.";
        $q->close();
        $db->close();
        header("Location:view-staffs.php");
        exit;
      }
      else {
        $_SESSION['errmsg'] = "Request not completed, please try again.";
        $q->close();
        $db->close();
        header("Location:add-staff.php");
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
