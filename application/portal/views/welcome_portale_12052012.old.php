<?php

/*
 * Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

 ?>
<?php  require dirname(__FILE__).'/includes/head.php'; ?>

<script type="text/javascript" src="//maps.googleapis.com/maps/api/js?sensor=true"></script>
<script type="text/javascript">

  var directionDisplay;
  var directionsService = new google.maps.DirectionsService();
  var map;

  function initialize() {
    directionsDisplay = new google.maps.DirectionsRenderer();
    var latlng = new google.maps.LatLng(42.20, 14.24);
    var myOptions = {
      zoom: 8,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
    directionsDisplay.setMap(map);
    $('#map_canvas').show();
    $('#location').show();
    $('#btnloc').show();
  }

function calcRoute() {
   
    var start = "ortona ";
    var end =$('#location').val();
    var request = {
        origin:start,
        destination:end,
        travelMode: google.maps.DirectionsTravelMode.DRIVING
    };
    directionsService.route(request, function(response, status) {
      if (status == google.maps.DirectionsStatus.OK) {
        directionsDisplay.setDirections(response);
      }

    });
  }
</script>

<h4><b>Applicazioni Portale di Ortona</b></h4>
<table id="ricerca">
        <tr> <td><?php $app_name=base_url().'missioni.php'; echo anchor($app_name, "Missioni",'class=button') ?></td> </tr>
        <tr><td> <?php $app_name=base_url().'albo.php';     echo anchor($app_name, "Repertorio Albo pretorio",'class=button'); ?></td></tr>
        <tr><td> <?php $app_name=base_url().'ordinanze.php';echo anchor($app_name, "Repertorio Ordinanze",'class=button'); ?></td></tr>
        <tr><td><?php $app_name='utenti'; if ($this->session->userdata('ruolo_p')=='admin')
                      echo anchor($app_name, "Gestione Utenti",'class=button'); ?></td></tr>
        <tr><td><button onclick="initialize();">Integrazione API Google Map</button></td></tr>


</table>
<input id="location" type="text" ><button id="btnloc"  onclick="calcRoute();">Calcola Percorso</button><td><tr>

<div id="map_canvas"></div>

<?php  require dirname(__FILE__).'/includes/footer.php'; ?>
