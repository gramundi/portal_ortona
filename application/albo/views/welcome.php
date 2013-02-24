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
al=$('#dati tr:eq('+row+') td:eq(7)').text();

datac= new Date();

arr2 = al.split("-");

//Creiamo i due oggetti “Date” delle due date.
d2 = new Date(arr2[2],arr2[1]-1,arr2[0]);

//Ricaviamo con getTime i timestamp che potremo facilmente confrontare.
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
</script>

<h1><?php echo $title;  ?></h1>
<h4><b>Riepilogo Registro degli atti: ALBO PRETORIO COMUNE ORTONA</b></h4>
<form method="post" action='<?php echo site_url()."/albo/set_filtro" ?>'  >
    <table id="ricerca">
        <tr>
            <th>richiedente</th>
            <th>oggetto</th>
            <th>riferimento</th>
            <th>tipo</th>
            <th>stato</th>
        </tr>
        <tr>
            <td><?php echo form_input('ente',$fil1)?> </td>
            <td><?php echo form_input('oggetto',$fil2)?> </td>
             <td><?php echo form_input('rif',$fil3)?> </td>
            <td><?php echo form_input('tipo',$fil4)?> </td>
            <td><?php $options = array('T' =>'Tutte','I'=>'Inserite','A'  => 'Annulate','C'   => 'Certificate', 'P' => 'Pubblicate');
                      echo form_dropdown('stato', $options, 'large'); ?>
        </tr>
        <tr><td><?php $par='id="ricerca" onclick="refresh();"'; echo form_submit('ricerca', 'Cerca'); ?></td></tr>
    </table>
</form>
<br>
<br>
<table id="dati">
    <tr>
        <th>cod</th>
        <th>rif</th>
        <th>richiedente</th>
        <th>tipo</th>
        <th>oggetto</th>
        <th>gestore</th>
        <th>dal</th>
        <th>al</th>
        <th>gg</th>
        <th>stato</th>
        <?php $ruolo=$this->session->userdata('ruolo'); if ($ruolo!='normal') echo '<th>azioni</th>'; ?>
          
    </tr>
    <?php  if($registro === false): ?>
    <p><b>Non ci Sono registrazioni</b></p>
<?php  else: ?>
<?php $i = 1;foreach($registro as $m): ?>
    <tr><?php $id=$m['id']; ?>
        <td><?php echo $m['codice']?></td>
        <td><?php echo $m['rif']?></td>
        <td><?php echo $m['ente'] ?></td>
        <td><?php echo $m['tipo'] ?></td>
        <td><?php echo $m['oggetto'] ?></td>
        <td><?php echo $m['responsabile'] ?></td>
        <td><?php echo $m['dal'] ?></td>
        <td><?php echo $m['al']?></td>
        <td><?php echo $m['periodo']?></td>
        <td><?php echo $m['stato']?></td>
        <?php $ruolo=$this->session->userdata('ruolo'); 
              switch($ruolo) {

                  case 'publisher':
                            $id_user=$this->session->userdata('id_user');
                            if ($id_user==$m['id_utente']) 
                                switch ($m['stato']) {

                                    case 'I':
                                        echo '<td>';
                                        echo anchor("albo/clona/".$id, "Clona",'class=button').
                                             anchor("albo/addmod/mod/".$id, "Modifica",'class=button').
                                             anchor("albo/addmod/del/".$id, "Cancella", array('class' => 'button','onclick' => "return confirm('Are you sure that you want to delete ?')")).
                                             anchor("albo/pubblica/".$id, "Pubblica",'class=button');
                                        echo '</td>';
                                        break;

                                    case 'P':
                                        echo '<td>';
                                        echo
                                        anchor("albo/clona/".$id, "Clona",'class=button').
                                        anchor("albo/addmod/mod/".$id, "Modifica", array('class' => 'button','onclick'=>"return check_constr($i,'m')")).
                                        anchor("albo/addmod/cer/".$id, "Certifica",array('class' => 'button','onclick'=>"return check_constr($i,'c')"));
                                        echo '</td>';
                                        break;
                                    case 'C':
                                        echo '<td>';
                                        echo anchor("albo/addmod/cer/".$id, "Certifica",array('class' => 'button','onclick'=>"return check_constr($i,'c')"));
                                        echo '</td>';
                                        break;
                                }
                            else  echo "<td>Nessuna Azione</td>";
                            break;
                   case 'resppub':
                            switch ($m['stato']){
                                    case 'I':
                                        echo '<td>';
                                        echo
                                        anchor("albo/clona/".$id, "Clona",'class=button').
                                        anchor("albo/addmod/mod/".$id, "Modifica",'class=button').
                                        anchor("albo/addmod/del/".$id, "Cancella", array('class' => 'button','onclick' => "return confirm('Are you sure that you want to delete ?')")).
                                        anchor("albo/pubblica/".$id, "Pubblica",'class=button');
                                        echo '</td>';
                                        break;
                                    case 'P':
                                        echo '<td>';
                                        echo
                                        anchor("albo/clona/".$id, "Clona",'class=button').
                                        anchor("albo/addmod/mod/".$id, "Modifica", array('class' => 'button','onclick'=>"return check_constr($i,'m')")).
                                        anchor("albo/addmod/cer/".$id, "Certifica",array('class' => 'button','onclick'=>"return check_constr($i,'c')"));
                                        echo '</td>';
                                        break;
                                    case 'C':
                                        echo '<td>';
                                        echo anchor("albo/addmod/cer/".$id, "Certifica",array('class' => 'button','onclick'=>"return check_constr($i,'c')"));
                                        echo '</td>';
                                        break;
                            }
                           break;
                   case 'normal':break;
              }
                                    ?>
        </tr>
    <?php $i++; endforeach ?>
   <?php  endif; ?>
</table>
<br>
<table>
    <tr> <td align="center"><?php echo 'Pagina:'.$this->pagination->create_links(); ?></td></tr>
</table>
<?php  require dirname(__FILE__).'/includes/footer.php'; ?>

