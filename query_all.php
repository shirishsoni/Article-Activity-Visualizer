<?php
  //getting the dboperation class
  require_once 'connection/DbOperations.php';

  $dB_op = new DbOperation();
  
  $temp = $dB_op->getData(); 

  echo $temp;
?>