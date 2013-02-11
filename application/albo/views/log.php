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
function test(){
    alert('ciao');
    return true;
}

</script>

<h1><?php echo $title;  ?></h1>
<h3><p><b>Riepilogo Registro degli atti: ALBO PRETORIO COMUNE ORTONA</b></p></h3>

<br>
<table>
    <tr>
        <th>id</th>
        <th>codice</th>
        <th>ente</th>
        <th>Oggetto</th>
        <th>responsabile</th>
        <th>dal</th>
        <th>al</th>
        <th>periodo</th>
        <th>stato</th>
        <?php $ruolo=$this->session->userdata('ruolo'); if ($ruolo=='Publisher') ?>
          <th>azioni</th>
    </tr>
    <?php  if($registro === false): ?>
    <p><b>Non ci Sono registrazioni</b></p>
<?php  else: ?>
<?php $i = 1; foreach($registro as $m): ?>
    <tr>
        <td><?php $id=$m['id']; echo $id; ?></td>
        <td><?php echo $m['codice']?></td>
        <td><?php echo $m['ente'] ?></td>
        <td><?php echo $m['oggetto'] ?></td>
        <td><?php echo $m['responsabile'] ?></td>
        <td><?php echo $m['dal'] ?></td>
        <td><?php echo $m['al']?></td>
        <td><?php echo $m['periodo']?></td>
        <td><?php echo $m['stato']?></td>
        <td>
        <?php $ruolo=$this->session->userdata('ruolo'); 
              switch($ruolo) {

                  case 'publisher':
                            $id_user=$this->session->userdata('id_user');
                            if ($id_user==$m['id_utente']) 
                                switch ($m['stato']) {

                                    case 'I':
                                        echo anchor("albo/addmod/mod/".$id, "Modifica",'class=button').
                                             anchor("albo/addmod/del/".$id, "Cancella", array('class' => 'button','onclick' => "return confirm('Are you sure that you want to delete ?')")).
                                             anchor("albo/addmod/pub/".$id, "Pubblica",'class=button');
                                        break;

                                    case 'P':
                                        echo
                                        anchor("albo/addmod/mod/".$id, "Modifica", array('class' => 'button','onclick'=>"return check_constr($i,'m')")).
                                        anchor("albo/addmod/cer/".$id, "Certifica",array('class' => 'button','onclick'=>"return check_constr($i,'c')"));
                                        break;
                                    case 'C':
                                        echo "Nessuna Azione";
                                        break;
                                }
                            else  echo "Nessuna Azione";
                            break;
                   case 'resppub':
                            switch ($m['stato']){
                                    case 'I':
                                        echo
                                        anchor("albo/addmod/mod/".$id, "Modifica",'class=button').
                                        anchor("albo/addmod/del/".$id, "Cancella", array('class' => 'button','onclick' => "return confirm('Are you sure that you want to delete ?')")).
                                        anchor("albo/addmod/pub/".$id, "Pubblica",'class=button');
                                        break;
                                    case 'P':
                                        echo
                                        anchor("albo/addmod/mod/".$id, "Modifica", array('class' => 'button','onclick'=>"return check_constr($i,'m')")).
                                        anchor("albo/addmod/cer/".$id, "Certifica",array('class' => 'button','onclick'=>"return check_constr($i,'c')"));
                                        break;
                                    case 'C':
                                        echo "Nessuna Azione";
                                        break;
                            }
                           break;
                   case 'normal':echo "Nessuna Azione";
              }
                                    ?>
        </td>
   </tr>
    <?php $i++; endforeach ?>
   <?php  endif; ?>
</table>
<?php  require dirname(__FILE__).'/includes/footer.php'; ?>

