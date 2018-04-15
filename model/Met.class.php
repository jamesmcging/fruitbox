<?php
/**
 *
 */
class Met {

  public $nEarliest;
  public $nLatest;

  public function loadMetData() {
    // Remove the time limit on the query, this data load takes a bit of time...
    set_time_limit(0);

    // get DB connection
    $objDB = DB::getInstance();

    // prepare our input statement
    $sQuery = '
      INSERT IGNORE INTO met_meteorologicaldata
      (
        loc_code,
        `time`,
        atmosphericPressure,
        windDirection,
        windSpeed,
        maxGustSpeed
      )
      VALUES
      (
        :code,
        :time,
        :atmosphericPressure,
        :windDirection,
        :windSpeed,
        :maxGustSpeed
      )
    ';
    $st = $objDB->prepare($sQuery);

    // load the locatin map file
    $fh = fopen('data/locationData.csv', 'r') or die('can\'t open the met data file');

    $nCount = 0;

    echo "<p>Reading met data into met_meteorologicaldata. This takes about 10 minutes.</p>";
    // someone threw in a sneaky twist, the file is saved on a single line so we
    // read in the file as a stream until we reach the \r character
    while ($sLine = stream_get_line($fh, 1000, "\r")) {
      $nCount++;

      // We ignore the first line returned, it contains titles
      if ($nCount === 1) {
        continue;
      }

      // trim the result, we don't need the \r character
      $sLine = rtrim($sLine, "\r");

      // turn the string into its elements
      list($sCode, $sTime, $fAtmosphericPressure, $nWindDirection, $fWindSpeed, $fMaxGustSpeed) = explode(',', $sLine);

      // bind the buoy code to the query
      $st->bindParam(':code', $sCode);

      // We store the time as a unix timestamp
      $st->bindParam(':time', $sTime);

      $st->bindParam(':atmosphericPressure', $fAtmosphericPressure);

      $st->bindParam(':windDirection', $nWindDirection);

      $st->bindParam(':windSpeed', $fWindSpeed);

      $st->bindParam(':maxGustSpeed', $fMaxGustSpeed);

      // stick the location into the DB
      $st->execute();

    }
    echo "<p>Added $nCount entries to met_meteorologicaldata</p>";

    // close the location file
    fclose($fh) or die('Can\'t close the location data file');
  }

  public function getDataForTime($sTime) {
    $sQuery = "
      SELECT *
      FROM met_meteorologicaldata
      WHERE time = FROM_UNIXTIME(:time)
    ";

    // get DB connection
    $objDB = DB::getInstance();

    $st = $objDB->prepare($sQuery);

    $st->bindParam(':time', $sTime);

    $st->execute();

    $result = $st->fetchAll();

    return $result;
  }


  /**
   * getDateRange - Function charged with populating nEarliest and nLatest with
   * unix timestamps representing the earliest and the latest dates in the DB
   *
   * @return {bool}
   */
  public function getDateRange() {
    // get DB connection
    $objDB = DB::getInstance();

    $sQuery = 'select max(time) as latest, min(time) as earliest from met_meteorologicaldata';
    // stick the location into the DB
    $st = $objDB->query($sQuery);
    $arrDates = $st->fetchAll();

    $this->nEarliest = strtotime($arrDates[0]['earliest']);
    $this->nLatest   = strtotime($arrDates[0]['latest']);

  }
}
?>
