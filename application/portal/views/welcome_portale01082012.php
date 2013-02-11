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

function reset_area_cont(){
$('#meteo').hide();


}

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

    reset_area_cont();
    $('#map_canvas').show();
    $('#location').show();
    $('#btnloc').show();

  }

function calcRoute() {
   
    var start = "ortona ";
    var end =$('#dst').val();
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



//$("p").text("The DOM is now loaded and can be manipulated.");
 

$(document).ready(function () {
//Gestione Messaggi Utenti
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
<div id="board"></div>
<!--<button onclick="testfancybox()">Fancybox</button> -->

<div class="sin">

        <h5>APPLICAZIONI</h5>
        <div>
        <span><?php $app_name=base_url().'missioni.php'; echo anchor($app_name, "Missioni",'class=button') ?></span>
        </div>
        <br>

        <div><span><?php $app_name=base_url().'albo.php';  echo anchor($app_name, "Repertorio Albo pretorio",'class=button'); ?></span>
        </div>
        <br>

        <div>
        <span><?php $app_name=base_url().'ordinanze.php';echo anchor($app_name, "Registro Ordinanze",'class=button'); ?></span>
        </div>
        <br>
        <div>
        <span><?php $app_name=base_url().'agenda.php';echo anchor($app_name, "Agenda Appuntamenti",'class=button'); ?></span>
        </div>
        <br>

    <h5>STRUMENTI</h5>
        
        <div id="mess"><?php $app_name=base_url().'portal.php/strumenti/index/1';echo anchor($app_name, "Messaggi",'class=button'); ?></div>
        <br>
        <div id="rubr"><?php $app_name=base_url().'portal.php/strumenti/index/2';echo anchor($app_name, "Rubrica",'class=button'); ?></div>
        <br>
        <div id="post"><?php $app_name=base_url().'portal.php/strumenti/index/3';echo anchor($app_name, "Post-it Management",'class=button'); ?></div>
        <br>
        <div id="user"><?php $app_name='utenti'; if ($this->session->userdata('ruolo_p')=='admin')
                      echo anchor($app_name, "Gestione Utenti",'class=button'); ?></div>
        <br>
        <div id="acce"><?php $app_name=base_url().'portal.php/strumenti/index/4'; if ($this->session->userdata('ruolo_p')=='admin')
                      echo anchor($app_name, "Log Accessi",'class=button'); ?></div>
        <br>

        

</div>

<div class="des">
<form method="post" action='<?php echo site_url()."/welcome_portal/previsioni" ?>'  >
    <h5>Servizio Meteo
    </n></n></n></n>                                Inserisci Luogo[es.Roma]</h5>
    <div><?php $par = 'id="loc" name="loc"'; echo form_input('loc','',$par)?></div>
    <div id="tempo"><?php echo form_submit('previsioni', 'previsioni'); ?></div>
    
</form>
<div> <button onclick="initialize();">Google Map</button></div>
</div>


<div class="mid">

    <div id="meteo">
    <?php if ($f_prev==1) : ?>
        <b><?php print $city; ?></b>
        <div class="citta">
            <br>
            <img src="<?php echo'http://www.google.com'.$icon ?>" alt="weather" />
            <span class="condition">
            <b><?php echo $temp ?>&deg;C,
            <?php echo $condition ?></b>
            </span>
        </div>
        <h5>PREVISIONI PROSSIMI GIORNI</h5>
        <?php foreach ($forecast_list as $forecast) : ?>
        <div class="weather">
            <img src="<?php echo 'http://www.google.com'.$forecast->icon['data']; ?>" alt="weather" />
            <b>
               <?php echo $forecast->day_of_week['data']; ?>
               <br>
               <label>Minima:<?php echo $forecast->low['data'] ?>&degC</label>
               <label>Massima:<?php echo $forecast->high['data'] ?>&degC</label>
               Previsione:<?php echo $forecast->condition['data'] ?><br>
               <br>
        </div>
        <?php endforeach ?>

        <?php endif ?>
</div>

<div style="display:none"   id="location" ><b>inserisci destinazione</b><input id="dst" type="text" /></div>
<div style="display:none" id="btnloc" ><button   onclick="calcRoute();">Visualizza Itinerario</button></div>
<div id="map_canvas"></div>


</div>

<?php  require dirname(__FILE__).'/includes/footer.php'; ?>
