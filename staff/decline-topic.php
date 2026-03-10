<?php
  session_start();
  if (isset($_SESSION['stapass'])) {
    if (isset($_GET['topic_id']) && isset($_GET['student_id'])) {
      $id = $_GET['topic_id'];
      $sid = $_GET['student_id'];
      include '../db.php';
      $approved = -1;
      $q = $db->prepare("update projects set approved = ? where id = ? and username = ?");
      $q->bind_param('sss',$approved,$id,$sid);
      $q->execute();
      $q->close();
      $db->close();
      header("Location:view-topics.php?student_id=$sid");
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
