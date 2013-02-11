<?php  require dirname(__FILE__).'/includes/head.php'; ?>

<script type="text/javascript" >

f_richiedente=0;
ora_app_inm=0;
min_app_inm=0;




$(document).ready(function () {
//Gestione Calendario Appuntamenti
$('.day_num').click(function() {
			
                        
                        //se il click per la modifica lascio l'evento in modifica;

                        //alert('catch');
                        if($('#tipoop').val()=='mod') return;
                        day_num = $(this).html();
                        //alert(day_num);
                        if (day_num==null) return;




                        //day_data = prompt('Nuovo Appuntamento', $(this).find('.content').html());
			titolare="<?php echo $titolare ?>";
                        //alert(titolare);
                        month="<?php echo $month ?>";
                        year="<?php  echo $year  ?>";

                        date=day_num+'/'+month+'/'+year;
                        
                        gg="<?php echo date("d"); ?>";
                        mm="<?php echo date("m"); ?>";
                        yy="<?php echo date("Y"); ?>";

                        if ((parseInt(year,10) - parseInt(yy,10)) < 0 ){
                            //alert(year+yy);
                            alert('impossibile inserire appuntamento');
                            return false;
                        }
                        if ((parseInt(month,10) - parseInt(mm,10)) < 0 ){
                            //alert(month+mm);
                            alert('impossibile inserire appuntamento');
                            return false;
                        }
                        if ((parseInt(day_num,10) - parseInt(gg,10)) < 0 ){
                            //alert(gg+day_num);
                            alert('impossibile inserire appuntamento');
                            return false;
                        }
                  

                        $('#cal').hide();

                        $('#strtitl').val(titolare);
                        $('#newe').show();
                        $('#tipoop').val('add');
                     
                        $('#id_app').val('gg');
                        $('#dataevt').val(date);
                        $('#dataevt').attr("readonly", true)


 		});
});


function gest_evt (day_num,id) {

    $("#dataevt").datepicker();
    $("#dataevt").datepicker( "option", "dateFormat", "dd/mm/yy" );

    //alert('gest_evt');
    $('#tipoop').val('mod');
    richiedente='';
    tipo='';
    titolo='';
    descrizione='';
    month="<?php echo $month ?>";
    year="<?php  echo $year  ?>";
    date=day_num+'/'+month+'/'+year;
    id_titolare="<?php echo $id_titolare ?>";
    $.ajax({
	   type: "POST",
	   url: "<?php echo site_url()."/agenda/get_data_app"; ?>",
	   data: "par="+id+'-'+id_titolare,
           async:false,
           dataType:"jsondata",
           success: function(data){

               //alert(data);
               var myObject = eval('(' + data + ')');
               //alert(myObject.length);
               ora=myObject[0].ora;
               min=myObject[0].min;
               richiedente=myObject[0].richiedente;
               id_richiedente=myObject[0].id_richiedente;
               tipo=myObject[0].tipo;
               if (tipo=='S') titolare=myObject[0].titolare;
               titolo=myObject[0].oggetto;
               descrizione=myObject[0].descrizione;
           }
     });

    
    //annullo il vicolo di ricercare il richiedente
    f_richiedente=1;
    ora_app_inm=ora;
    min_app_inm=min;
    $('#dataevt').val(date);
    $('#ora').val(ora);
    $('#min').val(min);
    $('#id_richiedente').val(id_richiedente);
    $('#strrich').val(richiedente);
    
    $('#tipo').val(tipo);

    if (tipo=='S') $('#strtitl').val("<?php echo $titolare ?>");
    hideshowTitolare();
    $('#titolo').val(titolo);
    $('#descrizione').val(descrizione);
    $('#id_app').val(id);
    $('#cal').hide();
    $('#newe').show();
    
}

function AttivaRic(st){

    $('#contatti').hide();
    if (st) {
        $('#ButtRich').show();
        $('#NewRich').hide();
        f_richiedente=0;
    }
    else {
        $('#ButtRich').hide();
        f_richiedente=1;
    }

}

function SetInput(id,nome) {

    $('#strrich').val(nome);
    $('#id_richiedente').val(id);
    AttivaRic(0);

}

