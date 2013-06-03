<?php
/*
CodeJump version 0.8

Copyright 2013 by azophy (www.azophy.com).

Licensed under GPL, read README.txt for further information
*/

require_once "./codejump-files/config-codejump.php";
session_start();

//code taken from: http://www.dreamincode.net/forums/topic/147880-does-php-5-automatically-addslashes/
function remove_magic($array, $depth = 5)
{
  if($depth <= 0 || count($array) == 0)
	return $array;
  
  foreach($array as $key => $value)
  {
	if(is_array($value))
	  $array[stripslashes($key)] = remove_magic($value, $depth - 1);
	else
	  $array[stripslashes($key)] = stripslashes($value);
  }
  
  return $array;
}

if(function_exists('set_magic_quotes_runtime'))
  @set_magic_quotes_runtime(0);

if((function_exists('get_magic_quotes_gpc') && @get_magic_quotes_gpc() == 1) || @ini_get('magic_quotes_sybase')) {
  $_COOKIE = remove_magic($_COOKIE);
  $_GET = remove_magic($_GET);
  $_POST = remove_magic($_POST);
  $_REQUEST = remove_magic($_REQUEST);
}

if (isset($_GET['logout']) && $_GET['logout']) {
  session_destroy();
  header("location: codejump.php");
} else if (isset($_POST['log_in'])) {
  if ( ($_POST['user'] == $codejump_username) && (md5($_POST['pass']) == $codejump_pass_hash) ) {
	$_SESSION['logged_in'] = true;
  }
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {

  if (isset($_GET['filename'])) {
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
<!doctype html>
<html>
<head>
	<title>CodeJump Editor</title>
	<link rel="stylesheet" href="./codejump-files/codemirror.css" />
	<script src="./codejump-files/codemirror.js"></script>
	<script src="./codejump-files/xml.js"></script>
	<script src="./codejump-files/css.js"></script>
	<script src="./codejump-files/javascript.js"></script>
	<script src="./codejump-files/htmlmixed.js"></script>

	<script src="./codejump-files/emmet.min.js"></script>
	<script src="./codejump-files/jquery-2.0.0.min.js"></script>
  
    <script src="./codejump-files/tree.jquery.js"></script>
    <link rel="stylesheet" href="./codejump-files/jqtree.css">
	
	<style>
	.CodeMirror {
		border: 1px solid black;
	}
	
	.cm-delimit {
		color: #fa4;
	}
      .header {
        background: #9b0bcc;padding: 10px;margin:0;
      }
      .header h1 {
        float:left; position: relative;margin:0;padding:0;
       }
      .header .file-info {
          float: left;margin:10px;padding:0;
      }
      .header .menu {
        float: right; margin:10px;
       }
      .header .menu a {
        font-weight: bold; color: #000;
       }
      
	  .row { position:relative;}
      .sidebar { position:relative; float: left; background:#f6dcff; width:20%;padding:10px; }
      #list_file { height:400px;overflow-y:scroll; }
      .button {
        position: relative; margin:0;width:100%; 
      }
      .button a {
        background: #310042; color: #fff; font-weight: bold; text-align: center; 
        text-decoration: none; padding: 10px; margin-right:3px;
      }
      .edit_area  { float: left; width:70%; padding: 10px; border-left: 1px solid #ba94c5; }
      .edit_area h2 { margin:0;padding:0;}
      .footer {
        margin:0;background: #9b0bcc;padding:10px;
      }
      .login-box {
        background: #9b0bcc; text-align: center; width: auto; margin:200px 0;
        position: relative;
        padding: 10px;
      }
      .login-box * {
        text-align: center;
      }
	</style>  
	<script>
	  var filename = "";
	</script>
</head>
<body>
<?php 
if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) { ?>
	<div class="header">
	  <h1>CodeJump</h1>
<?php 
	if (isset($_GET['filename'])) { ?>
	 <span id="label" class="file-info">Editing file: <?php echo $_GET['filename']; ?></span>
<?php } else { ?>
	 <span id="label" class="file-info">Select a file to begin editing</span>
<?php } ?>
      <span class="menu"><a href="?logout=true">Log Out</a></span>
      <br style="clear:both" />
	</div>
	<div class="row">
	  <div class="sidebar">
        <div id="list_file"></div>
        <hr/>
        <div class="button">
          <a href="javascript: create_new();">Create New File</a>
        </div>
	  </div>
	  <div class="edit_area">
		
		<form method="post" action="#"><textarea id="code" name="code"><?php
			if (isset($_GET['filename'])) {
				//echo $content; 
				echo $data;
				} else { ?>Select file to begin editing...
<?php 			} ?>
		</textarea>
		<!-- <button name="save" id="save">Save</button> -->
          <h2>Press Ctrl+s to save the file</h2>
          <div class="button" style="margin-top:10px;">
            <a href="javascript: del_file(filename);">Delete Current File</a>
            <a href="javascript: rename(filename);">Rename Current File</a>
          </div>
		</form>
	  </div>
	  <br style="clear:both;"/>
	</div>
    <div class="footer">
      &copy; Copyright 2013 <a href="http://www.azophy.com">azophy</a>
    </div>

	<script>
		var doc = CodeMirror.fromTextArea(document.getElementById("code"), {
			mode : "text/html",
			lineNumbers : true,
			profile: 'xhtml' /* define Emmet output profile */
		});
	</script>
	
	<script>
		function load(f) {
		  filename = f; //alert(filename);
		  var url = "./codemaster.php?filename=" + filename; 
		  jQuery.get(url, function(data) {
			//alert(data);
			doc.setValue(data);
		  });
		  $("#label").text("Editing file: " + filename);
		}

		function del_file(f) {
		  if (confirm("Are you sure you want to delete file '" + f + "' ?")) {
			var url = "./codemaster.php?del_file=" + f; 
			jQuery.get(url, function(data) {
			  if (data == 1)
				alert("File '" + f + "' successfully deleted!");
              /*else
                alert("Error in deleting file '" + f + "'!\n" + data); */
			  location.reload();
			});
		  }
		}

		function rename(f1) {
          var f2 = prompt('Enter the new file name you desire:');
		  if (confirm("Are you sure you want to rename file '" + f1 + "' to '" + f2 + "'?")) {
			var url = "./codemaster.php?rename_from=" + f1 + "&rename_to=" + f2; 
			jQuery.get(url, function(data) {
			  if (data == 1) 
				alert("File '" + f1 + "' successfully renamed to '" + f2 + "'!");
              else
                alert("Error in renaming file '" + f1 + "'!\n" + data); 
			  location.reload();
			});
		  }
		}

		function save_clicked() {
			if (confirm("Are you sure you wanted to edit '" + filename + "' ?")) {
			  //filename = f;
			  var url = "./codemaster.php?filename=" + filename; 
			  jQuery.post(url, {save:true, code: doc.getValue()}, function(data) {
				if (data == '1') 
                  alert("File '" + filename + "' successfully saved!");
                else
                  alert("Error in saving file '" + filename + "'!\n" + data); 
			  });
			  load(filename);
			}
		}

		//$("#save").click(save_clicked(filename));

		function create_new() {
		  filename = prompt('Insert new name for the file');
		  var url = "./codemaster.php?create_new=" + filename; 
		  jQuery.get(url, function(data) {
			//window.location = "./codemaster.php?filename=" + filename; 
			location.reload();
			//load(filename);
		  });
		}

		/*$(window).keypress(function(event) {
		    if (!(event.which == 115 && event.ctrlKey) && !(event.which == 19)) return true;
		    alert("Ctrl-S pressed");
		    event.preventDefault();
		    return false;
		});*/

		$(document).keydown(function(event) {

			//19 for Mac Command+S
			if (!( String.fromCharCode(event.which).toLowerCase() == 's' && event.ctrlKey) && !(event.which == 19)) return true;

			//alert("Ctrl-s pressed");
			save_clicked();

			event.preventDefault();
			return false;
		});
      
      //tree view management
      var data = [
<?php
//code taken from: http://stackoverflow.com/questions/4050511/how-to-list-files-and-folder-in-a-dir-php

// Create recursive dir iterator which skips dot folders
$dir = new RecursiveDirectoryIterator('.',
    FilesystemIterator::SKIP_DOTS);

// Flatten the recursive iterator, folders come before their files
$it  = new RecursiveIteratorIterator($dir,
    RecursiveIteratorIterator::SELF_FIRST);

// Maximum depth is 1 level deeper than the base folder
$it->setMaxDepth(1);

$pertama=true;
// Basic loop compiling directories into structured array
$files = Array();
$dirs = Array();
$n_dir=0;
$n_file=0;
foreach ($it as $fileinfo) {
    if ($fileinfo->isDir()) {
      $dirs[++$n_dir] = Array($fileinfo->getFilename(),Array());
    } else if ($fileinfo->isFile()) {
	  $sub_path = $it->getSubPath();
      //printf("<li><a href='javascript: load(&#39;%s&#47;%s&#39;);'>%s/%s</a> <a href='javascript: del_file(&#39;%s&#39;);'>(delete file)</a></li>",$sub_path,$fileinfo->getFilename(), $sub_path, $fileinfo->getFilename(), 
      if (!empty($sub_path)) {
        $dirs[$n_dir][1][] = $fileinfo->getFilename();
      } else {
        $files[++$n_file] = $fileinfo->getFilename();
      }
    }
}
//print_r($dirs);
foreach ($dirs as $dir) { 
  //print_r($dir);
    if ($pertama) $pertama=false; else printf(","); 
?>
  {
        label: '[<?php echo $dir[0]; ?>]',
        children: [
<?php $subpertama = true;
      foreach ($dir[1] as $filename) { ?>
            <?php if ($subpertama) $subpertama=false; else printf(","); ?>{ label: '<?php echo $filename; ?>' }
<?php } ?>
        ]
   }
<?php
}
foreach ($files as $filename) { 
    if ($pertama) $pertama=false; else printf(","); 
?>
  {
        label: '<?php echo $filename; ?>',
        children: [
        ]
   }
<?php
}
?>

      ];
      $('#list_file').tree({
          data: data,
          autoOpen: false,
          dragAndDrop: false
      });
      $('#list_file').bind(
          'tree.select',
          function(event) {
              if (event.node) {
                  // node was selected
                  var node = event.node;
                  if (node.parent.name != null)
                    load('./'+node.parent.name+'/'+node.name);
                  else if (!node.children[0])
                    load('./'+node.name);
                    //alert(node.children);
              }
          }
      );
	</script>
<?php } else { //show login box ?>
  <div class="login-box">
    <h2>Log In</h2>
    <hr />
    <form action="./codejump.php" method="post">
      <label>Username</label>
      <input type="text" name="user" />
      <label>Password</label>
      <input type="password" name="pass" />
      <button type="submit" name="log_in">Log In</button>
    </form>
  </div>
<?php } ?>
</body>
</html>