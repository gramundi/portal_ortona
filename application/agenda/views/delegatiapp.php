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


function changedelegato(selnum){
    alert('changedel');
    selid='#del'+selnum;
    alert('select da cambiare'+$(selid).val());
    
}

function delega(selnum,id_appuntamento){

strric='';
selid='#del'+selnum;
$(selid).removeAttr('disabled');

$.ajax({
            type: "POST",
            async: false,
            url: "<?php echo site_url()."/agenda/cerca_titolari"; ?>",
            data: "par="+strric+'-'+id_appuntamento,
            dataType:"jsondata",
            success: function(data){
                alert(data);

                    var myObject = eval('(' + data + ')');
                    for(j=0; j<myObject.length; j++){
                        id_titolare=myObject[j].id_user;
                        titolare=myObject[j].titolare;
                        alert(selid);
                        $(selid).append(new Option(titolare, id_titolare));
                    }
                }

        });

}
    
</script>

<table id="delegatipertitolari">
    <tr><b>Delegati Per Appuntamento</b></tr>
    <tr>
        <th>titolare</th>
        <th>delegato</th>
        <th>azioni</th>
    </tr>
    
<?php $i = 1;foreach($delegati as $m): ?>

    <tr>
        <td><?php echo $m['titolare'] ?></td>
        <td><?php if ($m['delegato']==null) $del='nessun delegato';
                  else $del=$m['delegato'];
                  $opt = array('0'=>$del);$js='disabled="disabled" id="del'.$i.'" onchange="changedelegato('.$i.')"';
                  echo form_dropdown('delegato',$opt,'',$js); ?> </td>
        <td><?php echo '<button id="bt'.$m['titolare'].'del" type="button"  onclick="delega('.$i.','.$id_appuntamento.');" >Delega</button>' ?>
        </td>
    </tr>
    <?php $i++; endforeach ?>
</table>
<br>

<form method="post" action=' <?php  $url='/riepilogoapp/gest_app';echo site_url().$url ?>'  >
<?php  $js='onclick="return valida();"';echo form_submit('salva','salva',$js) ?>
</form>

<?php  require dirname(__FILE__).'/includes/footer.php'; ?>

