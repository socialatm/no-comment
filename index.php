<?php

if(!empty($_POST)) {

require_once('C:/vhosts/krumo/class.krumo.php');

// echo 'SOURCE';

$source = pathinfo($_POST['file']);
$destination = pathinfo($_SERVER['SCRIPT_FILENAME']);
$destination = $destination['dirname'].'/'.$source['filename'].'-no-comment';

$destination = str_replace ( "\\" , "/" , $destination);
$source = str_replace ( "\\" , "/" , $source['dirname']);

// krumo($source);

// echo 'DESTINATION';
// krumo($destination);

//die();

require_once("./recursive.php");

}	/***	end	if(!empty($_POST))	***/



/*
* T_ML_COMMENT does not exist in PHP 5.
* The following three lines define it in order to
* preserve backwards compatibility.
*
* The next two lines define the PHP 5 only T_DOC_COMMENT,
* which we will mask as T_ML_COMMENT for PHP 4.
*
* see: http://php.net/manual/en/function.file.php#refsect1-function.file-examples
*/
if (!defined('T_ML_COMMENT')) {
   define('T_ML_COMMENT', T_COMMENT);
} else {
   define('T_DOC_COMMENT', T_ML_COMMENT);
}

function clean( $source, $destination ) {

krumo($source);
krumo($destination);

// die('WE ARE HERE');

$source = file_get_contents($source);

$code = '';

$tokens = token_get_all($source);

/***	this strips the coments	***/

foreach ($tokens as $token) {
   if (is_string($token)) {
       // simple 1-character token

       $code .= $token;

   } else {
       // token array
       list($id, $text) = $token;

       switch ($id) {
           case T_COMMENT:
   //        case T_ML_COMMENT: // we've defined this
           case T_DOC_COMMENT: // and this
               // no action on comments
               break;

           default:
               // anything else -> output "as is"

               $code .= $text;
               break;
       }
   }
}	/*** end foreach ($tokens as $token)	***/

/*** and then write it to the new file	***/

if(!$f = fopen($destination,"w")){die('Unable to open '.$destination);}
   fwrite($f,$code);
   fclose($f);

/*** ok the new file saved so lets get it and strip any multiple blank lines down to just one blank line	***/

$new_page = '';
$lines = file($destination);

// echo count($lines); we could put a progress bar using this

foreach ($lines as $line_num => $line) {

$line = rtrim($line);

$empty = empty($line) ? '': htmlspecialchars($line);

if ((empty($line)) and (empty($previous_line)) ) { continue; }


//   echo "Line #<b>{$line_num}</b> : " . htmlspecialchars($line) . "<br />\n";

//   echo "Line #<b>{$line_num}</b> : " . $empty . "<br />\n";


//   echo htmlspecialchars($line) . "<br />\n";

$previous_line = $line;

// $line = $line."\r\n";

$new_page .= $line."\r\n";

}	/***	end foreach ($lines as $line_num => $line)	***/

if(!$f = fopen($destination,"w")){die('Unable to open '.$destination);}
   fwrite($f,trim($new_page));
   fclose($f);

}	/***	end function clean()	***/

?>



<form action="" method="post" enctype="multipart/form-data">
<label for="file">Enter the complete path and name for any file in the Directory you want to Process:</label>
<br />
<input type="text" name="file" id="file" />
<br />
<input type="submit" id="blah" name="blah" value="Submit" />
</form>
