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

function InviaMsg(op) {

if (op=='ok'){
     oggetto=$('#oggetto').val();
     testo=$('#testo').val();
     id_dest=$('#id_dest').val();
     //alert('InviaMsg:'+id_dest+oggetto+testo);
     rif='null';
    $.ajax({
                type: "POST",
                async: false,
                url: "<?php echo site_url()."/strumenti/invia"; ?>",
                data: "par="+id_dest+'-'+oggetto+'-'+testo+'-'+rif,
                dataType:"jsondata",
                success: function(data){

                alert(data);
                alert('Messaggio Inviato con Successo');

                }
            });
}

 $('#rubrica').show();
 $('#ricerca').show();
 $('#pag').show();
 $('#messaggio').hide();

}

function CreaMsg(dest,id_dest){

    //alert(dest+id_dest);
    $('#rubrica').hide();
    $('#ricerca').hide();
    $('#pag').hide();
    $('#dest').val(dest);
    $('#id_dest').val(id_dest);

    $('#messaggio').show();
    

}
</script>


<h1><?php echo $title;  ?></h1>
    <table id="messaggio" style="display:none">
        <input type="hidden" id="id_dest" name="id_dest" >
        <tr><th>Destinatario</th><td><?php $par = 'readonly="readonly" id="dest"';echo form_input('dest','', $par) ?></td></tr>
        <tr><th>Oggetto</th><td><?php $par='id="oggetto"'; echo form_input('oggetto','',$par)?> </td></tr>
        <tr><th>Testo</th><td><?php $par='id="testo"';echo form_textarea('text','',$par); ?> </td></tr>
        <tr><td><button onclick="InviaMsg('ok')">Invia</button>
            <button onclick="InviaMsg('ko')">Annulla</button>
            </td>
        </tr>
    </table>

<form method="post" action='<?php echo site_url()."/strumenti/set_filtro/rubrica" ?>'  >
    <table id="ricerca">
        <tr>
            <th>cognome</th>
            <th>settore</th>
        </tr>
        <tr>
            <td><?php echo form_input('cognome',$fil1)?> </td>
            <td><?php $options1 = array('Tutti' =>'Tutti','Sett1'   => 'Sett1', 'Sett2' => 'Sett2','Sett3' => 'Sett3','Sett4' => 'Sett4');
                      echo form_dropdown('sett', $options1, $fil2); ?> </td>
        </tr>
        <tr><td><?php echo form_submit('ricerca', 'Cerca'); ?></td></tr>
    </table>
</form>
<br>
<br>
<table id="rubrica">
    <tr>
        <th>nome</th>
        <th>cognome</th>
        <th>settore</th>
        <th>interno</th>
        <th>cellulare</th>
        <th>azioni</th>
    </tr>
    <?php  if($rubrica === false): ?>
    <p><b>Nessun record</b></p>
 <?php  else: ?>
        <?php $i = 1;foreach($rubrica as $m): ?>
        <tr>
            <td><?php $id=$m['id_utente'];echo $m['nome']?></td>
            <td><?php $dest=$m['cognome'];echo $dest ?></td>
            <td><?php echo $m['settore'] ?></td>
            <td><?php echo $m['num_int'] ?></td>
            <td><?php echo $m['cellulare'] ?></td>
            <td><button onclick="CreaMsg(<?php echo "'".$dest."'".','.$id ?>)">Contatta</button></td>

        </tr>
        <?php $i++; endforeach ?>
   <?php  endif; ?>
</table>
<br>
<table id="pag" >
    <tr> <td align="center"><?php echo 'Pagina:'.$this->pagination->create_links(); ?></td></tr>
</table>
<?php  require dirname(__FILE__).'/includes/footer.php'; ?>


