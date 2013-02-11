<?php  require dirname(__FILE__).'/includes/head_1.php'; ?>
<script type="text/javascript">

//Cerca il riferimento codice  sul registro dell'albo pretorio
function CercaRif(){

 var rif=$('#rif').val();
    $('#oggetto').val('');
    //recupera i dati dalla tabella enti via ajax
    $.ajax({
	   type: "POST",
	   url: "<?php echo site_url()."/GetOggetto/"; ?>",
	   data: "par="+rif,
           dataType:"jsondata",
           success: function(data){
               // decodifico JSON
               //alert (data.length);
               if (data.length==0){ 
                   alert ('riferimento errato');
                   $('#rif').val('');
               }
               else {
                   var res = eval('(' + data + ')');
                   $('#oggetto').val(res[0].oggetto);
               //}
               }
               }

     });
}


function validadati(){
return true;

}
</script>

<h1><?php echo $title ?></h1>
<table>
<form method="post" action='<?php echo site_url()."/manage/index/".$op ?>' >
<?php if(isset($id)) echo form_hidden("id",$id); ?>
<?php if(isset($stato)) echo form_hidden("stato",$stato); ?>

 

<tr>
    <th>*ordinante</th>
    <td> <?php $data=' id="ordinante"';
     foreach ($ordinanti as $row) $options1[$row['cognome']]=$row['cognome'];
     echo form_dropdown('ordinante',$options1,$ordinante,$data); ?>
    </td>
</tr>   

<tr><th>Cod. Repertorio</th>
    <td><?php $data=' id="rif" size="10" onChange="CercaRif()"';echo form_input('rif', $rif,$data)?></td></tr>
<tr>
    <th>*oggetto</th>
    <td><?php $data='id="oggetto" size=120'; echo form_input('oggetto', $oggetto,$data)?> </td>
</tr>

<tr>
    <th>File Ordinanza:</th>
   
</tr>

<tr>
    <th><label for="descrizione">Descrizione Ordinanza</label></th>
    <td><?php $data=' id="descrizione" size="2000"';echo form_textarea('descrizione', $descrizione,$data)?></td>
</tr>
<tr>
<td><?php $data='onclick="return validadati();"'; echo form_submit("invia","Salva",$data)?> </td>
</tr>
    
</form>
</table>
<table><tr><td><a href=<?php echo site_url().'/ordinanze'?>> Riepilogo Ordinanze</a>
</td></tr></table>
<?php  require dirname(__FILE__).'/includes/footer.php'; ?>



