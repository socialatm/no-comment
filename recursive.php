<?php
require_once('C:/vhosts/krumo/class.krumo.php');
// $path = realpath('/etc');

/*** clean these extensions and just copy the rest	***/
$extensions = array('php', 'css', 'js');

// echo 'EXTENSIONS';

// krumo($extensions);

$objects = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

// echo 'FILE NAMES:';

foreach($objects as $name => $object){

	$full_path = $name;	/*** we may need this later	***/

	$full_path = str_replace ( "\\" , "/" , $full_path);

//    echo "$name\n<br />";

if(is_dir( $name )) {

$final = str_replace ( $source , $destination , $full_path );

// krumo($final); // die();

if(!is_dir($final)) {

if (!mkdir($final, 0, true)) { die('Failed to create folders...'); }

}

}	/*** end if(is_dir( string $name ))	***/

	if(is_file($name)) {

	$name = pathinfo($name);

	$what_to_do = in_array($name['extension'], $extensions) ? 'clean' : 'copy' ;
//	echo $what_to_do;

//    krumo($name); // die();

    if($name['basename'] == 'Thumbs.db') { continue; }

    if($what_to_do == 'copy') {

	$final = str_replace ( $source , $destination , $full_path );

	krumo($full_path);
	krumo($final);

//	die();

	if (!copy($full_path, $final)) { echo "failed to copy $file...\n";}

    }	/***	end	if($what_to_do == 'copy')	***/

    if($what_to_do == 'clean') {

$final = str_replace ( $source , $destination , $full_path );
    clean( $full_path, $final );


    }	/***	end	if($what_to_do == 'clean')	***/

    }	/***	end if(is_file($name))	***/

}	/***	end	foreach($objects as $name => $object)	***/



?>


if (!copy($file, $newfile)) { echo "failed to copy $file...\n";}