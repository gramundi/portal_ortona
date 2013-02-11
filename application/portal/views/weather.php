<?php  require dirname(__FILE__).'/includes/head.php'; ?>
<div class="meteo" >
        <p><?php print $city; ?></p>
        <div class="citta">
            <img src="<?php echo'http://www.google.com'.$icon ?>" alt="weather" />
            <span class="condition"> <?php echo $temp ?>&deg; F,
            <b><?php echo $condition ?></b>
            </span>
        </div>
        <p>Forecast</p>
        <?php foreach ($forecast_list as $forecast) : ?>
        <div class="weather">
            <img src="<?php echo 'http://www.google.com'.$forecast->icon['data']; ?>" alt="weather" />
            <b><?php echo $forecast->day_of_week['data']; ?>
               <?php echo $forecast->low['data'] ?>&deg; F -
               <?php echo $forecast->high['data'] ?>&deg; F,
	       <?php echo $forecast->condition['data'] ?></b>
            
        </div>
        <?php endforeach ?>
</div>
    </body>
</html>