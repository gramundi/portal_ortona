<?php
/*
 * Module:
 *
 *
 * Date:10/02/2010
 * Author: Jhonny Ramundi <http://www.jhonnynext.it>
 */
?>
<?php require dirname(__FILE__) . '/includes/head.php'; ?>
<script type="text/javascript">

    
    /* Reset the field of the form wich add users */
    function add()
    {
        //Hide the the manage table of the  user
        $('#contatti').hide();
        $('#ricerca').hide();
        $('#pag').hide();
        $('#op').val('add');
        //Reset fields
        $('#nome').val('');
        $('#cognome').val('');
        $('#ragsoc').val('');
        $('#telef').val('');
        $('#sito').val('');
        $('#email').val('');
        $('#emailsec').val('');
        $('#cell1').val('');
        $('#cell2').val('');
        $('#note').val('');
        $('#contattifrm').show();

    }

//Build the table to modify the user in the row i
    function mod(id)
    {
       
       $('#contatti').hide();
       $('#ricerca').hide();
       $('#pag').hide();

       $.ajax({
	   type: "POST",
	   url: "<?php echo site_url()."/gestcontatti/get_contatto"; ?>",
	   data: "par="+id,
           async:false,
           dataType:"jsondata",
           success: function(data){
               //alert(data);
               var myObject = eval('(' + data + ')');
               //alert(myObject.length);
               nome=myObject[0].nome;
               cognome=myObject[0].cognome;
               ragsoc=myObject[0].ragsoc;
               tel=myObject[0].tel;
               sito=myObject[0].sito;
               email=myObject[0].email;
               emailsec=myObject[0].emailsec;
               cell1=myObject[0].cell1;
               cell2=myObject[0].cell2;
               note=myObject[0].note;
           }
        });

        $('#nome').val(nome);
        $('#cognome').val(cognome);
        $('#ragsoc').val(ragsoc);
        $('#telef').val(tel);
        $('#sito').val(sito);
        $('#email').val(email);
        $('#emailsec').val(emailsec);
        $('#cell1').val(cell1);
        $('#cell2').val(cell2);
        $('#note').val(note);
        $('#op').val('mod');
        $('#idcontatto').val(id);
        $('#contattifrm').show();


        
    }


function cancella(id){
        alert (' Sei Sicuro');
        $('#iduser').val(id);

}

function validaform(){

nome=$('#nome').val();
cognome=$('#cognome').val();
if ( nome == '' ) {
     alert('prego inserire nome contatto');
     return false;
}

if ( cognome == '' ) {
     alert('prego inserire cognome contatto');
     return false;
 }



}


</script>

<hr>
<form method="post" action='<?php echo site_url() . "/gestcontatti/set_filtro" ?>'  >
<table id="ricerca">
    <tr><th>cognome</th><th>ragione sociale</th></tr>
    <tr> 
        <td><?php echo form_input('cognome', $fil1) ?> </td><td><?php echo form_input('ragsoc', $fil2) ?> </td>
    </tr>
    <tr><td><?php $par = 'id="ricerca"';echo form_submit('ricerca', 'Cerca'); ?></td></tr>
</table>
</form>

<p><b>Riepilogo Contatti</b></p>
<table id="contatti">
    <tr><td><input type=submit value="Nuovo Contatto" onclick="add()"></td></tr>
    <tr>
        <th>ID</th>
        <th>NOME</th>
        <th>COGNOME</th>
        <th>RAGIONE SOCIALE</th>
        <th>CELLULARE DI RECAPITO</th>
        <th>AZIONI</th>
    </tr>
    <p><b><?php if ($contatti === false): ?>Non ci Sono Conatti In Agenda</b>
    <?php else: ?>
    <?php $i = 1;
            foreach ($contatti as $m): ?>
                <tr id="<?php echo $i ?>">
                    <td><?php $id = $m['id'];
                echo $id ?></td>
                    <td><?php echo $m['nome'] ?></td>
                    <td><?php echo $m['cognome'] ?></td>
                    <td><?php echo $m['ragsoc'] ?></td>
                    <td><?php echo $m['cell1'] ?></td>
                    <td><button name="modifica"  type="button" onclick="mod(<?php echo $id ?>)">modifica</button>
                    <?php echo anchor("gestcontatti/dmlcontatti/del/$id", "Cancella", 'class=button'); ?>
            </td>
            </tr>
           <?php $i++; endforeach ?>
</table>
<br>
<table id="pag"><tr><td><?php echo 'Pagine:' . $pag; ?></td></tr></table>
<?php endif; ?>
<div align="left" style="width:800px;">
<form method="post" id="contattifrm" style="display:none" action="<?php echo site_url('/gestcontatti/dmlcontatti/'); ?>">
    <input type="hidden" id="op" name="op" >
    <input type="hidden" id="idcontatto" name="idcontatto" >
    <fieldset><legend>Gestione Contatti (*)Campi obbligatori</legend>

    <label> Cognome(*) </label><?php $par = 'id="cognome"';echo form_input('cognome', '', $par) ?>
    <label>Nome(*) </label><?php $par = 'id="nome"';echo form_input('nome', '', $par) ?><br />
    <label>Ragione Sociale </label><?php   $par = 'id="ragsoc"';echo form_input('ragsoc', '', $par) ?>
    <label>Recapito Tel. </label><?php $par = 'id="telef"'; echo form_input('telef', '', $par) ?><br />
    <label>Sito Internet </label><?php $par = 'id="sito"'; echo form_input('sito', '', $par) ?>
    <label>email </label><?php $par = 'id="email"'; echo form_input('email', '', $par) ?><br />
    <label>email aziendale </label><?php $par = 'id="emailsec"'; echo form_input('emailsec', '', $par) ?><br />
    <label>Cellulare1 </label><?php   $par = 'id="cell1"';echo form_input('cell1', '', $par) ?>
    <label>Cellulare2 </label><?php $par = 'id="cell2"'; echo form_input('cell2', '', $par) ?><br />    
    <label>Note </label><?php   $par = 'id="note"';echo form_textarea('note', '', $par) ?><br />
    <input type="submit" name="invia" value="Salva" onclick="return validaform();">
    </fieldset>
</form>
</div>
<?php require dirname(__FILE__) . '/includes/footer.php'; ?>

