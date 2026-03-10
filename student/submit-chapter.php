<?php
  session_start();
  if (isset($_SESSION['stupass'])) {
    if (isset($_POST['pcb'], $_POST['projectid'])) {
      $chapter = $_POST['chapter'];
      $projectId = $_POST['projectid'];

      if (empty($chapter)) {
        $_SESSION['errmsg'] = "Project chapter cannot be empty";
        header("Location:index.php");
        exit;
      }
			if($_FILES['file']['error'] == 0) {
        if ($_FILES['file']['type'] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") {
      		$source_file = $_FILES['file']['tmp_name'];
          $studentid = $_SESSION['stupass'];
          $id = preg_replace("/[^a-zA-Z]/", "", $studentid);

          $filename = $id.date('Ymhis').$_FILES['file']['name'];

      		$dest_file = "../uploads/chapters/".$filename;
          
          if (move_uploaded_file( $source_file, $dest_file )) {

            $info = new SplFileInfo($filename);
            $file_ext = $info->getExtension();
            include '../files.php';
            if ($file_ext == "docx") {
              $chapter_content = docx_to_text($dest_file);
            }
            else {
              $chapter_content = doc_to_text($dest_file);
            }
            include '../db.php';
            $q = $db->prepare("select id from chapters where chapter = ? && username = ?");
            $q->bind_param('ss',$chapter,$studentid);
            $q->execute();

            if ($q->num_rows > 0) {
              $q->close();

              $q = $db->prepare("update chapters set link = ? where chapter = ? and username = ?");
              $q->bind_param('ss',$filename, $chapter, $studentid);
              $q->execute();
            }
            else {
              $q->close();

              $q = $db->prepare("insert into chapters (project_id, username, chapter, content, link) values (?, ?, ?, ?, ?)");
              $q->bind_param('sssss', $projectId, $studentid, $chapter, $chapter_content, $filename);
              $q->execute();
            }

            $q->close();
            $db->close();
            $_SESSION['msg'] = "Upload successful.";
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
