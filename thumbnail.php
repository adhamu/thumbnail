<?php

	/* Parameters */
	$path   = $_GET["img"];
	$width  = $_GET["width"];
	$height = $_GET["height"];
	$type   = $_GET['type'];

	/* Process Thumbnail */
	include 'Thumbnail.class.php';

	$thumbnail = new Thumbnail($path, $width, $height, $type);
	echo $thumbnail->getImg();

?>