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


function effectFadeOut(classname) {
$("#"+classname).fadeIn(800).fadeOut(800, effectFadeIn(classname))
}
function effectFadeIn(classname) {
    //alert('pippo');
    $("#"+classname).fadeOut(800).fadeIn(800, effectFadeOut(classname))
}

function testfancybox(){
  $.fancybox( '<h2>Hi!</h2><p>FancyBox Yeh</p>',
	      {
        	'autoDimensions'	: false,
		'width'         		: 350,
		'height'        		: 'auto',
		'transitionIn'		: 'none',
		'transitionOut'		: 'none'
	      } );

}

 
$(document).ready(function () {



//$("p").text("The DOM is now loaded and can be manipulated.");



f_msg=0;
$.ajax({
            type: "POST",
            async: false,
            url: "<?php echo site_url()."/welcome_portal/get_messages"; ?>",
            dataType:"jsondata",
            success: function(data){
            //alert(data);
            if(data > 0) f_msg=1;
            }
        });
if (f_msg==1) for (i=0;i<=30;i++){
                    $("#mess").fadeOut(800);
                    $("#mess").fadeIn(800);
                    }



});



</script>
<p></p>
<h4><b>Applicazioni Portale di Ortona</b></h4>
<div id="board"></div>
<!--<button onclick="testfancybox()">Fancybox</button> -->
<table id="app">
        <tr><th>APPLICAZIONI</th></tr>
        <tr> <td><?php $app_name=base_url().'missioni.php'; echo anchor($app_name, "Missioni",'class=button') ?></td> </tr>
        <tr><td> <?php $app_name=base_url().'albo.php';     echo anchor($app_name, "Repertorio Albo pretorio",'class=button'); ?></td></tr>
        <tr><td> <?php $app_name=base_url().'ordinanze.php';echo anchor($app_name, "Repertorio Ordinanze",'class=button'); ?></td></tr>
        

</table>
<br>
<table id="strumenti">
    <tr><th>STRUMENTI</th></tr>
        <tr><td id="mess"><?php $app_name=base_url().'portal.php/strumenti/index/1';echo anchor($app_name, "Messaggi",'class=button'); ?></td></tr>
        <tr><td id="rubr"><?php $app_name=base_url().'portal.php/strumenti/index/2';echo anchor($app_name, "Rubrica",'class=button'); ?></td></tr>
        <tr><td id="post"><?php $app_name=base_url().'portal.php/strumenti/index/3';echo anchor($app_name, "sticknote",'class=button'); ?></td></tr>
        <tr><td id="tempo"><?php $app_name=base_url().'portal.php/tempo';echo anchor($app_name, "tempo",'class=button'); ?></td></tr>
        <tr><td id="user"><?php $app_name='utenti'; if ($this->session->userdata('ruolo_p')=='admin')
                      echo anchor($app_name, "Gestione Utenti",'class=button'); ?></td></tr>
        <tr><td><button onclick="initialize();">Integrazione API Google Map</button></td></tr>


</table>

<input id="location" type="text" ><button id="btnloc"  onclick="calcRoute();">Calcola Percorso</button><td><tr>

<div id="map_canvas"></div>

<?php  require dirname(__FILE__).'/includes/footer.php'; ?>
