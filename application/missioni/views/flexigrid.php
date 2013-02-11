<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Flexigrid Implemented in CodeIgniter</title>
<link href="<?php echo $this->config->item('base_url');?>css/mainstyle.css" rel="stylesheet" type="text/css"  />
<link href="<?php echo $this->config->item('base_url');?>css/flexigrid.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>js/jquery.pack.js"></script>
<script type="text/javascript" src="<?php echo $this->config->item('base_url');?>js/flexigrid.pack.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/script.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/scriptaculous.js"></script>
</head>
<body>
<?php
echo $js_grid;
?>
<script type="text/javascript">



function test(com,grid)
{
    if (com=='Seleziona Tutti')
    {
		$('.bDiv tbody tr',grid).addClass('trSelected');
    }
    
    if (com=='Deseleziona')
    {
		$('.bDiv tbody tr',grid).removeClass('trSelected');
    }
    
    if (com=='Cancella')
        {
           if($('.trSelected',grid).length>0){
			   if(confirm('Delete ' + $('.trSelected',grid).length + ' items?')){
		            var items = $('.trSelected',grid);
		            var itemlist ='';
		        	for(i=0;i<items.length;i++){
				        itemlist+= items[i].id.substr(3)+",";
					}
				$.ajax({
					   type: "POST",
					   url: "<?php echo site_url("/ajax/deletec");?>",
					   data: "items="+itemlist,
					   success: function(data){
					   	$('#flex1').flexReload();
					  	alert(data);
					   }
				});
			    }
			} else {
				return false;
			} 
}  }

function aggiungi(com,grid)
{
   
  $('#newmodspesa').show();
  $('#op').val('add');
   
}

function modifica(com,grid)
{
    //alert('modifica'+com);
    if($('.trSelected',grid).length==1){
			   if(confirm('Modifica  item?')){
		            var items = $('.trSelected',grid);
                            var des,qta,cu,tipo,id_s,data;
                            //Get valori dalla tabella
                            tipo=$('.trSelected td:nth-child(1) div').text();
                            id_s=$('.trSelected td:nth-child(2) div').text();
                            data=$('.trSelected td:nth-child(3) div').text();
                            des=$('.trSelected td:nth-child(4) div').text();
                            qta=$('.trSelected td:nth-child(5) div').text();
                            cu=$('.trSelected td:nth-child(6) div').text();
                            //alert(tipo+'-'+id_s+'-'+des+'-'+qta+'-'+cu);
                            //$('#missione').val();
                            var gg=data.substr(8,10);
                            var mo=data.substr(4,7);
                            var aa=data.substr(0,4);
                            mo=mo.substr(1,2);
                            data=gg+'/'+mo+'/'+aa;
                            $('#newmodspesa').show();
                            $('#op').val('mod');
                            $('#id_s').val(id_s);

                            $('#tipo').val(tipo);
                            $('#descrizione').val(des);
                            $('#dataspesa').val(data);
                            $('#qta').val(qta);
                            $('#costo').val(cu);
				}
			} else {
				return false;
			}
    }


function check() {
//alert ('ok');
var missione=$('#missione').val();
var dataspesa=$('#dataspesa').val();
var data_check="";
//alert(missione+dataspesa);
$.ajax({
	   type: "POST",
	   url: "<?php echo site_url("/Operation/getDateMissione");?>",
	   data: "par="+missione+","+dataspesa,
           //dataType:"jsondata",
           success:function(data){
               //alert(data);
               if (data.length==0) {
                   alert('Data Spesa Non valida');
                   $("#dataspesa").click(function(){ $(this).attr({ value: '' }); });
               }
               else data_check='ok';
           },
           async:false
       });
if (data_check=='ok') return true;
else return false;
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

function stripCharsInBag(s, bag){
	var i;
    var returnString = "";
    //alert('stripchars');
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++){
        var c = s.charAt(i);
        //Se il car c non è / concateno la stringa
        if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
}



function daysInFebruary (year){

    //alert('days in febrruary');
	// February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
}

function DaysArray(n) {
	for (var i = 1; i <= n; i++) {
		this[i] = 31;
		if (i==4 || i==6 || i==9 || i==11) {this[i] = 30;}
		if (i==2) {this[i] = 29;}
   }
   return this;
}


function isDate(dtStr){
        var dtCh= "/";
        var minYear=1900;
        var maxYear=2100;
        
        
        var daysInMonth = DaysArray(12);
	var pos1=dtStr.indexOf(dtCh);
	var pos2=dtStr.indexOf(dtCh,pos1+1);
	var strDay=dtStr.substring(0,pos1);
	var strMonth=dtStr.substring(pos1+1,pos2);
	var strYear=dtStr.substring(pos2+1);
	strYr=strYear;
	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1);
	if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1);
	for (var i = 1; i <= 3; i++) {
		if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1);
	}
	month=parseInt(strMonth);
	day=parseInt(strDay);
	year=parseInt(strYr);
        
	if (pos1==-1 || pos2==-1){
		alert("La data deve essere nel formato : gg/mm/aaaa");
		return false;
	}
	if (strMonth.length < 1 || month<1 || month>12){
		alert("Mese non valido.Per favore inserire il mese a due cifre");
		return false;
	}
	if (strDay.length < 1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
		alert("Giorno non valido. Per favore inserire il giorno a due cifre");
		return false;
	}
	if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
		alert("Please enter a valid 4 digit year between "+minYear+" and "+maxYear);
		return false;
	}
	if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
		alert("Data non valida");
		return false;
	}
        return true;
}