function NuovoContatto(){

    //alert('newrich');
    $('#newe').hide();
    $('#contattifrm').show();

}




function CercaRich(){
    
    var strric=$('#strrich').val();
    //alert(strric);
    $.ajax({
	   type: "POST",
	   url: "<?php echo site_url()."/agenda/cerca_richiedenti"; ?>",
	   data: "par="+strric,
           dataType:"jsondata",
           success: function(data){
               // ciclo l'array
               //alert(data);
               if(data.length==0){
                     $('#NewRich').show();
                     $('#ButtRich').hide();
               }
               var myObject = eval('(' + data + ')');
               //alert(myObject.length);
               var         co='<tr> <p><b> Scelta Richiedente </b></p> </tr>';
                           co=co+'<th> ID </th>';
                           co=co+'<th> RICHIEDENTE </th>';
                           co=co+'<th> TELEFONO </th>';
                           co=co+'<th> SITO </th>';
                           co=co+'<th> EMAIL </th>';
                           co=co+'<th> AZIONI </th></tr>';
                           for(i=0; i<myObject.length; i++){
                                 id=myObject[i].id;
                                 richiedente=myObject[i].richiedente;
                                 tel=myObject[i].tel;
                                 sito=myObject[i].sito;
                                 email=myObject[i].email;
                                 co=co+'<tr>';
                                 co=co+'<td>'+id+'</td>';
                                 co=co+'<td>'+richiedente+'</td>';
                                 co=co+'<td>'+tel+'</td>';
                                 co=co+'<td>'+sito+'</td>';
                                 co=co+'<td>'+email+'</td>';
                                 var str="";
                                 str='<button type="button"  onclick="SetInput('+id+','+"'"+richiedente+"'"+');"> Seleziona</button>';
                                 //str+='<button type="button"  onclick="Visualizza('+id+');">Scheda Anagrafica</button>';
                                 co=co+'<td>'+str+'</td>';

                           }
                           co=co+'</tr>';
                           $('#contatti').html(co);
                           $('#contatti').show();
           }
     });
}



function hideshowTitolare(){

    tipoapp=$('#tipo').val();
    //alert(tipoapp);
    if (tipoapp=='S') $('#titl').show();
    else $('#titl').hide();

}

function valida(){


 tipoapp=$('#tipo').val();
 richiedente=$('#strrich').val();

 titolare=$('#strtitl').val();
 titolo=$('#titolo').val();




 if ( richiedente == '' ) {
     alert('prego inserire richiedente appuntamento');
     return false;
 }


 if (f_richiedente==0){
     alert('prego effettuare ricerca richiedente');
     return false;
 }

 if(titolo=='') {
     alert('prego inserire titolo appuntamento');
     return false;

    }

    data=$('#dataevt').val();
    //alert('data-->'+date+'data nuova-->'+data);
    ora=$('#ora').val();
    min=$('#min').val();

    id_titolare="<?php echo $id_titolare ?>";
    f_ora='ok';
    tipoop=$('#tipoop').val();
 
 //alert($('#tipoop').val());


 if ((tipoop=='add')||((tipoop=='mod')&&((data != date)||(ora != ora_app_inm)||(min != min_app_inm))))
    $.ajax({
                    type: "POST",
                    url: "<?php echo site_url()."/agenda/valida_ora"; ?>",
                    data: "par="+data+'-'+id_titolare+'-'+ora+'-'+min,
                    dataType:"jsondata",
                    async: false,
                    success: function(result){
                    //alert(result);
                    if (result > 0 )f_ora='nok';
                     
                   }
           });
if (f_ora=='nok'){
    alert('appuntamento non possibile cambiare orario');
    return false;
}

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

    ragsoc=$('#ragsoc').val();
    telef=$('#telef').val();
    sito=$('#sito').val();
    email=$('#email').val();
    emailsec=$('emailsec').val();
    cell1=$('cell1').val();
    cell2=$('cell2').val();
    note=$('note').val();
    parametri=nome+'-'+cognome+'-'+ragsoc+'-'+telef+'-'+sito+'-'+email+'-'+emailsec+'-'+cell1+'-'+cell2+'-'+note;
$.ajax({
            type: "POST",
            url: "<?php echo site_url()."/agenda/salva_newcontatto"; ?>",
            data: "par="+parametri,
            dataType:"jsondata",
            async: false,
            success: function(result){

            //id del nuovo conatto salvato in DB
            $('#id_richiedente').val(result);
            

   }
 });

 val=cognome+nome+ragsoc;
 $('#strrich').val(val);
 $('#newe').show();
 $('#contattifrm').hide();
 $('#NewRich').hide();
 f_richiedente=1;

}


