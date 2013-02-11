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


nrtitolari=<?php echo $nrtitolari ?>;

$(document).ready(function () {

    strric='';
    id_app="<?php echo $id_appuntamento ?>";
    //alert(strric);
    $.ajax({
	   type: "POST",
	   url: "<?php echo site_url()."/agenda/cerca_titolari"; ?>",
	   data: "par="+strric+'-'+id_app,
           dataType:"jsondata",
           success: function(data){
               // ciclo l'array
               //alert(data);
               if(data.length==0){
                     $('#titolari').hide();
               }
               var myObject = eval('(' + data + ')');
               //alert(myObject.length);
               var
                           
                           co='<tr><th> id </th>';
                           co=co+'<th> titolare </th>';
                           co=co+'<th> azioni </th></tr>';

                           for(i=0; i<myObject.length; i++){
                                 id=myObject[i].id_user;
                                 titolare=myObject[i].titolare;
                                 co=co+'<tr id="'+id+'">';
                                 co=co+'<td>'+id+'</td>';
                                 co=co+'<td>'+titolare+'</td>';
                                 var str="";
                                 str='<button id="bt'+id+'sel" type="button"  onclick="manage(1,'+id+');"> Seleziona</button>';
                                 co=co+'<td>'+str+'</td>';
                                 co=co+'</tr>';
                           }
                           $('#titolari').html(co);
                           $('#titolari').show();
           }
     });


})


function manage(op,id_titolare){

//alert(op+'-'+id_titolare);

//Nota jquery tr:eq ragiona contando le righe mentre tr:#id unta con l'identificatore di riga'
/*id=$('#titolari tr:#'+id_titolare+' td:eq(0)').text();
titolare=$('#titolari tr:#'+id_titolare+' td:eq(1)').text();
alert('titolari====='+id+'----'+titolare);
id=$('#titolariapp tr:#'+id_titolare+' td:eq(0)').text();
titolare=$('#titolariapp tr:#'+id_titolare+' td:eq(1)').text();
alert('titolariappuntameno======'+id+'----'+titolare);
*/

if ((nrtitolari == 2 ) &&( op==0)) {
    alert('Operazione non concessa:Un appuntamento collettivo richiede più di un titolare');
    return false;
}

id_app="<?php echo $id_appuntamento ?>";
titolo="<?php echo $titolo ?>";
    
$.ajax({
	   type: "POST",
	   url: "<?php echo site_url()."/agenda/associa_titolari"; ?>",
	   data: "par="+op+'-'+id_app+'-'+id_titolare+'-'+titolo,
           dataType:"jsondata",
           async:false,
           success: function(data){
                alert(data);
                if (data==0) alert('associazione non effettuata titolare impegnato');
                else {
                    if (op==1) {
                        nrtitolari=nrtitolari+1;
                        //alert('associazione effettuata');
                        titolare=$('#titolari tr:#'+id_titolare+' td:eq(1)').text();
                     
                        $('#titolari    tr:#'+id_titolare+'').remove();
                        $('#titolariapp').append('<tr id="'+id_titolare+'" ><td>'+id_titolare+'</td><td>'+titolare+'</td><td><button type="button" onclick="manage(0,'+id_titolare+');">Cancella</button></td></tr>');
                       
                    }
                    else {

                    nrtitolari=nrtitolari-1;
                    //alert('cancellazione effettuata');
                    titolare=$('#titolariapp tr:#'+id_titolare+' td:eq(1)').text();
                    
                    $('#titolariapp    tr:#'+id_titolare+'').remove();
                    $('#titolari').append('<tr id="'+id_titolare+'" ><td>'+id_titolare+'</td><td>'+titolare+'</td><td><button type="button" onclick="manage(1,'+id_titolare+');"> Seleziona</button></td></tr>');
                    
                   }

                }
               
           }
     });

}




function valida(){

    if (nrtitolari==1){
        alert('Attenzione per appuntamenti collettivi devi associare più di un titolare');
        return false;
    }

}


</script>

<table id="titolariapp">
    <tr><b>Titolari Associati</b></tr>
    <tr>
        <th>id</th>
        <th>titolare</th>
        <th>azioni</th>
    </tr>
    
<?php $i = 1;foreach($titolari as $m): ?>

    <tr id="<?php echo  $m['id_titolare'] ?>">
        <td><?php echo  $m['id_titolare'] ?></td>
        <td><?php echo $m['titolare'] ?> </td>
        <td><?php echo '<button id="bt'.$m['id_titolare'].'del" type="button"  onclick="manage(0,'.$m['id_titolare'].');" > Cancella</button>' ?>
        </td>
    </tr>
    <?php $i++; endforeach ?>
</table>
<br>
<table id="titolari" style="display:none">
<tr><b>Titolari Associabili</b> </tr>
</table>
<br>
<form method="post" action=' <?php if ($caller=='agenda') $url='/agenda/display';
                                   else $url='/riepilogoapp/gest_app';
                                   echo site_url().$url ?>'  >
<?php  $js='onclick="return valida();"';echo form_submit('salva','salva',$js) ?>
</form>

<?php  require dirname(__FILE__).'/includes/footer.php'; ?>

