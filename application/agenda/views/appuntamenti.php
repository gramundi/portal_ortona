<?php

/*
 * Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

 ?>
<?php  require dirname(__FILE__).'/includes/head.php'; ?>
<script type="text/javascript">
$(document).ready(function() {
    $("#datepicker").datepicker();
    $("#datepicker").datepicker( "option", "dateFormat", "dd/mm/yy" );
  });
</script>

<h1><?php echo $title;  ?></h1>
<form method="post" action='<?php echo site_url()."/riepilogoapp/set_filtro" ?>'  >
    <table id="ricerca">
        <tr>
            <th>richiedente</th>
            <th>titolare</th>
            <th>Data Appuntamento</th>
            
        </tr>
        <tr>
            <td><?php echo form_input('richiedente',$fil1)?> </td>
            <td><?php echo form_input('titolare',$fil2)?> </td>
            <td><?php $js='id="datepicker"'; echo form_input('dataapp',$fil3,$js)?></td>
            

        </tr>
        <tr><td><?php $par='id="ricerca" onclick="refresh();"'; echo form_submit('ricerca', 'Cerca'); ?></td></tr>
    </table>
</form>
<br>
<br>
<table id="dati">
    <tr>
        <th>richiedente</th>
        <th>titolare</th>
        <th>tipo</th>
        <th>data</th>
        <th>ora</th>
        <th>oggetto</th>
        <th>azioni</th>
        
    </tr>
    <?php  if($appuntamenti === false): ?>
    <p><b>Non ci Sono Appuntamenti</b></p>
<?php  else: ?>
<?php $idcoll=0;$i = 1;foreach($appuntamenti as $m): ?>
    <?php if ($idcoll!=$m['id_appuntamento']):?>
    <tr><?php $id=$m['id_appuntamento'] ?>
        <td><?php echo $m['richiedente'] ?> </td>
        <td><?php echo $m['titolari'] ?></td>
        <td><?php echo $m['tipo'] ?></td>
        <td><?php echo $m['data'] ?></td>
        <td><?php echo $m['oramin'] ?></td>
        <td><?php echo $m['oggetto'] ?></td>
        <td><?php if ($m['tipo']=='C') {
                           $butTit=anchor("riepilogoapp/index/titolari/".$id.'/'.$m['oggetto'], "Titolari",'class=button');
                           $idcoll=$m['id_appuntamento'];
                           }
                  else $butTit='';
                  $sp=explode(':',$m['oramin']);
                  $gior=substr($m['data'],0,2);
                  $mese=substr($m['data'],3,2);
                  $anno=substr($m['data'],6,4);
                  $time=$gior.$mese.$anno.$sp[0].$sp[1];
                  echo   
                  anchor("riepilogoapp/index/cancella/".$id, "Cancella", array('class' => 'button', 'onclick'=>"return confirm('Are you sure that you want to delete ?')")).
                  anchor("riepilogoapp/index/notifica/".$id.'/'.$time.'/'.$m['richiedente'].'/'.$m['descrizione'], "Notifica", 'class=button').$butTit;
                       //anchor("riepilogoapp/index/delega/".$id.'/'.$m['data'].'/'.$sp[0].'/'.$sp[1], "Delega",   'class=button');

 
                            ?>
                </td>
        </tr>
     <?php else: ?>
     <?php endif; ?>
    <?php $i++; endforeach ?>
   <?php  endif; ?>
</table>
<h6><?php echo 'filtrati '.$num_rows_fil.' su '.$num_rows.' elementi totali' ?></h6>
<table>
    <tr> <td align="center"><?php echo 'Pagina:'.$this->pagination->create_links(); ?></td></tr>
</table>
<?php  require dirname(__FILE__).'/includes/footer.php'; ?>
