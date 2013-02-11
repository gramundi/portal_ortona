<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php  require dirname(__FILE__).'/includes/head_1.php'; ?>


<script type="text/javascript">

function CercaEnte(){
    //alert('CercaEnte');
    $('#cerca_ente').show();
}

function ChangeEnte(nome){
     $('#ente').val(nome);
     $('#cerca_ente').hide();
     $('#mydata').hide();
    
}
function getdata(){
    var ente=$('#enteric').val();
    //recupera i dati dalla tabella enti via ajax
    $.ajax({
	   type: "POST",
	   url: "<?php echo site_url()."/enti/"; ?>",
	   data: "par="+ente,
           dataType:"jsondata",
           success: function(data){
               // ciclo l'array
               //alert(data.length);
               if(data.length==0){
                     $('#mydata').hide();
               }
               var myObject = eval('(' + data + ')');
               //alert(myObject.length);
               var         co='<tr> <p><b> Enti Trovati </b></p> </tr>';
                           co=co+'<tr> <th> ID ENTE</th>';
                           co=co+'<th> TIPO </th>';
                           co=co+'<th> NOME </th>';
                           co=co+'<th> AZIONI </th></tr>';
                           for(i=0; i<myObject.length; i++){
                                 id=myObject[i].id;
                                 tipo=myObject[i].tipo;
                                 nome=myObject[i].nome;
                                 co=co+'<tr>';
                                 co=co+'<td>'+id+'</td>';
                                 co=co+'<td>'+tipo+'</td>';
                                 co=co+'<td>'+nome+'</td>';
                                 var str="";
                                 str='<button type="button"  onclick="ChangeEnte('+"'"+nome+"'"+');"> Seleziona</button>';
                                 co=co+'<td>'+str+'</td>';
                           }
                           co=co+'</tr>';
                           //alert(co);
                           $('#mydata').html(co);
                           $('#mydata').show();
           }
     });
}

function isInteger(s){
	var i;

     //   alert('is integer');
    for (i = 0; i < s.length; i++){
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
    }
    // All characters are numbers.
    return true;
}

function validadati(){

//alert('valida-Dati');

var strOgg=$('#oggetto').val();
var strPer=$('#periodo').val();

if (strOgg==false){
                alert("Per Inserire Oggetto dell' atto");
		return false
}

if (strPer==false) {
                alert("Inserire i giorni di pubblicazione in    Periodo(gg)");
		return false
}

if (isInteger(strPer)==false){
                alert("Per Favore inserire periodo valido(Nr gg di validità dell'atto)");
		return false
}

        return true;
}


</script>

<h1><?php echo $title ?></h1>
<table>
<form method="post" action='<?php echo site_url()."/albo/addmod/".$op ?>' >
<?php if(isset($id)) echo form_hidden("id",$id); ?>
<?php if(isset($id_ente)) echo form_hidden("id_ente",$id_ente); ?>
<?php if(isset($stato)) echo form_hidden("stato",$stato); ?>

<tr>
    <th>*ente</th>
    <td><?php $data='id="ente" readonly="readonly" size="30"'; echo form_input('ente', $ente,$data);
              echo '  <button type="button" onClick="CercaEnte();"> Cerca </button>' ?></td>

</tr>    

<tr>
    <th><label for="tipo">*tipo atto</label></th>
    
    <td><?php $data=' id="tipo"';
     $i=0;
     foreach ($tipi_atti as $row)
                {
                      $i=$i+1;
                      $options[''.$i]=$row['descrizione'];

                }
    //array('1' => 'delibera', '2' => 'bando','3'=>'concorso','4' => 'comunicato', '5' => 'determina', '6' => 'ordinanza',
    // '7' => 'convocazione','8' => 'notifica', '9' => 'provvedimento',10'=> 'avviso','11'=>'verbale','12'=>'permesso')
              echo form_dropdown('id_tipo',$options,$id_tipo,$data); ?>
    
    
    </td>
    
</tr>
<tr><th>Riferimento</th>
    <td><?php $data=' id="rif" size="10"';echo form_input('rif', $rif,$data)?></td></tr>
<tr>
    <th>*oggetto</th>
    <td><?php $data='id="oggetto" size=120'; echo form_input('oggetto', $oggetto,$data)?> </td>


</tr>
<tr>
    <th><label for="periodo">*Periodo(gg)</label></th>
    <td><?php $data=' id="periodo" size="10"';echo form_input('periodo', $periodo,$data)?></td>
</tr>
<tr>
    <th><label for="descrizione">Descrizione Atto</label></th>
    <td><?php $data=' id="descrizione" size="2000"';echo form_textarea('descrizione', $descrizione,$data)?></td>
</tr>
<tr>
<td><?php $data='onclick="return validadati();"'; echo form_submit("invia","Salva",$data)?> </td>
</tr>
    

</form>
    </table>


<table id="cerca_ente"  style="display:none">
<tbody>
<tr>
    <th><label for="Nome Ente">Nome Ente Richiedente</label></th>
    <td><?php $par='id="enteric"';echo form_input('ente','',$par); ?></td>
</tr>
<tr>
    <th><label for="Ricerca">Ricerca Ente</label></th>
    <td><?php echo '<button type="button" onclick="getdata();"> Cerca </button>'; ?></td>
</tr>

</tbody>
</table>
<table id="mydata"></table>

<br>
<table><tr><td><a href=<?php echo site_url().'/albo'?>> Riepilogo Atti</a>
</td></tr></table>
<?php  require dirname(__FILE__).'/includes/footer.php'; ?>



