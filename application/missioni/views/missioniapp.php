<?php

/*
 * Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

 ?>
<?php  require dirname(__FILE__).'/includes/head_1.php'; ?>
<h1><?php echo $title;  ?></h1>
<p><b>Riepilogo MissioniApprovate </b></p>
<?php  if($missioni == 0): ?>
    <p><b>Non ci Sono Missioni Da consolidare</b></p>
<?php  else: ?>
    <table>
        <tr>
            <th>id</th>
            <th>nome     </th>
            <th>cognome  </th>
            <th>qualifica</th>
            <th>costo</th>
        </tr>
    <?php foreach($missioni as $m): ?>
        <tr>
           <td><?php $id=$m['id'];echo $m['id']?></td>
            <td><?php echo $m['nome']?></td>
            <td><?php echo $m['cognome']?></td>
            <td><?php echo $m['qualifica']?></td>
            <td><?php echo $m['costo']?></td>
        </tr>
      <?php  endforeach ?>
       <tr><td></td></tr>
       <tr><td></td></tr>
      <tr><td><b><?php echo anchor('consolida/trasferisci', 'Esegui Il trasferimento dati');?></b></td></tr>
      </table>
      <?php  endif; ?>
  <table>
  <tr><td><b>  <?php echo anchor('consolida/revoca', 'Revoca Missioni');?></b></td></tr>
  <tr><td><b>  <?php echo anchor('mission/', 'Home');?></b></td></tr>
  </table>

<?php  require dirname(__FILE__).'/includes/footer.php'; ?>

