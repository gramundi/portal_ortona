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

function validaform(){
    //alert('validaform');
    var nome=$('#nome').val();
    var cognome=$('#cognome').val();
    var username=$('#username').val();
    var password=$('#password').val();

    if (nome && cognome && username && password ) return true;
    else{
        alert('inserire campi obbligatori');
        return false;
        }

}
function add()
{
$('#manageuser').hide();
$('#nome').val('');
$('#cognome').val('');
$('#ruolo').val('');
$('#username').val('');
$('#password').val('');
$('#manageuser').show();
$('#op').val('2');
}

function mod(id)
{
var row=id;
var column=0;
//var cell = $('tr:eq(' + row + ') td:eq(' + column + ')');

//alert(row);
//$('#manageuser').hide();

var id=$('tr:eq(' + row + ') td:eq(0)').text();
var nome=$('tr:eq(' + row + ') td:eq(1)').text();
var cognome=$('tr:eq(' + row + ') td:eq(2)').text();
var ruolo=$('tr:eq(' + row + ') td:eq(3)').text();


$('#iduser').val(id);
$('#nome').val(nome);
$('#cognome').val(cognome);
$('#ruolo').val(ruolo);
$('#op').val('1');
$('#manageuser').show();
//alert(id+nome+cognome+ruolo);


}

function cancella(id)
{
alert (' Sei Sicuro');
$('#iduser').val(id);

}


</script>
<p><b>Riepilogo Utenti</b></p>
<p><input type=submit value="aggiungi" onclick="add()"></p>
<table id="ute">
    <tr>
        <th>ID</th>
        <th>NOME</th>
        <th>COGNOME</th>
        <th>RUOLO</th>
        <th>AZIONI</th>
    </tr>

<p><b><?php  if($utenti === false): ?>Non ci Sono Utenti</b>
<?php  else: ?>
<?php $i = 1; foreach($utenti as $m): ?>
<tbody>
<tr id="<?php echo $i?>">
       <td><?php  $id=$m['id']; echo $id ?></td>
       <td><?php  echo $m['nome']?></td>
        <td><?php echo $m['cognome']?></td>
        <td><?php echo $m['ruolo'] ?></td>
        <td><button name="modifica" type="button" onclick="mod(<?php  echo $i?>)">modifica</button>
        <?php echo anchor("utenti/ManageUtenti/3/$id","Cancella", 'class=button'); ?></td>

   </tr>
    <?php $i++; endforeach ?>

   
</tbody>
</table>
<br>

<table><tr><td><?php echo 'Pagine:'.$pag; ?></td></tr></table>
<?php  endif; ?>
<form method="post" action='<?php echo site_url()."/utenti/set_filtro" ?>'  >
    <table id="ricerca">
        <tr>
            <th>nome</th>
        </tr>
        <tr>
            <td><?php echo form_input('nome',$fil1)?> </td>
        </tr>
        <tr><td><?php $par='id="ricerca"'; echo form_submit('ricerca', 'Cerca'); ?></td></tr>
    </table>
</form>
<form method="post" id="manageuser" style="display:none" action="<?php echo site_url('/utenti/ManageUtenti/');?>">
<br>
<table class="form">
<tbody>
    <input type="hidden" id="op" name="op" >
    <input type="hidden" id="iduser" name="iduser" >
    <tr>
    <th>Nome</th> <td><?php $par ='id="nome"'; echo form_input('nome','',$par)?></td>
    <th>Cognome</th>
    <td><?php $par ='id="cognome"'; echo form_input('cognome','',$par)?></td>
    </tr>
<tr>
    <th><label for="ruolo">Ruolo</label></th>
    <td><?php $js = ' id="ruolo" '; 
              echo form_dropdown('ruolo',array('admin' => 'admin', 'resppub' => 'resppub','publisher'=>'publisher','normal'=>'normal'),'normal',$js); ?>
    </td>
    <th><label for="qualifica">Qualifica</label></th>
    <td><?php $js = ' id="qualifica" '; echo form_dropdown('qualifica',
            array('dir' => 'dir', 'c' => 'c', 'd3' => 'd3','b'=>'b','a'=>'a','d'=>'d','b3'=>'b3'),'dir',$js); ?>
    </td>
    </tr>
    <tr>
    <th><label for="capitolo">Capitolo</label></th>
    <td><?php $js = ' id="capitolo" '; echo form_dropdown('capitolo',
            array('segreteria' => 'segreteria', 'finanze' => 'finanze','uff tec'=>'uff tec',
                'Bilbio'=>'Biblio','Pol Mun'=>'Polizia Municipale','Sociale'=>' Ass Sociale',
                'Demo'=>'Serv Demografici','Urbanist'=>'Serv Urbanistici'),'segreteria',$js); ?>
         </td>
</tr>
<tr>
    <th><label for="username">Nome Utente</label></th>
    <td><?php  $par ='id="username"';  echo form_input('username','',$par)?></td>
    <th><label for="password">Password</label></th>
    <td><?php $par ='id="password"'; echo form_input('password','',$par)?></td>
</tr>
<tr><td><input type="submit" name="invia" value="Salva" onclick="return validaform();"></td></tr>
</tbody>
</table>
</form>
<?php  require dirname(__FILE__).'/includes/footer.php'; ?>

