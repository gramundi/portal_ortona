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

//Tecnica jquery interrogo con i wrapper il documento recuperando i dati in tabella
//Non appensentisco il sistema lato server ma i dati li ho già nella pagina
//Controlla se la data corrente è maggiore della data di scadenza pubblicazione
function getaccessi(){

var username=$('#username').val();
//var dataacc= $('#dataacc').val();
alert(username);

$.ajax({

	   type: "POST",
	   url: "<?php echo site_url()."/strumenti/getlog/"; ?>",
	   data: "par="+username,
           dataType:"jsondata",
           success: function(data){
               alert(data);
               if(data.length==0){
                     $('#logdata').hide();
               }
               var myObject = eval('(' + data + ')');
               var co='<tr> <th> COGNOME </th>';
                           co=co+'<th> NOME </th>';
                           co=co+'<th> DATAINI</th>';
                           co=co+'<th> DATAFINI</th>';
                           co=co+'<th> TEMPO CONN(sec)</th></tr>';
                           for(i=0; i<myObject.length; i++){
                                 nome=myObject[i].cognome;
                                 cognome=myObject[i].nome;
                                 dataini=myObject[i].dataini;
                                 datafin=myObject[i].datafin;
                                 secondi=myObject[i].secondi;
                                 co=co+'<tr>';
                                 co=co+'<td>'+nome+'</td>';
                                 co=co+'<td>'+cognome+'</td>';
                                 co=co+'<td>'+dataini+'</td>';
                                 co=co+'<td>'+datafin+'</td>';
                                 co=co+'<td>'+secondi+'</td>';

                           }
                           co=co+'</tr>';
                           alert(co);
                           $('#filtro').html('<p><b> Accessi Trovati </b></p>');
                           $('#logdata').html(co);
                           $('#logdata').show();
           }
     });

 

}


</script>

<h1><?php echo $title;  ?></h1>
<table id="ricerca">
        <tr>
           
            <th>username</th>
            <th>dataacc</th>
            
        </tr>
        <tr>
            <td><?php $opz='id="username"'; echo form_input('username','',$opz)?> </td>
            <td><?php $opz='id="dataacc"'; echo form_input('dataacc','',$opz)?> </td>
            
        </tr>
        <tr><td><?php $par='id="ricerca" onclick="getaccessi();"'; echo form_submit('ricerca', 'Cerca',$par); ?></td></tr>
</table>
<table id="logdata">

</table>
<br>

<?php  require dirname(__FILE__).'/includes/footer.php'; ?>

