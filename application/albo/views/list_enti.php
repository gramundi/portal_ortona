<?php

/*
 * Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

 ?>
<?php  require dirname(__FILE__).'/includes/head_1.php'; ?>
<script type="text/javascript">
function add()
{

$('#tipo').val('');
$('#nome').val('');
$('#indir').val('');
$('#tel').val('');
$('#piva').val('');
$('#cf').val('');
$('#op').val('2');
$('#manageenti').show();

}

function mod(id)
{
var row=id;
var column=0;
//var cell = $('tr:eq(' + row + ') td:eq(' + column + ')');

//alert(row);
var id=$('tr:eq(' + row + ') td:eq(0)').text();
var tipo=$('tr:eq(' + row + ') td:eq(1)').text();
var nome=$('tr:eq(' + row + ') td:eq(2)').text();
var indirizzo=$('tr:eq(' + row + ') td:eq(3)').text();
var telefono=$('tr:eq(' + row + ') td:eq(4)').text();
var piva=$('tr:eq(' + row + ') td:eq(5)').text();
var cf=$('tr:eq(' + row + ') td:eq(6)').text();



$('#idente').val(id);
$('#tipo').val(tipo);
$('#nome').val(nome);
$('#indir').val(indirizzo);
$('#tel').val(telefono);
$('#piva').val(piva);
$('#cf').val(cf);
$('#op').val('1');
$('#manageenti').show();

}

function ricerca()
{
  alert('getstringaricerca');
  var stringa=$('#ente').val();
    alert(stringa);
    //recupera i dati dalla tabella enti via ajax
    $.ajax({
	   type: "POST",
	   url: "<?php echo site_url()."/enti/ManageEnti/0/"?>"+stringa
	   });

}




</script>

<h3>Richiedenti:</h3>
<form  method="post" id="cercaenti"  action="<?php echo site_url('/enti/ManageEnti/0/');?>">
<table id="cercaenti">
<tr>
    <th><label for="Nome Richiedente">Nome Richiedente</label></th>
    <td><?php $par='id="ente"';echo form_input('ente','',$par); ?></td>
</tr>
<tr>
    <th><label for="Ricerca">Ricerca Richiedente</label></th>
    <td><input type="submit" value="Cerca" ></td>
</tr>
</table>
</form>
<p><input type=submit value="aggiungi" onclick="add()"></p>

<table id="dati">
    <tr>
        <th>tipo</th>
        <th>nome</th>
        <th>indirizzo</th>
        <th>telefono</th>
        <th>piva</th>
        <th>codice fiscale</th>
        <th>azioni</th>
    </tr>
    <?php  if($enti === false): ?>
    <p><b>Non ci Sono Enti</b></p>
<?php  else: ?>
<?php $i = 1; foreach($enti as $m): ?>
    <tr>
        <?php $id=$m['id'] ?>
        <td><?php echo $m['tipo']?></td>
        <td><?php echo $m['nome'] ?></td>
        <td><?php echo $m['indir'] ?></td>
        <td><?php echo $m['tel'] ?></td>
        <td><?php echo $m['piva']?></td>
        <td><?php echo $m['cf']?></td>
        <td><button name="modifica" type="button" onclick="mod(<?php  echo $i?>)">modifica</button>
            <?php echo anchor("enti/ManageEnti/3/$id","Cancella", 'class=button'); ?>
        </td>
   </tr>
    <?php $i++; endforeach ?>
   <?php  endif; ?>
   
   <tr> <td align="center">
    </td>
   </tr>

</table>
<hr>
<hr>
<br>
<form method="post" id="manageenti" style="display:none" action="<?php echo site_url('/enti/ManageEnti/');?>">
<table class="form">
<tbody>
    <input type="hidden" id="op" name="op" >
    <input type="hidden" id="idente" name="idente" >
    <tr>
    
    <th>Tipo</th><td><?php $js = ' id="tipo" '; echo form_dropdown('tipo',array('interno' => 'interno', 'esterno' => 'esterno'),'interno',$js); ?></td>
    <th>Nome</th> <td><?php $par ='id="nome"'; echo form_input('nome','',$par)?></td>
    <th>Indirizzo</th><td><?php $par ='id="indir"'; echo form_input('indir','',$par)?></td>
    </tr>
    <tr>
    <th>Telefono</th><td><?php $par ='id="tel"'; echo form_input('tel','',$par)?></td>
    <th>Partita IVA</th><td><?php $par ='id="piva"'; echo form_input('piva','',$par)?></td>
    <th>Codice Fiscale</th><td><?php $par ='id="cf"'; echo form_input('cf','',$par)?></td>

    </tr>

<tr><td><input type="submit" name="invia" value="Salva" onclick="return validaform();"></td></tr>
</tbody>
</table>
</form>
<hr>
<a href=<?php echo site_url().'/albo'?>> Riepilogo Atti</a>


<?php  require dirname(__FILE__).'/includes/footer.php'; ?>

