<?php
  session_start();
  if (isset($_SESSION['pass'])) {
    if (isset($_POST['ab'])) {
      $topic = $_POST['topic'];
      if (empty($topic)) {
        $_SESSION['errmsg'] = "Topic cannot be empty";
        header("Location:index.php");
        exit;
      }
			if($_FILES['abstract']['error'] == 0) {
        if ($_FILES['abstract']['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
      		$source_file = $_FILES['abstract']['tmp_name'];
          $uploader = "admin";
          $filename = "doc".date('Ymhis').$_FILES['abstract']['name'];
      		$dest_file = "../uploads/".$filename;

          if (move_uploaded_file( $source_file, $dest_file )) {
            $info = new SplFileInfo($filename);
            $file_ext = $info->getExtension();
            include '../files.php';
            if ($file_ext == "docx") {
              $abstract_content = docx_to_text($dest_file);
            }
            else {
              $abstract_content = doc_to_text($dest_file);
            }
            include '../db.php';
            $approved = "1";
            $q = $db->prepare("insert into projects (topic, abstract, username, abstract_content, approved) values (?, ?, ?, ?, ?)");
            $q->bind_param('sssss', $topic, $filename, $uploader, $abstract_content, $approved);
            $q->execute();
            $q->close();
            $db->close();
            $_SESSION['msg'] = "Project added successfuly!";
          }
          else {
            $_SESSION['errmsg'] = "error encountered while uploading document.";
          }
          header("Location:./");
          exit;
        }
        else {
          $_SESSION['errmsg'] = "Invalid file type uploaded.";
          header("Location:index.php");
          exit;
        }
			}
      else {
        $_SESSION['errmsg'] = "request not completed, please try again.";
        header("Location:index.php");
        exit;
      }
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
