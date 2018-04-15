# fruitbox, a simple PHP visualisation using the Google Map API

## Overview

A simple non-framework PHP app that is charged with entering two files into a
DB then presenting the data within these files using a JS driven front end.

The PHP app is set up using a front controller pattern, leveraging Apache to
drive all requests through a single script which calls the relevant controller.
Each controller then accesses the model as required.

There is a single view, the map page.

## Entering the Data

The data is presented in two files, one describing locations and the other
describing met data at those locations. The data isn't clean:

* Some locations have multiple data entries for the same time;
* Some times don't have data for each location;

The weather data file is large and takes a while to parse into the DB. This is
done using a prepared PDO statement with bound parameters.

The locations didn't come with lat and long so this was obtained from
https://www.met.ie/marine/buoy_locations.asp

The database structure can be seen in model/DBSetup.class.php

## Visualising the Data

The data is presented to the user using the Google Map API. We load the map of
Ireland and a slider. The slider can be used by the user to select a date.
The app fetches the data for that date from the DB using ajax and draws markers
for each location. Where data exists for a marker, it is displayed on mouse
over.

To simplify date handling, the app uses unix_timestamps in the code, converting
them to date and time for the user.

## Stretch Goal and Known Issues

The app needs a more granular way of moving the slider, perhaps events
listening for the arrow keys.

JS is being overly clever and converting the times to the users local time.
