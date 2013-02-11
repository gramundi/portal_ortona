<?php

/*
 * Gestione Missioni
 * <form method="post" action='<?php echo site_url()."/ajax/addmod_row"."/".$missione.'/'.$op ?>'>
<input type="submit" name="invia" value="Cerca">
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

 ?>
<?php  require dirname(__FILE__).'/includes/head_1.php'; ?>
<script type="text/javascript">

function getdata()
{
    var ente=$('#ente').val();
    //alert(ente);
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
               var co='<tr> <th> ID ENTE</th>';
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
                                 var str="<a href=";
                                 str=str+"<?php echo site_url().'/albo/addmod/add/0/'; ?>"+id+" class=button > Seleziona </a>";
                                 co=co+'<td>'+str+'</td>';
                           }
                           co=co+'</tr>';
                           //alert(co);
                           $('#filtro').html('<p><b> Enti Trovati </b></p>');
                           $('#mydata').html(co);
                           $('#mydata').show();
           }
     });
}

</script>
<html>
    <body>
<p><b>Cerca Ente Richiedente Atto</b></p>
<table class="form">
<tbody>
<tr>
    <th><label for="Nome Ente">Nome Ente Richiedente</label></th>
    <td><?php $par='id="ente"';echo form_input('ente','',$par); ?></td>
</tr>
<tr>
    <th><label for="Ricerca">Ricerca Ente</label></th>
    <td><?php $par='id="ricerca" onclick="getdata();"';echo form_submit('ricerca', 'Cerca',$par); ?></td>
</tr>
</tbody>
</table>
<div   id="filtro"></div>
<table id="mydata"></table>
<hr>
<a href=<?php echo site_url().'/albo'?>> Riepilogo Atti</a>
</body>

</html>

<?php  require dirname(__FILE__).'/includes/footer.php'; ?>

