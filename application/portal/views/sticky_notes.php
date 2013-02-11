<?php  require dirname(__FILE__).'/includes/head.php'; ?>

 
<script type="text/javascript">

rif_pst=0;

function crea_postit () {    
   //Create postick
        rif_pst+=1;
        //alert(rif_pst);
        bs_url="<?php echo base_url(); ?>";
        postit='<div class="postick" id='+rif_pst+' style="left:20px;top:70px">';
        postit+='<input type="hidden"  name="stato" value="t" id="stato"> </input>'
        postit+='<span class="delete" onclick="cancella('+rif_pst+')"><IMG SRC="'+bs_url+'/images/close.png" height="10" width="10"></span>';
        postit+='<div contenteditable class="editable" id="edit"></div>';
        postit+='<span class="salva"  onclick="salva('+rif_pst+')"><IMG SRC="'+bs_url+'/images/add.png" height="10" width="10"></span></div>';
        //alert(postit);
        $('#board').append(postit);
        $(".postick").draggable({
            cancel: '.editable'
        });

    

}

function cancella(rif){
    //alert(rif);
    stato=$('#'+rif).find('#stato').attr("value");
    //alert(stato);
    if (stato=='t') $('#'+rif).remove();
    else {
        //alert('call back');
        content=$('#'+rif).contents('#edit').text();
        $('#'+rif).remove();
        //Call Back to delete post it in DB
        $.ajax({
                type: "POST",
                async: false,
                url: "<?php echo site_url()."/strumenti/gest_pst"; ?>",
                data: "par="+'del'+'-'+rif+'-'+content,
                dataType:"jsondata",
                success: function(data){
                    //alert(data);
                
                }
            });
         $('#msg').html('Nota Cancellata');
         $('#msg').show().animate({ opacity: "hide" },3000);

    }

}

function salva(rif){

    //Cambio stato
    $('#'+rif).find('#stato').attr("value","s");

    content=$('#'+rif).contents('#edit').text();
    //Call Back to record post it in DB

    $.ajax({
                type: "POST",
                async: false,
                url: "<?php echo site_url()."/strumenti/gest_pst"; ?>",
                data: "par="+'rec'+'-'+rif+'-'+content,
                dataType:"jsondata",
                success: function(data){
                    //alert(data);
                }
            });
    $('#msg').html('Nota Salvata');
    $('#msg').show().animate({ opacity: "hide" },3000);
    //Call back ajax to save the notes
}


$(document).ready(function () {

rif_pst=<?php echo $max_rif?>;
$(".postick").draggable({
            cancel: '.editable'
        });

});


</script>
<input type="button" value="Add Post-it"  id="btn-addNote" onclick="crea_postit()"/>
<b id="msg" style="display:none"></b>
<div id="board">
  <?php  if($postit > 0) {
            $toppos=70;
            $leftpos=20;
            foreach($postit as $m){
               $toppos+=15;
               $leftpos+=15;
               $rif=$m['rif'];
               $testo=$m['testo'];
               $postit='';
               $postit='<div class="postick" id="'.$rif.'" style="left:'.$leftpos.'px;top:'.$toppos.'px" >';
               $postit=$postit.'<input type="hidden" id="stato" name="stato" value="s"> </input>';
               $postit=$postit.'<span class="delete" onclick="cancella('.$rif.')"><IMG SRC="'.base_url().'/images/close.png" height="10" width="10"></span>';
               $postit=$postit.'<div contenteditable class="editable" id="edit">'.$testo.'</div>';
               $postit=$postit.'<span class="salva"  onclick="salva('.$rif.')"><IMG SRC="'.base_url().'/images/add.png" height="10" width="10"></span></div>';
               echo $postit;
               
            }
  } ?>
</div>
</body>
</html>