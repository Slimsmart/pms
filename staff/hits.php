<?php
  if (isset($_GET['file']) && isset($_GET['id']) && isset($_GET['hit'])) {
    include '../db.php';
    $file = $_GET['file'];
    $id = $_GET['id'];
    $hit = $_GET['hit'];
    $h = ++$hit;
    $q0 = $db->prepare("update projects set hits = ? where id = ?");
    $q0->bind_param('ss',$h, $id);
    $q0->execute();
    $q0->close();
    header("Location:../uploads/$file");
    exit;
  }
  else {
    header("Location:index.php");
    exit;
  }
?>
