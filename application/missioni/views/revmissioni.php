<?php

/*
 * Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

 ?>

<?php  require dirname(__FILE__).'/includes/head_1.php'; ?>
<script type="text/javascript">

function getids(){
alert('ciao');
$('#cons tr').each(function() {

    if (!this.rowIndex) return; // skip first row
    alert(this.cells[6].is(':checked'));
   //$(this).children('td').eq(6).is(':checked'));
   
    //$("#esempio6 input").is(":checked") )
    //alert(IdMiss);

});
}

</script>

<h1><?php echo $title;  ?></h1>
<p><b>Riepilogo Missioni Consolidate</b></p>
<form method="post" action='<?php echo site_url()."/consolida/revoca" ?>'  >
    <table id="ricerca">
        <tr>
            <th>Anno(yyyy)</th>
            <th>Mese(mm)</th>
            <th>Utente</th>
        </tr>
        <tr>
            <td><?php echo form_dropdown('anno',array('2010' => '2010','2011'=>'2011','2012'=>'2012','2013'=>'2013')); ?></td>
            <td><?php echo form_dropdown('mese',
            array('01'=>'gen','02'=>'feb','03'=>'mar','04'=>'apr','05'=>'mag','06'=>'giu','07'=>'lug','08'=>'ago','09'=>'set','10'=>'ott','11'=>'nov','12' => 'dic')); ?></td>
            <td><?php echo form_input('utente'); ?> </td>
        </tr>
        <tr><td><?php echo form_submit('ricerca', 'Cerca'); ?></td></tr>
    </table>
</form>
<br><br>
<?php  if($missioni === false): ?>
    <p><b>Non ci Sono Missioni consolidate che rispondono ai criteri di ricerca</b></p>
<?php  else: ?>
    <form method="post" action='<?php echo site_url()."/consolida/revoca" ?>'  >
    <table id="cons">
            <tr>
                <th>id</th>
                <th>utente</th>
                <th>Oggetto</th>
                <th>capitolo</th>
                <th>città</th>
                <th>costo</th>
                <th>Azioni</th>
            </tr>

                <?php $i = 1; foreach($missioni as $m): ?>
                <tr>
                   <td><?php $id=$m['id'];echo $m['id']?></td>
                    <td><?php echo $m['cognome'].'-'.substr($m['nome'],0,3) ?></td>
                    <td><?php echo $m['oggetto']?></td>
                    <td><?php echo $m['capitolo']?></td>
                    <td><?php echo $m['citta']?></td>
                    <td><?php echo $m['costo']?></td>
                    <td><?php echo form_checkbox($m['id'],$m['id'],FALSE);?></td>
               </tr>
                <?php $i++; endforeach ?>
           <?php endif; ?>
    <tr><td><?php $par='id="ricerca" onclick="getids();"'; echo form_submit('revoca', 'Revoca',$par); ?></td></tr>
    </table>
    </form>
           <?php  require dirname(__FILE__).'/includes/footer.php'; ?>

