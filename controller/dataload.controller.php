<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
// phpinfo();
// print_r($_SERVER);
// if ($_SERVER['REQUEST_METHOD'] == 'GET') {
//   $sHTML = <<<HTML
//     <form method="post" action="{$_SERVER['SCRIPT_NAME']}" enctype="multipart/form-data">
//       <input type="file" name="document"/>
//       <input type="submit" value="Send file"/>
//     </form>
// HTML;
// echo $sHTML;
// } else {
//   echo "<pre>"; print_r($_FILES); echo "</pre>";
//   // var_dump($_FILES);
//   if (isset($_FILES['document']) && ($_FILES['document']['error'] == UPLOAD_ERR_OK)) {
//     $sNewPath = $_SERVER['DOCUMENT_ROOT'].'/fruitbox_dataload/files/'.basename($_FILES['document']['name']);
//     if (move_uploaded_file($FILES['document']['tmp_name'], $sNewPath)) {
//       echo "File save in $sNewPath\n";
//     } else {
//       echo "Couldn't move file to $sNewPath\n";
//     }
//   } else {
//     echo "No valid file uploadedn\n";
//   }
// }

require $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/fruitbox/model/DBSetup.class.php';
require $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/fruitbox/model/Locations.class.php';
require $_SERVER['CONTEXT_DOCUMENT_ROOT'].'/fruitbox/model/Met.class.php';

echo '<h2>Data Load</h2><hr>';

$objDB = new DBSetup();
$objDB->buildLocationsTable();
$objDB->buildMetTable();

$objLocations = new Locations();
$objLocations->loadLocations();

$objMet = new Met();
$objMet->loadMetData();

echo '<a href="/fruitbox/">Link to map</a>';
?>
