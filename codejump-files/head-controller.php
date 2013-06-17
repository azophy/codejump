<?php
//HANDLE MAGIC QUOTES GPC
handle_magic();

if (isset($_GET['logout']) && $_GET['logout']) {
  session_destroy();
  header("location: codejump.php");
} else if (isset($_POST['log_in'])) {
  if ( ($_POST['user'] == $codejump_username) && (md5($_POST['pass']) == $codejump_pass_hash) ) {
	$_SESSION['logged_in'] = true;
  }
}

if (is_logged_in()) {

  if (isset($_GET['is_file_exist'])) {
      echo (file_exists($_GET['is_file_exist']))?'1':'0';
      exit(0);
 } else if (isset($_GET['node'])) {
  	get_path_content($_GET['node']);
	exit(0);
 } else if (isset($_FILES["upload_file"])) {
  	if ($_FILES["upload_file"]["error"] > 0) {
	  //echo "Return Code: " . $_FILES["upload_file"]["error"] . "<br>";
    } else {
	  if (file_exists("upload/" . $_FILES["upload_file"]["name"])) {
		  //echo $_FILES["upload_file"]["name"] . " already exists. ";
	  } else {
		  move_uploaded_file($_FILES["upload_file"]["tmp_name"], "./" . $_FILES["upload_file"]["name"]);
		  //echo "1";
	  }
    }
	//exit(0);
  } else if (isset($_GET['filename'])) {
	  if (isset($_POST['save'])) {
		  $file=fopen($_GET['filename'], 'w') or die('Cannot open file:  '.$_GET['filename']);
		  fwrite($file,$_POST['code']);
		  fclose($file);
		  echo 1;
          exit(0);
	  } else {
		$file=fopen($_GET['filename'],"r") or exit("Unable to open file!");
		$content="";
		/*while (!feof($file))
		  {
		  //$content+=fgetc($file);
		  }*/
		$data = fread($file,filesize($_GET['filename']));
		fclose($file);
		echo $data;
	  }
	  exit(0);
  } else if (isset($_GET['create_new'])) {
	  $file=fopen($_GET['create_new'], 'w') or die('Cannot open file:  '.$_GET['filename']);
	  fwrite($file,"empty file..");
	  fclose($file);
	  echo 1;
      exit(0);
  } else if (isset($_GET['del_file'])) {
      if (unlink($_GET['del_file'])) echo 1; 
      exit(0);
  } else if (isset($_GET['rename_from'])) {
      if (rename($_GET['rename_from'], $_GET['rename_to'])) {
        echo 1;
      }
      exit(0);
  }
}
?> 
