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
    <link rel="shortcut icon" href="<?php echo base_url()?>images/web/favicon.ico" />
    <script type="text/javascript" src="<?php echo base_url()?>js/script.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/prototype.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/scriptaculous.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/jquery.pack.js"></script>
  </head>
  <body>
  <div id="main-div">
  <ul id="menu">
    <li><?php $logout_func=base_url().'portal.php/logout';echo anchor($logout_func, 'Logout')?></li>
    <li><?php $app_name=base_url().'portal.php/welcome_portal';echo anchor($app_name, 'Portal')?></li>
    <li><?php echo anchor('ordinanze', 'Home')?></li>
    <li><?php if ($ruolo!='normal')  echo anchor('manage/index/add', 'Aggiungi Ordinanza')?></li>
    </ul>
  </div>
  </body>