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
    var utente=$('#utente').val();

    //alert(utente);
    $.ajax({
	   type: "POST",
	   url: "<?php echo site_url("/operations/getutenti");?>",
	   data: "par="+utente,
           dataType:"jsondata",
           success: function(data){           // ciclo l'array
                          //alert(data);
                          //Codifica oggetto JSON in Oggetto Java Script
                          var myObject = eval('(' + data + ')');
                          var co='<tr> <th> ID USER </th>';
                          if(myObject.length==0){
                               $('#mydata').hide();
                           }
                          co=co+'<th> NOME </th>';
                          co=co+'<th> COGNOME </th>';
                          co=co+'<th> COMANDI </th></tr>';
                          for(i=0; i<myObject.length; i++){
                                 id=myObject[i].id;
                                 username=myObject[i].username;
                                 nome=myObject[i].nome;
                                 cognome=myObject[i].cognome;
                                 co=co+'<tr>';
                                 co=co+'<td>'+id+'</td>';
                                 co=co+'<td>'+nome+'</td>';
                                 co=co+'<td>'+cognome+'</td>';
                                 var str="";
                                 str='<a href='+"<?php echo site_url(); ?>"+'/mission/newmissadm/'+username+'/'+id;
                                 str=str+' class=button>Seleziona</a>';
                                 co=co+'<td>'+str+'</td>';
                           }
                          co=co+'</tr>';
                          //alert(co);
                          $('#filtro').html('<p><b> Utenti Filtrati </b></p>');
                          $('#mydata').html(co);
                          $('#mydata').show();
                          }                    
                   });
}

function getdata1(){

alert('getdata1');
$.getJSON(
         './json.php',
         function(data){
            // ciclo l'array
            alert(data);
            for(i=0; i<data.length; i++){
               var  content  = '<li>';
                   content +=  data[i].fname + ' ' + data[i].lname;
                   content  += '<br />';
                   content +=  data[i].number;
                   content += '</li>';

               $('ul.rubrica').append(content);
            }

         }
      );
alert('ciao');
}

</script>
<html>
    <body>
<p><b>Cerca Utente per cui Immettere Missione</b></p>
<table class="form">
<tbody>
<tr>
    <th><label for="tipo">Cognome Utente</label></th>
    <td><?php $par='id="utente"';echo form_input('utente','',$par); ?></td>
</tr>
<tr>
    <th><label for="tipo">Ricerca</label></th>
    <td><?php $par='id="ricerca" onclick="getdata();"';echo form_submit('ricerca', 'Cerca',$par); ?></td>
</tr>
</tbody>
</table>
<div id="filtro"></div>
<table id="mydata"></table>
<a href=<?php echo site_url() ?>> Riepilogo Missioni</a>
</body>
</html>

<?php  require dirname(__FILE__).'/includes/footer.php'; ?>

