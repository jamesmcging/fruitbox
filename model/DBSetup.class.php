<?php
require_once 'db.class.php';

class DBSetup {

  private $objDB;

  public function __construct() {
    $this->objDB = DB::getInstance();
  }

  public function buildLocationsTable() {
    // See if loc_location has alrteady been created
    $sQuery = "
      SELECT *
      FROM information_schema.tables
      WHERE table_schema = 'fruitbox'
          AND table_name = 'loc_location'
      LIMIT 1";
    $st = $this->objDB->query($sQuery);
    $arr = $st->fetchAll();

    if (count($arr) === 1) {
      echo "<p>loc_location already exists</p>";

    } else {
      $sQuery = "
        CREATE TABLE IF NOT EXISTS loc_location
        (
          code        varchar(20) PRIMARY KEY,
          name        varchar(50),
          latitude    decimal(6, 3) DEFAULT 000.000,
          longitude   decimal(6, 3) DEFAULT 000.000
        ) ENGINE=InnoDB;
      ";

      // Add the locations table
      if ($this->objDB->exec($sQuery) !== false) {
        echo "<p>Created table loc_location</p>";
      } else {
        echo '<p>Unable to create loc_location table</p>';
      }
    }
  }

  public function buildMetTable() {
    // See if met_meteorologicaldata has alrteady been created
    $sQuery = "
      SELECT *
      FROM information_schema.tables
      WHERE table_schema = 'fruitbox'
          AND table_name = 'met_meteorologicaldata'
      LIMIT 1";
    $st = $this->objDB->query($sQuery);
    $arr = $st->fetchAll();

    if (count($arr) === 1) {
      echo "<p>met_meteorologicaldata already exists</p>";

    } else {
      $sQuery = "
        CREATE TABLE IF NOT EXISTS met_meteorologicaldata
        (
           id                      int(10) UNSIGNED PRIMARY KEY AUTO_INCREMENT,
           loc_code                varchar(20),
           `time`                  timestamp,
           atmosphericPressure     decimal(7, 3) UNSIGNED,
           windDirection           smallint(5) UNSIGNED,
           windSpeed               decimal(6, 3) UNSIGNED,
           maxGustSpeed            decimal(6, 3) UNSIGNED,
           FOREIGN KEY (loc_code) REFERENCES fruitbox.loc_location(code),
           UNIQUE KEY uk_code_time(loc_code, `time`)
        ) ENGINE=InnoDB
      ";

      // Add the met data table
      if ($this->objDB->exec($sQuery) !== false) {
        echo "<p>Created table met_meteorologicaldata</p>";
      } else {
        echo '<p>Unable to create met_meteorologicaldata table</p>';
      }
    }
  }
}
