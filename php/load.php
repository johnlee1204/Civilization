
<?php
  if(isset($_GET['name']))
    $name = $_GET['name'];
    
    $inputStream = fopen('../Maps/'.$name.'.txt','r');
    $dataToSend = fread($inputStream,8192);
    echo $dataToSend;