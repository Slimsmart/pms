<?php
  session_start();
  if (isset($_SESSION['stupass'])) {
    if (isset($_POST['pp'])) {
      $topic = $_POST['topic'];
      if (empty($topic)) {
        $_SESSION['errmsg'] = "Topic cannot be empty";
        header("Location:index.php");
        exit;
      }
			if($_FILES['abstract']['error'] == 0) {
        
        if ($_FILES['abstract']['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {

      		$source_file = $_FILES['abstract']['tmp_name'];
          $studentid = $_SESSION['stupass'];
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
            $q = $db->prepare("insert into projects (topic, abstract, username, abstract_content) values (?, ?, ?, ?)");
            $q->bind_param('ssss', $topic, $filename, $studentid, $abstract_content);
            $q->execute();
            $q->close();
            $db->close();
            $_SESSION['msg'] = "Proposal submitted successfully.";
          }
          else {
            $_SESSION['errmsg'] = "error encountered while uploading document.";
          }
          header("Location:index.php");
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
