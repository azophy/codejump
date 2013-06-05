<?php
//==================================
//	SECURITY && SESSION MANAGEMENT
//==================================

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

function handle_magic() {
  if(function_exists('set_magic_quotes_runtime'))
	@set_magic_quotes_runtime(0);
  
  if((function_exists('get_magic_quotes_gpc') && @get_magic_quotes_gpc() == 1) || @ini_get('magic_quotes_sybase')) {
	$_COOKIE = remove_magic($_COOKIE);
	$_GET = remove_magic($_GET);
	$_POST = remove_magic($_POST);
	$_REQUEST = remove_magic($_REQUEST);
  }
}

function is_logged_in() {
	return (isset($_SESSION['logged_in']) && $_SESSION['logged_in']);
}


//==================================
//	TREE VIEW BROWSER
//==================================

//code modified from source: http://www.php.net/manual/en/class.directoryiterator.php && http://php.net/manual/en/class.recursivedirectoryiterator.php
function get_dir_structure() {
  $ritit = new RecursiveIteratorIterator(new RecursiveDirectoryIterator('.',FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST); 
  $ar = array(); 
  foreach ($ritit as $splFileInfo) { 
	 $path = $splFileInfo->isDir() 
		   ? array($splFileInfo->getFilename() => array()) 
		   : array($splFileInfo->getFilename()); 
  
	 for ($depth = $ritit->getDepth() - 1; $depth >= 0; $depth--) { 
		 $path = array($ritit->getSubIterator($depth)->current()->getFilename() => $path); 
	 } 
	 $ar = array_merge_recursive($ar, $path); 
  }
  return $ar;
}

function generate_dir_structure_JSON_code($rr) {
  $first=true;
  foreach ($rr as $key => $val) {
    if (is_array($val)) {
      if ($key[0] != '.') {
	    if ($first) $first=false; else echo ',';
        echo "{label:'",$key,"', children: [";
        generate_dir_structure_JSON_code($val);
        echo "]}";
      }
    } else {      
      if ($val[0] != '.' && $val[strlen($val)-1] != '~') {
        if ($first) $first=false; else echo ',';
        echo "{label:'",$val,"'}";
      }
    }
  }
}
?>