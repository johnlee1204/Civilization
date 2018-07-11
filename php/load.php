<<<<<<< HEAD
<?php
  if(isset($_GET['name']))
    $name = $_GET['name'];
    
    $inputStream = fopen('../Maps/'.$name.'.txt','r');
    $dataToSend = fread($inputStream,8192);
    echo $dataToSend;
  
  
=======
<?php
  if(isset($_GET['name']))
    $name = $_GET['name'];
    
    $inputStream = fopen('../Maps/'.$name.'.txt','r');
    $dataToSend = fread($inputStream,8192);
    echo $dataToSend;
  
  
>>>>>>> 202ef54839033042ef5ca7abbb7fe11b4d9c5583
?>