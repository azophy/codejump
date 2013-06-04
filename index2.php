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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Loading Bootstrap -->
    <link href="codejump-files/css/bootstrap.css" rel="stylesheet">

    <!-- Loading Flat UI -->
    <link href="codejump-files/css/flat-ui.css" rel="stylesheet">
    <link rel="shortcut icon" href="codejump-files/images/favicon.ico">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="codejump-files/js/html5shiv.js"></script>
    <![endif]-->
    
    <link rel="stylesheet" href="./codejump-files/codemirror.css" />
    <!-- <link rel="stylesheet" href="./codejump-files/jqtree.css"> -->
    
    <style>
      .CodeMirror {
		border: 1px solid black;
      }
      
      .cm-delimit {
          color: #fa4;
      }
      
      body {
        padding-top:40px;padding-bottom:40px;
      }
      #list_file { height:450px;overflow-y:scroll; }
    </style>
  </head>
  <body>
      <!-- HEADER -->
      <div class="row-fluid">
        <div class="span12">
          <div class="navbar navbar-fixed-top navbar-inverse">
            <div class="navbar-inner">
              <ul class="nav">
                <li><a href="#"><span class="fui-new-24"></span> CodeJump</a></li>
                <li>
                  <a href="#">File Menu</a>
                  <ul>
                    <li><a id="menu_new" href="javascript: save_clicked();">Create New File</a></li>
                    <li><a href="javascript:load(prompt('Masukkan nama file!'));">Load File</a></li>
                    <li><a id="menu_ren" href="javascript: rename(filename);">Rename Current File</a></li>
                    <li><a id="menu_del" href="javascript: del_file(filename);">Delete Current File</a></li>
                  </ul>
                </li>
              </ul>
              <ul class="nav pull-right">
                <li><a href="#">Log Out</a></li>
              </ul>
            </div>
          </div><!-- /container/row/span12/navbar navbar-inverse -->
        </div><!-- /container/row/span12 -->
      </div><!-- /container/row-fluid -->
    
      <!-- MAIN AREA -->
      <div class="row-fluid" id="main-area">
        <div class="span2">
          <h4>File Browser</h4>
          <div id="list_file"></div>
        </div>
        <div class="span10">
          <form method="post" action="#">
            <textarea id="code" name="code"><?php
			if (isset($_GET['filename'])) {
				//echo $content; 
				echo $data;
				} else { ?>Select file to begin editing...
<?php 			} ?></textarea>
          </form>
        </div>
      </div>
    
      <div class="navbar navbar-inverse navbar-fixed-bottom">
        <div class="navbar-inner">
          <p class="navbar-text">&copy; Copyright 2013 <a href="www.azophy.com">www.azophy.com</a></p>
        </div>
      </div>
    
    <!-- Load JS here for greater good =============================-->
    
    <script src="codejump-files/js/jquery-1.8.2.min.js"></script>
    <script src="codejump-files/js/jquery-ui-1.10.0.custom.min.js"></script>
    <script src="codejump-files/js/jquery.dropkick-1.0.0.js"></script>
    <script src="codejump-files/js/custom_checkbox_and_radio.js"></script>
    <script src="codejump-files/js/custom_radio.js"></script>
    <script src="codejump-files/js/jquery.tagsinput.js"></script>
    <script src="codejump-files/js/bootstrap-tooltip.js"></script>
    <script src="codejump-files/js/jquery.placeholder.js"></script>
    <script src="http://vjs.zencdn.net/c/video.js"></script>
    <script src="codejump-files/js/application.js"></script>
    <!--[if lt IE 8]>
      <script src="codejump-files/js/icon-font-ie7.js"></script>
      <script src="codejump-files/js/icon-font-ie7-24.js"></script>
    <![endif]-->
    <script type="text/javascript">
      var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
      document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
    </script>
    <script type="text/javascript">
      try{
        var pageTracker = _gat._getTracker("UA-19972760-2");
        pageTracker._trackPageview();
        } catch(err) {}
    </script>
    
    <!-- --------------------------------------------------------------- -->
	<script src="./codejump-files/codemirror.js"></script>
	<script src="./codejump-files/xml.js"></script>
	<script src="./codejump-files/css.js"></script>
	<script src="./codejump-files/javascript.js"></script>
	<script src="./codejump-files/htmlmixed.js"></script>

	<script src="./codejump-files/emmet.min.js"></script>
    <script src="./codejump-files/tree.jquery.js"></script>
	<!-- --------------------------------------------------------------- -->
        
    <script>
		var doc = CodeMirror.fromTextArea(document.getElementById("code"), {
			mode : "text/html",
			lineNumbers : true,
			profile: 'xhtml' /* define Emmet output profile */
		});
	</script>
	
	<script>
      //disable class first
        if (!($("#menu_ren").hasClass("disabled"))) {
          $("#menu_ren").addClass("disabled");
          $("#menu_del").addClass("disabled");
        }
      
		function load(f) {
		  filename = f; //alert(filename);
		  var url = "./codejump.php?filename=" + filename; 
		  jQuery.get(url, function(data) {
			//alert(data);
			doc.setValue(data);
		  });
		  $("#label").text("Editing file: " + filename);
          //enable menu
          if ($("#menu_ren").hasClass("disabled")) {
            $("#menu_ren").removeClass("disabled");
            $("#menu_del").removeClass("disabled");
          }
		}

		function del_file(f) {
		  if (confirm("Are you sure you want to delete file '" + f + "' ?")) {
			var url = "./codejump.php?del_file=" + f; 
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
			var url = "./codejump.php?rename_from=" + f1 + "&rename_to=" + f2; 
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
			  var url = "./codejump.php?filename=" + filename; 
			  jQuery.post(url, {save:true, code: doc.getValue()}, function(data) {
				if (data == '1') 
                  alert("File '" + filename + "' successfully saved!");
                else
                  alert("Error in saving file '" + filename + "'!\n" + data); 
			  });
			  load(filename);
			}
		}

		function create_new() {
		  filename = prompt('Insert new name for the file');
		  var url = "./codejump.php?create_new=" + filename; 
		  jQuery.get(url, function(data) {
			//window.location = "./codejump.php?filename=" + filename; 
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
      
        function enable_menu(mode) {
          if (mode) {
            if ($("#menu_ren").hasClass("disabled")) {
              $("#menu_ren").removeClass("disabled");
              $("#menu_del").removeClass("disabled");
            }
          } else {
            if (!($("#menu_ren").hasClass("disabled"))) {
              $("#menu_ren").addClass("disabled");
              $("#menu_del").addClass("disabled");
            }
          }
        }
        

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
          {
              label: 'node1',
              children: [
                  { label: 'child1' },
                  { label: 'child2' }
              ]
          },
          {
              label: 'node2',
              children: [
                  { label: 'child3' }
              ]
          }
      ];
      $('#list-file').tree({
          data: data,
          autoOpen: true,
          dragAndDrop: true
      });
	</script>
  </body>
</html>