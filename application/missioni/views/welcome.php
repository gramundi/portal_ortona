<?php

/*
 * Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

 ?>
<?php  require dirname(__FILE__).'/includes/head.php'; ?>
<h1><?php echo $title;  ?></h1>
<p><b>Riepilogo Missioni</b></p>
<table>
    <tr>
        <th>identificativo</th>
        <th>Oggetto</th>
        <th>capitolo</th>
        <th>città</th>
        <th>Azioni</th>
    </tr>
    <?php  if($missioni === false): ?>
    <p><b>Non ci Sono Missioni</b></p>
<?php  else: ?>
<?php $i = 1; foreach($missioni as $m): ?>
    <tr>
       <td><?php $id=$m['id'];echo $m['id']?></td>
        <td><?php echo $m['oggetto']?></td>
        <td><?php echo $m['capitolo']?></td>
        <td><?php echo $m['citta']?></td>
        <td><?php if ( $m['stato']==1) {

                        echo
                        anchor("mission/manage/$id/det", "Spese",'class=button').
                        anchor("mission/addmod/mod/".$id, "Modifica",'class=button').
                        anchor("mission/manage/$id/del", "Cancella", array('class' => 'button','onclick' => "return confirm('Are you sure that you want to delete ?')")).
                        anchor("mission/manage/$id/sta", "Stampa",'class=button');
                        }
                   else {
                        echo
                        anchor("mission/manage/$id/sta", "Stampa",'class=button');
                        
                    }
        ?>
        </td>
   </tr>
    <?php $i++; endforeach ?>
   <?php  endif; ?>
</table>
<?php  require dirname(__FILE__).'/includes/footer.php'; ?>

