<?php

/**
 *
 */
class Locations {

  public function loadLocations() {
    // get DB connection
    $objDB = DB::getInstance();

    // prepare our input statement
    $st = $objDB->prepare('INSERT IGNORE INTO loc_location (code, name, latitude, longitude) VALUES (:code, :name, :latitude, :longitude)');

    // load the locatin map file
    $fh = fopen('data/locationMap.csv', 'r') or die('can\'t open the location map file');

    $bFirstLine = true;

    // someone threw in a sneaky twist, the file is saved on a single line so we
    // read in the file as a stream until we reach the \r character
    while ($sLine = stream_get_line($fh, 1000, "\r")) {
      // We ignore the first line returned, it contains titles
      if ($bFirstLine) {
        $bFirstLine = false;
        continue;
      }

      // trim the result, we don't need the \r character
      $sLine = rtrim($sLine, "\r");

      // turn the string into its elements
      list($sCode, $sName) = explode(',', $sLine);

      // bind the buoy code to the query
      $st->bindParam(':code', $sCode);

      // bind the name you have quaintly given the buoy
      $st->bindParam(':name', $sName);

      // I looked up the lat and long of the buoys on https://www.met.ie/marine/buoy_locations.asp
      $sLat = $this->getLatitude($sCode);
      $st->bindParam(':latitude', $sLat);
      $sLong = $this->getLongitude($sCode);
      $st->bindParam(':longitude', $sLong);

      // stick the location into the DB
      $st->execute();

      echo "<p>Added $sName to the DB</p>";
    }

    // close the location file
    fclose($fh) or die('Can\'t close the location map');
  }

  public function getLocations() {
    // get DB connection
    $objDB = DB::getInstance();

    $sQuery = "SELECT * FROM loc_location";
    $st = $objDB->query($sQuery);
    return $st->fetchAll();
  }
    /**
    * getLatitude - hardcoded array of latitude for the locations. If given time I
    * would have looked these up using the Google Map API using Geo-location
    *
    * @param  {string} $sCode location code
    * @return {string}        latitude
    */
  private function getLatitude($sCode) {
    $arrLatitude = array(
      'M1' => '53.127',
      'M2' => '53.480',
      'M3' => '51.217',
      'M4' => '55.000',
      'FS1' => '51.953', // Youghal
      'M4-Archive' => '51.555', //Skibereen
      'M5' => '51.690',
      'M6' => '52.986',
      'Belmullet-AMETS' => '54.22393',
    );

    return (isset($arrLatitude[$sCode])?$arrLatitude[$sCode]:null);
  }


  private function getLongitude($sCode) {
    $arrLongitude = array(
      'M1' => '-11.200',
      'M2' => '-5.425',
      'M3' => '-10.550',
      'M4' => '-10.000',
      'FS1' => '-7.875', // Youghal
      'M4-Archive' => '-9.270', //Skibereen
      'M5' => '-6.704',
      'M6' => '-15.866',
      'Belmullet-AMETS' => '-9.996',
    );

    return (isset($arrLongitude[$sCode])?$arrLongitude[$sCode]:null);
  }

}
?>
