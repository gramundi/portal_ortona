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
//Controlla se la data corrente è maggiore della data di scadenza pubblicazione
function check_constr(id,op){


var row=id;

//alert('implementare controllo Modifica solo se Periodo di pubblicazione non scaduto');

al=$('#'+row).children(':eq(7)').html();

datac= new Date();


arr2 = al.split("-");

//Creiamo i due oggetti “Date” delle due date.
d2 = new Date(arr2[2],arr2[1]-1,arr2[0]);

//Ricaviamo con getTime i timestamp che possiamo facilmente confrontare.
r1 = datac.getTime();
r2 = d2.getTime();


switch(op){


case 'm':   //alert('modifica');
            if (r1 <= r2) return true;
            else {
                alert ('Modifica non possibile Periodo di pubblicazione scaduto');
                return false;
                }
            break;

case 'c':   //alert('certifica');
            if (r1 >= r2) return true;
            else {
                alert ('Certificazione non possibile Periodo di pubblicazione non scaduto');
                return false;
                }
            break;
}
}

$(function(){


$('.rowel').click(function(){

 console.log($(this).attr('id'));
 cod=$(this).children(':eq(0)').html();
 oggetto=$(this).children(':eq(1)').html();
 richiedente=$(this).children(':eq(2)').html();
 gestore=$(this).children(':eq(5)').html();
 
 

});
});




</script>

<h1><?php echo $title;  ?></h1>

<h4><b>Riepilogo Registro degli atti: ALBO PRETORIO COMUNE ORTONA</b></h4>
<form method="post" action='<?php echo site_url()."/albo/set_filtro" ?>'  >
    <div id="form-ricerca">
        <div class="rowhead">
            <div class="cell">richiedente</div>
            <div class="cell">oggetto</div>
            <div class="cell">gestore</div>
            <div class="cell">riferimento</div>
            <div class="cell">tipo</div>
            <div class="cell">stato</div>
        </div>
        <div class="rowel">
            <div class="cell"><?php echo form_input('ente',$fil1)?> </div>
            <div class="cell"><?php echo form_input('oggetto',$fil2)?> </div>
            <div class="cell"><?php echo form_input('gestore',$fil3)?> </div>
            <div class="cell"><?php echo form_input('rif',$fil4)?> </div>
            <div class="cell"><?php echo form_input('tipo',$fil5)?> </div>
            <div class="cell"><?php $options = array('T' =>'Tutte','I'=>'Inserite','A'  => 'Annulate','C'   => 'Certificate', 'P' => 'Pubblicate');
                      echo form_dropdown('stato', $options, $fil6); ?>
            </div>    
            
        </div>
        
            <?php $par='id="ricerca" onclick="refresh();"'; echo form_submit('ricerca', 'Cerca'); ?>
            
        
    </div>
</form>
<br>
<br>
<div id="dati">
    <div class="rowhead">    
        <div class="cell">cod</div>
        <div class="cell">rif</div>
        <div class="cell">richiedente</div>
        <div class="cell">tipo</div>
        <div class="cell">file</div>
        <div class="cell">gestore</div>
        <div class="cell">dal</div>
        <div class="cell">al</div>
        <div class="cell">gg</div>
        <div class="cell">stato</div>
    </div>
    <?php  if($registro === false): ?>
    <p><b>Non ci Sono registrazioni</b></p>
