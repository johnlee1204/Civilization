<?php
  if(isset($_GET['width']))
    $width = $_GET['width'];
  if(isset($_GET['height']))
    $height = $_GET['height'];
  if(isset($_GET['map']))
    $data = $_GET['map'];
  if(isset($_GET['name']))
    $name = $_GET['name'];
    
    $outputStream = fopen('../Maps/'.$name.'.txt','w');
    $stringFormOfMap=$width.','.$height.','.$data;
    fwrite($outputStream,$stringFormOfMap);
    fclose($outputStream);
  
	echo "<script>window.close();</script>";
