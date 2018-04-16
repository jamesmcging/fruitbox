var arrDataModel = [];

var map = {};

function initMap() {
  var ireland = {lat: 53.302821, lng: -6.716435};
  map = new google.maps.Map(document.getElementById('map'), {
    zoom: 5,
    center: ireland,
    mapTypeId: 'satellite'
  });
}

const mapController = {
  getDataForMarker: function(code) {
    for (let i in arrDataModel) {
      if (arrDataModel[i].loc_code == code) {
        return arrDataModel[i];
      }
    }

    // If we don't have data for that marker, we return an empty object
    return {
      windDirection: 'unknown',
      windSpeed: 'unknown',
      maxGustSpeed: 'unknown',
      atmosphericPressure: 'unknown'
    };
  },

  addMarkers: function() {
    for (let i in arrLocations) {
      let location = {
        lat: parseFloat(arrLocations[i].latitude),
        lng: parseFloat(arrLocations[i].longitude)
      };

      let objMetData = mapController.getDataForMarker(arrLocations[i].code);

      let marker = new google.maps.Marker({
        position: location,
        map: map,
        title: arrLocations[i].code + '(' + arrLocations[i].name + ')'
      });

      let eleContent = '<div>';
      eleContent += '  <div class="name">' + arrLocations[i].code + '</div>';
      eleContent += '  <div class="latlng">';
      eleContent += 'location: ' + arrLocations[i].latitude;
      eleContent += ', ' + arrLocations[i].longitude;
      eleContent += '  </div>';
      eleContent +=
        '  <div class="windDirection">Wind direction: ' +
        objMetData.windDirection +
        '</div>';
      eleContent +=
        '  <div class="windSpeed">Wind speed: ' +
        objMetData.windSpeed +
        '</div>';
      eleContent +=
        '  <div class="maxGustSpeed">Max Gust: ' +
        objMetData.maxGustSpeed +
        '</div>';
      eleContent +=
        '  <div class="atmosphericPressure">Pressure: ' +
        objMetData.atmosphericPressure +
        '</div>';
      eleContent += '</div>';
      let infowindow = new google.maps.InfoWindow({
        content: eleContent
      });

      marker.addListener('mouseover', function() {
        infowindow.open(map, marker);
      });

      marker.addListener('mouseout', function() {
        infowindow.close(map, marker);
      });
    }
  }
};

const dateSelector = {
  setup: function() {
    let self = this;

    // Build range input and attach to the DOM
    var ele = document.createElement('input');
    ele.type = 'range';
    ele.id = 'dateselector';
    ele.min = objDates.earliest;
    ele.max = objDates.latest;
    ele.value = objDates.latest;
    ele.step = 3600; // an hour repreenting the increments in the met data
    document.getElementById('datecontainer').appendChild(ele);

    document
      .getElementById('datecontainer')
      .appendChild(document.createElement('br'));

    // Give the user feedback as to which date they have picked
    var ele = document.createElement('p');
    ele.id = 'dateinfo';
    ele.innerHTML = 'use the slider to select date';
    document.getElementById('datecontainer').appendChild(ele);

    document
      .getElementById('datecontainer')
      .appendChild(document.createElement('br'));

    // Add a listener to the date selector
    document
      .getElementById('dateselector')
      .addEventListener('mouseup', function() {
        // Display the date selected using the slider
        document.getElementById('dateinfo').innerHTML = new Date(
          this.value * 1000
        ).toString();
        // ask the server for data for this date
        dateSelector.getData(this.value);
      });
  },

  getData: function(timestamp) {
    let sURL = 'http://localhost/getData/' + timestamp;

    var jqxhr = $.get(sURL)
      .done(function(data) {
        arrDataModel = JSON.parse(data);
        mapController.addMarkers();
      })
      .fail(function() {
        alert('Failed to load data for this date');
      });
  }
};

$(document).ready(function() {
  dateSelector.setup();
});
