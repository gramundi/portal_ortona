<?php

/*
 * Propone la navigazione documentale degli atti trovati all'interno 
 * dell registro del repertorio
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */
 ?>
<?php  require dirname(__FILE__).'/includes/head_public.php'; ?>

<script type="text/javascript">


</script>
<h4><b>ALBO PRETORIO</b></h4>
<table id="navatt">
        
            
            <?php foreach ($tipi as $row ) :?>
            <tr><?php
                     echo "<td>";
                     echo anchor("albo_pretorio/index/".$row['descrizione'], $row['descrizione'],'class=button');
                     echo "</td>";
                ?>
            </tr>
            <?php endforeach ?>
</table>
<p><b><?php  if($atti==''): ?>Visualizzo le categorie</b>
<?php  else: ?>
<table id="documenti">
    <tr><td align="center"><?php echo $tipo  ?></td></tr>
    <tr>
        <th>OGGETTO</th>
        <th>DESCRIZIONE</th>
        <th>DOCUMENTO</th>
    </tr>
<?php foreach($atti as $m): ?>
<tbody>
<tr>
        <td><?php  echo$m['oggetto']?></td>
        <td><?php echo $m['descrizione']?></td>
        <td><?php echo 'Link al file';  ?></td>
        
</tr>

<?php endforeach ?>

</table>
<?php  endif; ?>
<tr> <td align="center"><?php echo 'Pagina:'.$this->pagination->create_links(); ?></td></tr>
<?php  require dirname(__FILE__).'/includes/footer_public.php'; ?>
<br>
<br>

