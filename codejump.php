<?php
/*
CodeJump version 0.8

Copyright 2013 by azophy (www.azophy.com).

Licensed under GPL, read README.md for further information
*/

session_start();
require_once "./codejump-files/config-codejump.php";
require_once "./codejump-files/library.php";
require_once "./codejump-files/head-controller.php";
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
    <link rel="shortcut icon" href="codejump-files/images/favicon4.ico">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements. All other JS at the end of file. -->
    <!--[if lt IE 9]>
      <script src="codejump-files/js/html5shiv.js"></script>
    <![endif]-->
    
    <link rel="stylesheet" href="./codejump-files/css/codemirror.css" />
    <link rel="stylesheet" href="./codejump-files/css/jqtree.css">
    
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
      #list-file { height:400px;overflow-y:scroll; border:1px solid #eee; padding-left: 15px;}
	  #sidebar { padding:10px; }
    </style>
  </head>
  <body>
<?php
if (is_logged_in()) { ?>
      <!-- HEADER -->
      <div class="row-fluid">
        <div class="span12">
          <div class="navbar navbar-fixed-top navbar-inverse">
            <div class="navbar-inner">
              <ul class="nav">
                <li><a href="#"><img src="./codejump-files/images/logo3-white.png" alt="CodeJump Logo" style="height:20px;" /></a></li>
                <li>
                  <a href="#">File Menu</a>
                  <ul>
                    <li><a id="menu_new" href="javascript: create_new_file();">Create New File</a></li>
					<li><a href="javascript:upload_file();">Upload File</a></li>
					<li class="divider"></li>
                    <li><a href="javascript:load_file(prompt('Masukkan nama file!'));">Load File</a></li>
                    <li><a id="menu_new" href="javascript: save_file(filename);">Save File</a></li>
                    <li><a id="menu_new" href="javascript: save_file_as();">Save As..</a></li>
					<li class="divider"></li>
                    <li><a id="menu_ren" href="javascript: rename_file(filename);">Rename Current File</a></li>
                    <li><a id="menu_del" href="javascript: delete_file(filename);">Delete Current File</a></li>
                  </ul>
                </li>
              </ul>
              <ul class="nav pull-right">
                <li><a href="codejump.php?logout=true">Log Out</a></li>
              </ul>
            </div>
          </div><!-- /container/row/span12/navbar navbar-inverse -->
        </div><!-- /container/row/span12 -->
      </div><!-- /container/row-fluid -->
    
      <!-- MAIN AREA -->
      <div class="row-fluid" id="main-area">
        <div class="span3" id="sidebar">
			<h4>File Browser</h4><hr />
			<!-- <div id="list-file"></div> -->
			<div id="list-file" data-url="./codejump.php"></div>
			<hr/>
        </div>
        <div class="span9">
			<h3 id="label">Editing file : none</h3><hr />
            <textarea id="code" name="code"><?php
			if (isset($_GET['filename'])) {
				//echo $content; 
				echo $data;
				} else { ?>Select file to begin editing...
<?php 			} ?></textarea>
        </div>
      </div>
      
      <div class="navbar navbar-inverse navbar-fixed-bottom">
        <div class="navbar-inner">
          <p class="navbar-text">&copy; Copyright 2013 <a href="www.azophy.com">www.azophy.com</a></p>
        </div>
      </div>
	  
	  <!-- Hacky select file element for upload feature -->
	  <form action="codejump.php" method="post" enctype="multipart/form-data" style="display:none;">
	  	<input type="file" id="select_file" name="upload_file"/>
		<button type="submit" id="submit_file"></button>
	  </form>
    
<?php } else { ?>
	  
	  <div class="container">
	  	<div class="row-fluid">
	  		<div class="span4 offset4">
				<div style="background:#34495e; padding:20px;">
					<img src="./codejump-files/images/logo3-white.png" alt="CodeJump Logo" style="width:100%" />
				</div>
				<div class="share" style="padding:10px;margin-top:30px;">
					<form action="codejump.php" method="post">
						<legend>Log In</legend>
						<input type="text" class="input-block-level" name="user" placeholder="Enter your name" />
						<input type="password" class="input-block-level" name="pass" placeholder="Enter your password" />
						<button class="btn btn-block" type="submit" name="log_in">Login</button>
					</form>
				</div>
	  		</div>
	  	</div>
	  </div>

<?php } ?>
    
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
	<script src="./codejump-files/js/codemirror/codemirror.js"></script>
	<script src="./codejump-files/js/codemirror/xml.js"></script>
	<script src="./codejump-files/js/codemirror/css.js"></script>
	<script src="./codejump-files/js/codemirror/javascript.js"></script>
	<script src="./codejump-files/js/codemirror/htmlmixed.js"></script>
	<script src="./codejump-files/js/codemirror/clike.js"></script>
	<script src="./codejump-files/js/codemirror/php.js"></script>
	<!-- <script src="./codejump-files/js/codemirror/search-addon/searchcursor.js"></script>
	<script src="./codejump-files/js/codemirror/search-addon/match-highlighter.js"></script> -->

	<script src="./codejump-files/js/other-script/emmet.min.js"></script>
    <script src="./codejump-files/js/other-script/tree.jquery.js"></script>
	<script src="./codejump-files/js/other-script/library.js"></script>
	<!-- --------------------------------------------------------------- -->
        
    <script>
		var doc = CodeMirror.fromTextArea(document.getElementById("code"), {
			mode: "application/x-httpd-php",
			//mode : "text/html",
			//mode : "htmlmixed",
			lineNumbers : true,
            indentWithTabs : true,
            smartIndent : false,
			//highlightSelectionMatches: true,
			profile: 'xhtml' /* define Emmet output profile */
		});
	</script>
	
	<script>
      //disable class first
        if (!($("#menu_ren").hasClass("muted"))) {
          $("#menu_ren").addClass("muted");
          $("#menu_del").addClass("muted");
        }
      
		//<<<< FILE MODIFICATION FUNCTION >>>>		
		function load_file(f) {
		  filename = f; //alert(filename);
		  var url = "./codejump.php?filename=" + filename; 
		  jQuery.get(url, function(data) {
			//alert(data);
			doc.setValue(data);
		  });
		  $("#label").text("Editing file: " + filename);
          //enable menu
          if ($("#menu_ren").hasClass("muted")) {
            $("#menu_ren").removeClass("muted");
            $("#menu_del").removeClass("muted");
          }
		}

		function save_file(f) {
			if (confirm("Are you sure you wanted to edit '" + f + "' ?")) {
			  //filename = f;
			  var url = "./codejump.php?filename=" + f; 
			  jQuery.post(url, {save:true, code: doc.getValue()}, function(data) {
				if (data == '1') 
                  alert("File '" + f + "' successfully saved!");
                else
                  alert("Error in saving file '" + f + "'!\n" + data); 
			  });
			  load_file(f);
			}
		}
      
        function save_file_as() {
          var filename2 = prompt('Save file as ..');
          var url = "./codejump.php?is_file_exist=" + filename2; 
		  jQuery.get(url, function(data) {
            if (data == '0') {
              save_file(filename2);
              location.reload();
            } else
              alert("Error in saving file '" + filename2 + "'!\n" + data); 
		  });
		}
		
		function upload_file() {
			$("#select_file").trigger('click');
		}
		
		$("#select_file").change(function() {
			//alert($("#select_file").val());
			$("#submit_file").trigger('click');
			/*var url = "./codejump.php"; 
			jQuery.post(url, {upload_file: $("#select_file") }, function(data) {
			if (data == '1') 
			  alert("File '" + f + "' successfully saved!");
            else
			  alert("Error in saving file '" + f + "'!\n" + data); 
			});
			location.reload();*/
		});
		
		/*$(window).keypress(function(event) {
		    if (!(event.which == 115 && event.ctrlKey) && !(event.which == 19)) return true;
		    alert("Ctrl-S pressed");
		    event.preventDefault();
		    return false;
		});*/        

		$(document).keydown(function(event) {
			
			//19 for Mac Command+S
			if (( String.fromCharCode(event.which).toLowerCase() == 's' && event.ctrlKey) || (event.which == 19)) {
				//alert("Ctrl-s pressed");
				//window["save_file"](filename);
				save_file(filename);
	
				event.preventDefault();
				return false;
			}
				
			return true;
		});
      
      //tree view management
      var data = [
<?php 
$r = get_dir_structure($root_path);
$r = hierarchy_sort($r);
generate_dir_structure_JSON_code($r);
?>];      
      $('#list-file').tree({
          data: data,
          autoOpen: false,
          dragAndDrop: false
      });
	  
	  function get_node_path(a) {
          if (a.parent.name == null)
        	  return(a.name);
          else
        	  return(get_node_path(a.parent) + '/' + a.name);
	  }
	  
      $('#list-file').bind(
          'tree.select',
          function(event) {
              if (event.node) {
                  // node was selected
                  var node = event.node;
				  load_file('<?php echo $root_path;?>'+get_node_path(node));
              }
          }
      );
	</script>
  </body>
</html>