<?php  else: ?>
<?php $i = 1;foreach($registro as $m): ?>
    <hr> 
    <div class="rowel" id="<?php echo $id=$m['id']; ?>">
      
        <div class="cell"><?php echo $m['codice'] ?></div>
        <div class="cell"><?php if ($m['rif']) echo $m['rif'] ?> </div>
        <div class="cell"><?php echo $m['ente'] ?></div>
        <div class="cell"><?php echo $m['tipo'] ?></div>
        
        <div class="cell"><?php if ($m['file']) {
            $atts = array('wclassth'=> '800','height'=> '600','scrollbars' => 'yes','status' => 'yes','resizable'  => 'yes',
              'screenx'    => '0','screeny'    => '0'
            );
            $anno=substr($m['file'],0,4);
            echo anchor_popup(base_url().$anno.'/'.($m['file']),'doc',$atts);
       
            }?> 
        </div>  
        <div class="cell"><?php echo $m['responsabile'] ?></div>
        <div class="cell"><?php echo $m['dal'] ?></div>
        <div class="cell"><?php echo $m['al']?></div>
        <div class="cell"><?php echo $m['periodo']?></div>
        <div class="cell"><?php echo $m['stato']?></div>
         </div>
        <?php
       
        switch($ruolo) {

                  case 'publisher':
                            $id_user=$this->session->userdata('id_user');
                            
                            if ($id_user==$m['id_utente'])
                                 switch ($m['stato']) {
                                      case 'I':
                                        echo '<div class="azioni">';
                                        echo anchor("albo/clona/".$id, "Clona",'class=button').
                                             anchor("albo/addmod/mod/".$id, "Modifica",'class=button').
                                             anchor("albo/addmod/del/".$id, "Cancella", array('class' => 'button','onclick' => "return confirm('Are you sure that you want to delete ?')")).
                                             anchor("albo/pubblica/".$id, "Pubblica",'class=button');
                                        echo '</div>';
                                        break;

                                    case 'P':
                                        echo '<div row="azioni">';
                                        echo
                                        anchor("albo/clona/".$id, "Clona",'class=button').
                                        anchor("albo/addmod/mod/".$id, "Modifica", array('class' => 'button','onclick'=>"return check_constr($id,'m')")).
                                        anchor("albo/pubblica/".$id, "Pubblica",'class=button').
                                        anchor("albo/addmod/cer/".$id, "Certifica",array('class' => 'button','onclick'=>"return check_constr($id,'c')"));
                                        echo '</div>';
                                        break;
                                    case 'C':
                                        echo '<div class="azioni">';
                                        echo anchor("albo/addmod/cer/".$id, "Certifica",array('class' => 'button','onclick'=>"return check_constr($id,'c')"));
                                        echo '</div>';
                                        break;
                                }
                               else  echo "<div>Nessuna Azione</div>";
                               break;
                    case 'resppub' || 'admin':
                            switch ($m['stato']){
                                    case 'I':
                                        echo '<div class="azioni">';
                                        echo
                                        anchor("albo/clona/".$id, "Clona",'class=button').
                                        anchor("albo/addmod/mod/".$id, "Modifica",'class=button').
                                        anchor("albo/addmod/del/".$id, "Cancella", array('class' => 'button','onclick' => "return confirm('Are you sure that you want to delete ?')")).
                                        anchor("albo/pubblica/".$id, "Pubblica",'class=button');
                                        echo '</div>';
                                        break;
                                    case 'P':
                                        echo '<div class="azioni">';
                                        echo
                                        anchor("albo/clona/".$id, "Clona",'class=button').
                                        anchor("albo/addmod/mod/".$id, "Modifica", array('class' => 'button','onclick'=>"return check_constr($id,'m')")).
                                        anchor("albo/pubblica/".$id, "Pubblica",'class=button').
                                        anchor("albo/addmod/cer/".$id, "Certifica",array('class' => 'button','onclick'=>"return check_constr($id,'c')"));
                                        if ($ruolo=='admin')//Administratore Può anche bonificare
                                            echo anchor("albo/bonifica/bon/".$id, "Bonifica",array('class' => 'button'));
                                        echo '</div>';
                                        break;
                                    case 'C':
                                        echo '<div class="azioni">';
                                        if ($ruolo=='admin')//Administratore Può anche bonificare
                                            echo
                                            anchor("albo/addmod/cer/".$id, "Certifica",array('class' => 'button','onclick'=>"return check_constr($id,'c')")).
                                            anchor("albo/bonifica/bon/".$id, "Bonifica",array('class' => 'button'));
                                        else
                                            echo
                                            anchor("albo/addmod/cer/".$id, "Certifica",array('class' => 'button','onclick'=>"return check_constr($id,'c')"));
                                        echo '</div>';
                                        break;
                            }
                           break;
                           case 'normal':break;




                              }
                            ?>
       
       
        <div class="oggetto"><div class="rowhead">Oggetto: </div> <p> <?php echo $m['oggetto'] ?></p>
        <hr>
        </div>
        
       
    <?php $i++; endforeach ?>
   <?php  endif; ?>
</div>     
<h6><?php echo 'filtrati '.$num_rows_fil.' su '.$num_rows.' elementi totali' ?></h6>
<div align="center"><?php echo 'Pagina:'.$this->pagination->create_links(); ?></div>

<?php  require dirname(__FILE__).'/includes/footer.php'; ?>
