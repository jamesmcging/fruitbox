<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
require 'model/db.class.php';


/**
 * This isn't a fully fledged front-controller, it only handles the following
 * resources:
 *
 *   - /
 *   - /getData/DATE
 *   - /loadData
 */


// Examine the $_SERVER['REQUEST_URI'] by splitting on /
$arrURIelements = explode('/', $_SERVER['REQUEST_URI']);

if (isset($arrURIelements[2])) {

  // The primary resource is the second entry in the $arrURIElements
  $sResource = $arrURIelements[2];
}

switch ($sResource) {
  case 'getData':
    require 'controller/getData.controller.php';
    break;

  case 'loadData':
    require 'controller/dataload.controller.php';
    break;

  default:
    require 'view/map.view.php';
}
