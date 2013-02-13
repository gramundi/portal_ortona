<?php

/*
 /*
 * Modulo Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    <title><?php echo $title?> </title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
    <link rel="stylesheet" href="<?php echo base_url()?>css/style_lab.css" media="screen,handheld,projection" />
    <link rel="stylesheet" href="<?php echo base_url()?>js/fancybox/jquery.fancybox-1.3.4.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url()?>css/notes.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.3/themes/base/jquery-ui.css" type="text/css" media="all" />
    <link rel="shortcut icon" href="<?php echo base_url()?>images/web/favicon.ico" />
    
    <script type="text/javascript" src="<?php echo base_url()?>js/scriptaculous.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.10/jquery-ui.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
   </head>
  <body>
  <?php if (strpos(dirname(__FILE__),'app_porsvil')) echo '<h9>SVILUPPO</h9>'; ?>
  <div id="main-div">
  <ul id="menu">
      <li><?php echo anchor('logout', 'Logout')?></li>
      <li><?php echo anchor('welcome_portal', 'Home')?></li>
    </ul>
  </div>
