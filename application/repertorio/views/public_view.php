<?php

/*
 * Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

 ?>
<?php  require dirname(__FILE__).'/includes/head_public.php'; ?>
<script type="text/javascript">

</script>

<body>
    <div id="maindiv">    
<h1><?php echo $title;  ?></h1>
<h2><b>Registro pubblico degli atti in albo pretorio del comune di  ORTONA</b></h2>
<form id="filtrorep" method="post" action='<?php echo site_url()."/albo/set_filtro" ?>'  >
    <table id="ricerca">
        <tr>
            <th>richiedente</th>
            <th>oggetto</th>
            <th>tipo</th>
           
        </tr>
        <tr>
            <td><?php echo form_input('ente',$fil1)?> </td>
            <td><?php echo form_input('oggetto',$fil2)?> </td>
            <td><?php echo form_input('tipo',$fil4)?> </td>
        </tr>
        <tr><td><?php $par='id="ricerca" onclick="refresh();"'; echo form_submit('ricerca', 'Cerca'); ?></td></tr>
    </table>
</form>
<br>
<br>
<div id="dati-rep-pub">
    <div id="rowhead">
        <div id="cell">oggetto</div>
        <div id="cell">ente</div>
        <div id="cell">tipo</div>
         
    </div>
    <?php  if($registro === false): ?>
    <p><b>Non ci Sono registrazioni</b></p>
<?php  else: ?>
<?php $i = 1;foreach($registro as $m): ?>
    <div id="rowel"><?php $id=$m['id']; ?>
        <div id="cell"><?php echo $m['oggetto'] ?></div>
        <div id="cell"><?php echo $m['ente'] ?></div>
        <div id="cell"><?php echo $m['tipo'] ?></div>
        <div id="cell"><button>Visualizza Atto</button></div>
    </div>    
    <?php $i++; endforeach ?>
   <?php  endif; ?>
</div>
<br>
    
<?php  require dirname(__FILE__).'/includes/footer.php'; ?>
</div>
</body>
