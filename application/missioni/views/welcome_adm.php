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
function test(){
    alert('ciao');
    return true;
}

function hidefields(){

   // var id=$.('#missione').val();

    //alert('hide');
    //alert($('#missione').val());
    //alert($.('#ricerca td:nth-child(2)').val());
    //alert($.('#ricerca tr:nth-child(1)').val());
    
    $('td:nth-child(2)').hide();
    $('td:nth-child(3)').hide();
    $('th:nth-child(2)').hide();
    $('th:nth-child(3)').hide();

}

function refresh(){

    $('td:nth-child(2)').show();
    $('td:nth-child(3)').show();
    $('th:nth-child(2)').show();
    $('th:nth-child(3)').show();

}
</script>

<h1><?php echo $title;  ?></h1>
<p><b>Riepilogo Missioni</b></p>
<form method="post" action='<?php echo site_url()."/mission/set_filtro" ?>'  >
    <table id="ricerca">
        <tr>

            <th>Capitolo</th>
            <th>Cognome </th>
            <th>Localita</th>
            
        </tr>
        <tr>
            <td><?php echo form_input('capitolo', $fil0)?></td>
            <td><?php echo form_input('cognome', $fil1) ?></td>
            <td><?php echo form_input('localita',$fil2) ?> </td>
        </tr>
        <tr><td><?php $par='id="ricerca" onclick="refresh();"'; echo form_submit('ricerca', 'Cerca'); ?></td></tr>
    </table>
</form>
<br>
<br>
<table>
    <tr>
        
        <th>utente</th>
        <th>Oggetto</th>
        <th>capitolo</th>
        <th>città</th>
        <th>costo</th>
        <th>Azioni</th>
    </tr>
    <?php  if($missioni === false): ?>
    <p><b>Non ci Sono Missioni</b></p>
<?php  else: ?>
<?php $i = 1; foreach($missioni as $m): ?>
    <tr>
       
        <td><?php $id=$m['id']; echo $m['cognome'].'-'.substr($m['nome'],0,3) ?></td>
        <td><?php echo $m['oggetto']?></td>
        <td><?php echo $m['capitolo']?></td>
        <td><?php echo $m['citta']?></td>
        <td><?php echo $m['costo']?></td>
        <td><?php if ( $m['stato']==1) {

                        echo
                        anchor("mission/manage/$id/cln", "Clona",'class=button').
                        anchor("mission/manage/$id/det", "Spese",'class=button').
                        anchor("mission/addmod/mod/".$id, "Modifica",'class=button').
                        anchor("mission/manage/$id/del", "Cancella", array('class' => 'button','onclick' => "return confirm('Are you sure that you want to delete ?')")).
                        anchor("mission/manage/$id/sta", "Stampa",'class=button').
                        anchor("mission/manage/$id/app", "Approva",'class=button');
                        }
                   else {
                        if ( $m['stato']==2) {
                            echo
                            anchor("mission/manage/$id/sta", "Stampa",'class=button').
                            anchor("mission/manage/$id/dis", "Disapprova",'class=button');
                        }
                        else {
                            echo anchor("mission/manage/$id/sta", "Stampa",'class=button');
                        }

                    }
        ?>
        </td>
   </tr>
    <?php $i++; endforeach ?>
   <?php  endif; ?>
</table>
<table>
    <tr> <td align="center"><?php echo 'Pagina:'.$this->pagination->create_links(); ?></td></tr>
</table>
<?php  require dirname(__FILE__).'/includes/footer.php'; ?>

