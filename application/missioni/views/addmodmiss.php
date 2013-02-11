<script type="text/javascript">

var dtCh= "/";
var timeCh=":";
var minYear=1900;
var maxYear=2100;

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
	month=parseInt(strMonth,10);
	day=parseInt(strDay,10);
	year=parseInt(strYr);
	if (pos1==-1 || pos2==-1){
		alert("The date format should be : mm/dd/yyyy");
		return false;
	}
	if (strMonth.length<1 || month<1 || month>12){
		alert("Please enter a valid month");
		return false;
	}
	if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
		alert("Please enter a valid day");
		return false;
	}
	if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
		alert("Please enter a valid 4 digit year between "+minYear+" and "+maxYear);
		return false;
	}
	if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
		alert("Please enter a valid date");
		return false;
	}
return true;
}

function isTime(timeStr){

    var pos1=timeStr.indexOf(timeCh);
    var strHour=timeStr.substring(0,pos1);
    var strMin=timeStr.substring(pos1+1,timeStr.length+1);

    hh=parseInt(strHour,10);
    mm=parseInt(strMin,10);
    if ( hh > 24  || hh < 1 || strHour.lenght < 2 ){
        alert("Please enter a valid hour");
	return false;
    }

    if ( mm > 59  || mm < 0 || strMin.length < 2){
        alert("Please enter a valid minute");
	return false;
    }
  return true;
}

function get_int_min(ora){

    var pos1=ora.indexOf(timeCh);
    var strhh=ora.substring(0,pos1);
    var strmm=ora.substring(pos1+1);
    if (strhh.charAt(0)=="0" && strhh.length>1) strhh=strhh.substring(1);
    if (strmm.charAt(0)=="0" && strmm.length>1) strmm=strmm.substring(1);
    hhint=parseInt(strhh,10)*60;
    mmint=parseInt(strmm,10);

    return(hhint+mmint)



}

function ValidaDate(){

        var data_p=$('#data_p').val();
        var data_r=$('#data_r').val();
        var strdate1=data_p.substr(0,10);
        var strdate2=data_r.substr(0,10);
        var ora_p=data_p.substr(11);
        var ora_r=data_r.substr(11);

        var strOgg=$('#oggetto').val();
        var strLoc=$('#localita').val();
        
        if (((isDate(strdate1))== false) ||((isDate(strdate2))==false)){
                alert("Per Favore corregere le Date");
		return false
	}

        //alert (ora_p+'-'+ora_a);
        if (((isTime(ora_p))== false) ||((isTime(ora_r))==false)){
                alert("Per Favore corregere gli Orari");
		return false
	}


        data1int = strdate1.substr(6)+strdate1.substr(3, 2)+strdate1.substr(0, 2);
	data2int = strdate2.substr(6)+strdate2.substr(3, 2)+strdate2.substr(0, 2);
        //alert(data1int-data2int);
	//controllo se la seconda data è successiva alla prima la tecnica
        // sommo interi anno mese giorno
        if (data2int-data1int < 0) {
            alert("La data di rientro deve essere successiva alla data di partenza");
            return false;
        }

        if (data2int-data1int == 0) {
            
            var ora_p_in_min=get_int_min(ora_p);
            var ora_r_in_min=get_int_min(ora_r);
            //alert (ora_r_in_min - ora_p_in_min);
            if (ora_r_in_min - ora_p_in_min < 0) {
                alert("L'ora di rientro deve essere successiva all'ora di partenza");
                return false
            }

        }


        if (strOgg==false){
                alert("Per Inserire Oggetto Missione");
		return false
	}

        if (strLoc==false){
                alert("Per Favore inserie Località");
		return false
	}
       
        return true;
	
 }


</script>

<?php  require dirname(__FILE__).'/includes/head_1.php'; ?>
<html>
<body>

<form method="post" action='<?php echo site_url()."/mission/insupd/".$op ?>' onSubmit=" return ValidaDate();">
<?php if(isset($missione)) echo form_hidden("missione",$missione); ?>
<?php if(isset($id_user))  echo form_hidden("id_user",$id_user); ?>
<?php if(isset($username)) echo form_hidden("missione",$username); ?>
<table class="form">
<tbody>
<tr><h1><?php echo $title ?></h1><tr>
<tr>
    <th><label for="oggetto">*oggetto missione</label></th>
    <td><?php $data='id="oggetto" size="30"'; echo form_input('oggetto', $ogg,$data)?> </td>
</tr>    
 <tr>
    <th><label for="capitolo">*capitolo di spesa</label></th>
    <td><?php
            foreach ($capitoli as $row)
                $options[$row['voce']]=$row['voce'];
            $par ='id="capitolo" ';
            echo form_dropdown('capitolo',$options,$capitolo,$par); ?>
    </td>
    

</tr>
<tr>
    <th><label for="localita">Localita</label></th>
    <td><?php $data=' id="localita" size="30"';echo form_input('localita', $loc,$data)?></td>
</tr>
<tr>
    <th><label for="datap">data ora partenza(gg/mm/aaaa hh:mm)</label></th>
    <td><?php $da=date_format(date_create($data_p),'d/m/Y H:i');($spese)?$ro='readonly="readonly"':$ro='';$data=' id="data_p"'.$ro;echo form_input('data_p', $da,$data)?></td>
    <th><label for="dataa">data ora rientro(gg/mm/aaaa hh:mm)</label></th>
    <td><?php $da=date_format(date_create($data_r),'d/m/Y H:i');($spese)?$ro='readonly="readonly"':$ro='';$data=' id="data_r"'.$ro;echo form_input('data_r', $da,$data)?></td>
</tr>
<td><?php echo form_submit("invia","Salva")?> </td>
<tr>
</tbody>
</table>
</form>
<a href=<?php echo site_url().'/mission'?>> Riepilogo Missioni</a>
</body>
</html>