function gestione_tipi(){

    $('#descrizione').val('');
    $('#qta').val('');
    $('#costo').val('');
    
}

function get_int_Date(dtStr){


        var dtCh= "/";
        var pos1=dtStr.indexOf(dtCh);
	var pos2=dtStr.indexOf(dtCh,pos1+1);
	var strDay=dtStr.substring(0,pos1);
	var strMonth=dtStr.substring(pos1+1,pos2);
	var strYear=dtStr.substring(pos2+1);
	strYr=strYear;

	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1);
	if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1);
	for (var i = 1; i <= 3; i++) {
		if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1);
	}
	month=parseInt(strMonth);
	day=parseInt(strDay);
	year=parseInt(strYr);
        return(month+day+year);

}



function validaform(){


missione=$('#missione').val();
tipo=$('#tipo').val();
id_s=$('#id_s').val();
des=$('#descrizione').val();
datas=$('#dataspesa').val();
qta=$('#qta').val();
cu=$('#costo').val();
op=$('#op').val();
data_p="<?php echo date_format(date_create($periodo['partenza']),'d/m/Y h:m'); ?>";
data_r="<?php echo date_format(date_create($periodo['rientro']),'d/m/Y h:m'); ?>";


//alert(op+'-'+missione+'-'+tipo+'-'+id_s+'-'+des+'-'+datas+'-'+qta+'-'+cu);

if ((qta=='')||(cu=='')||(datas=='')) {
    alert('i dati quantita|costo|dataspesa sono obbligatori');
    return false;
    }
if (isDate(datas)== false ) return false;

int_data_p=get_int_Date(data_p);
int_data_r=get_int_Date(data_r);
int_datas=get_int_Date(datas);

if ((int_datas < int_data_p) || ( int_datas > int_data_r  )) {
    alert ('spesa non ammissibile data spesa non rientra nel periodo trasferta');
    return false;
}

if ( tipo == 'Pasti' ) {
    //Controllo missione maggiore di 8 ore
    oremiss=parseInt("<?php echo $oremiss; ?>");
    //alert(oremiss);
    if (oremiss < 8) {
        alert ('spesa non ammissibile pasti concessi solo se missione > 8 ore');
        return false;
    }
    
}



$.ajax({
		type: "POST",
		url: "<?php echo site_url("/ajax/addmod_row");?>",
		data: "op="+op+"&missione="+missione+"&id_s="+id_s+"&tipo="+tipo+"&des="+des+"&data="+datas+"&qta="+qta+"&cu="+cu,
		success: function(data){
		 //alert(data);
                },
            async: false
	});

}


</script>
<p><b>riepilogo spese trasferta: <?php echo $title?>----- Periodo trasferta dal: <?php echo

date_format(date_create($periodo['partenza']),'d/m/Y H:m') ?>
      al: <?php echo date_format(date_create($periodo['rientro']),'d/m/Y H:m') ?></b></p>

<table id="flex1" style="display:none"></table>
<hr></hr>
<form id="newmodspesa" style="display:none" action="">

    <input name="id_spesa" id="id_s" type="hidden" value=""></input>
    <input name="operazione" id="op" type="hidden" value=""></input>
    <table class="form">
<tbody>
<tr>
    <th><label for="IdMiss">ID Missione</label></th>
    <td><?php $par = 'id="missione" readonly="readonly"'; echo form_input("missione",$missione,$par); ?>
    </td>
</tr>
<tr>
    <th><label for="tipospesa">*Tipo Spese</label></th>
    <td><?php
            foreach ($tspese as $row)
                $options[$row['tipo']]=$row['tipo'];
            $par ='id="tipo" ';
            echo form_dropdown('tipo',$options,'',$par); ?>
    </td>
    

</tr>
<tr>
    <th><label for="Descrizione">Descrizione</label></th>
    <td><?php $par ='id="descrizione" size=30'; echo form_input('des', '',$par)?></td>
</tr>
<tr>
    <th><label for="Data">Data(gg/mm/aaaa)</label></th>
    <td><?php $par ='id="dataspesa" onblur="check();"';echo form_input('data','',$par)?></td>
</tr>
<tr>
    <th><label for="Quantita">Quantita</label></th>
    <td><?php $par ='id="qta"'; echo form_input('qta','',$par)?></td>
</tr>
<tr>
    <th><label for="Costo">Costo</label></th>
    <td> <?php $par ='id="costo"'; echo form_input('cu','',$par)?></td>
</tr>
    <tr><td><input type="submit" name="invia" value="Salva" onclick="return validaform();"></input></td></tr>
</tbody>
</table>
</form>
<hr></hr>
<a href="<?php echo site_url() ?>"> Riepilogo Missioni</a>
</body>
</html>