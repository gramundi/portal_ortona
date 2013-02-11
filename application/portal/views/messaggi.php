<?php

/*
 * Gestione Rubrica
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

 ?>
<?php  require dirname(__FILE__).'/includes/head.php'; ?>
<script type="text/javascript">

function get_msg( row,id_mitt,testo ){

$('#messaggi').hide();
$('#ricerca').hide();
$('#pag').hide();






var rif=$('#messaggi tr:eq(' + row + ') td:eq(0)').text();
var mitt=$('#messaggi tr:eq(' + row + ') td:eq(2)').text();
var oggetto=$('#messaggi tr:eq(' + row + ') td:eq(3)').text();


$('#dest').hide();
$('#rif').val(rif);
$('#id_mitt').val(id_mitt);
$('#mitt').val(mitt);
$('#oggetto').val(oggetto);
$('#testo').val(testo)
$('#messaggio').show();
$('#risposta').hide();
$('#rispondi').show();
$('#invia').hide();
$('#annulla').hide();

//Call AJAX to change stato
$.ajax({
                type: "POST",
                async: false,
                url: "<?php echo site_url()."/strumenti/upd_stato/"; ?>"+"upd/"+rif,
                dataType:"jsondata",
                success: function(data){

                //alert(data);
                                }
            });


}

function riep_mess(){

$('#messaggio').hide();
$('#messaggi').show();
$('#ricerca').show();
$('#pag').show();


}

function rispondi(){

$('#rispondi').hide();
$('#risposta').show();
$('#invia').show();

}

function InviaMsg(op) {


if (op=='ok'){
     oggetto=$('#oggetto').val();
     testo=$('#risposta').val();
     //Il destinatario diventa il mittente del messaggio corrente
     id_dest=$('#id_mitt').val();
     rif=$('#rif').val();
     //alert('InviaMsg:'+id_dest+'ogg-'+oggetto+testo+rif);
     $.ajax({
                type: "POST",
                async: false,
                url: "<?php echo site_url()."/strumenti/invia"; ?>",
                data: "par="+id_dest+'-'+oggetto+'-'+testo+'-'+rif,
                dataType:"jsondata",
                success: function(data){

                //alert(data);
                alert('Messaggio Inviato con Successo');
                }
            });
  
}
$('#messaggio').hide();
$('#messaggi').show();
$('#ricerca').show();
$('#pag').show();


}





</script>




<form method="post" action='<?php echo site_url()."/strumenti/set_filtro/messaggi" ?>'  >
    <table id="ricerca">
        <tr>
            <th>Mittente</th>
            <th>Oggetto</th>
        </tr>
        <tr>
            <td><?php echo form_input('mittente',$fil1)?> </td>
            <td><?php echo form_input('oggetto',$fil2); ?> </td>
        </tr>
        <tr><td><?php echo form_submit('ricerca', 'Cerca'); ?></td></tr>
    </table>
</form>
<br>
<br>

 <table id="messaggio" style="display:none">
        <input type="hidden" id="rif"     name="rif" >
        <input type="hidden" id="id_mitt" name="id_mitt" >
        <tr><th>Mittente</th><td><?php $par = 'readonly="readonly" id="mitt"';echo form_input('mitt','', $par) ?></td></tr>
        <tr><th>Destinatario</th><td><?php $par = 'readonly="readonly" id="dest"';echo form_input('dest','', $par) ?></td></tr>
        <tr><th>Oggetto</th><td><?php $par='readonly="readonly" id="oggetto"'; echo form_input('oggetto','',$par)?> </td></tr>
        <tr><th>Testo</th><td><?php $par='readonly="readonly" id="testo"';echo form_textarea('text','',$par); ?> </td></tr>
        <tr><th>Risposta</th><td><?php $par='id="risposta"';echo form_textarea('risp','',$par); ?> </td></tr>
        <tr><td></td><td>
                <button id="riepmess" onclick="riep_mess()">Riepilogo Messaggi</button>
                <button id="rispondi" onclick="rispondi()">Rispondi</button>
                <button id="invia" onclick="InviaMsg('ok')">Invia</button>
                <button id="annulla" onclick="Annulla('ko')">Annulla</button>
            </td>
        </tr>
    </table>

<table id="messaggi">
    <tr>
        <th>id</th>
        <th>Data</th>
        <th>Mittente</th>
        <th>Oggetto</th>
        
        <th>Azioni</th>
    </tr>
    <?php  if($messaggi === false): ?>
    <p><b>Nessun record</b></p>
 <?php  else: ?>
        <?php $i = 1;foreach($messaggi as $m): ?>
        <tr><?php $id_mitt=$m['id_mitt'];$testo=$m['testo']; ?>
            <td><?php echo $m['id'] ?> </td>
            <td><?php $dest=$m['data'];echo $dest ?></td>
            <td><?php echo $m['mittente'] ?></td>
            <td><?php echo $m['oggetto'] ?></td>
            
            <td><button class="button" onclick="get_msg(<?php echo $i ?>,<?php echo $id_mitt ?>,<?php echo "'".$testo."'" ?>)">Visualizza</button>
                <?php echo anchor(site_url()."/strumenti/upd_stato/del/".$m['id'], "Cancella",'class=button'); ?></td>


        </tr>
        <?php $i++; endforeach ?>
   <?php  endif; ?>
</table>
<br>
<table id="pag" >
    <tr> <td align="center"><?php echo 'Pagina:'.$this->pagination->create_links(); ?></td></tr>
</table>
<?php  require dirname(__FILE__).'/includes/footer.php'; ?>


