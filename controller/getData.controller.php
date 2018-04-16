<?php

// If we have a third entry in the $arrURIElements it is the date being
// requested
$sDateRequested = null;
if (isset($arrURIelements[3])) {
  $sDateRequested = $arrURIelements[3];
}


require $_SERVER['CONTEXT_DOCUMENT_ROOT'].'model/Met.class.php';
$objMet = new Met();

// header('Content-Type: application/json');
echo json_encode($objMet->getDataForTime($sDateRequested));

// echo "<pre>";
// var_dump($objMet->getDataForTime($sDateRequested));
// echo "</pre>";
// echo json_encode(array('test'=>'param1'));
