<?php
require 'model/Met.class.php';
require 'model/Locations.class.php';

// Get the date range for our data
$objData = new Met();
$objData->getDateRange();
$sDateData = json_encode(array(
  'earliest' => $objData->nEarliest,
  'latest'   => $objData->nLatest
));

// Get the location for the map
$objLocation = new Locations();
$sLocationData = json_encode($objLocation->getLocations());

echo <<<HTML
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Demonstration">
    <meta name="author" content="James McGing with the help of Bootstrap">
    <link rel="icon" href="favicon.ico">

    <title>Demonstration of data import and display</title>

    <!-- Bootstrap core CSS -->
    <link href="/css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/css/fruitbox.css" rel="stylesheet">
  </head>

  <body>

    <nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
      <a class="navbar-brand" href="#">FruitBox</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarsExampleDefault">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="/">Map<span class="sr-only">(current)</span></a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="/loadData">Load Data</a>
          </li>
        </ul>
      </div>
    </nav>

    <main role="main" class="container">

      <div id="map"></div>
      <div id="datecontainer"></div>

      <script>
        var objDates = {$sDateData};
        var arrLocations = {$sLocationData};
      </script>

    </main><!-- /.container -->

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/js/jquery.js"></script>
    <script src="/js/popper.min.js"></script>
    <script src="/js/bootstrap.min.js"></script>
    <script src="/js/fruitbox.js"></script>
    <script async defer
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA1FTh_fLmTZreh9f7Ow7T_bfQJrOOLk3k&callback=initMap">
    </script>
  </body>
</html>

HTML;
?>