</script>
<div id="cal"> <?php  echo $calendar; ?> </div>

<form id="newe"  style="display:none" method="post" action=' <?php echo site_url()."/agenda/gestapp" ?>'  >
<input style="display:none;"  id="tipoop" name="tipoop" value=""/>
<input style="display:none;"  id="id_app" name="id_app" value=""/>
<input style="display:none;"  id="id_richiedente" name="id_richiedente" value=""/>
<?php if(isset($id_titolare)) echo form_hidden("id_titolare",$id_titolare); ?>
<table>
        <tr><th>Data Evento</th>
            <td>
            <?php $opt = array('name'=>'data','size'=> '8'); $ro='id="dataevt"';
                      echo form_input($opt,'',$ro) ?>
                <b>ora:min</b>
            <?php $js='id="ora"'; $opt = array('6'=>'6','7'=>'7','8'=>'8','9'=>'9','10'=>'10',
                                               '11'=>'11','12'=>'12','13'=>'13','14'=>'14','15'=>'15',
                                               '16'=>'16','17'=>'17','18'=>'18','19'=>'19',
                                               '20'=>'20','21'=>'21','22'=>'22','23'=>'23');
                  echo form_dropdown('ora',$opt,'8',$js); ?>
                <b>:</b>
            <?php $js='id="min"';$opt=array('00'=>'00','15'=>'15','30'=>'30','45'=>'45');
                  echo form_dropdown('min',$opt,'00',$js); ?>
            </td>
        </tr>


        <tr><th>Richiedente</th>
            <td><?php $js='id="strrich" onkeydown="AttivaRic(1);"'; $opt = array('name'=>'richi','size'=> '40');
                      echo form_input($opt,'',$js) ?>
                <button type="button" style="display:none" id="ButtRich" onclick="CercaRich();">Cerca</button>
                <button type="button" style="display:none" id="NewRich"  onclick="NuovoContatto();">NuovoContatto</button>
            </td></tr>


        <tr><th>Tipo</th>
            <td><?php
            $opt = array('S'=> 'S','C'=>'C');
            //Il ruolo normal non può immettere appuntamenti Collettivi;
            if ($ruolo=='normal') $ro='disabled="disabled"';
            else $ro='';
            $js=$ro.' id="tipo" onchange="hideshowTitolare()"';
            echo form_dropdown('tipo',$opt,'S',$js); ?></td></tr>

         <tr id="titl"><th>Titolare</th>
            <td><?php $js='id="strtitl" readonly="readonly" '; $opt = array('name'=>'titl','size'=> '40');
                      echo form_input($opt,'',$js) ?>
                </td></tr>
        <tr><th>Titolo</th>
            <td><?php $js='id="titolo"';$opt = array('name'=>'titol','size'=> '80');
                      echo form_input($opt,'',$js) ?></td></tr>
        <tr><th>Descrizione</th>
            <td><?php $js='id="descrizione"'; $opt = array('name'=>'descr','rows'=> '10','cols'=>'60');
                      echo form_textarea($opt,'',$js)?></td></tr>
        <tr><td><?php  $js='onclick="return valida();"';echo form_submit('salva','salva',$js) ?> </td></tr>
        

     </table>
        </form>
<br>
<table id="contatti" style="display:none"></table>

<div align="left" style="width:800px;">

   <fieldset id="contattifrm" style="display:none;"><legend>Gestione Contatti (*)Campi obbligatori</legend>

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
    <button type="button" name="salva" value="Salva" onclick="return validaform();">Salva</button>
    </fieldset>
</div>
