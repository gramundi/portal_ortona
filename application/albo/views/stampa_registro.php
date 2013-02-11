<?php

/*
 * Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

 ?>
<script type="text/javascript">

function test(){
    alert('ciao');
    return true;
}

</script>
<?php  require dirname(__FILE__).'/includes/head_1.php'; ?>
<h1><?php //echo $title;  ?></h1>
<h3><p><b>Stampa Registro degli atti: </b></p></h3>

<b>Numero Atti Trovati:<?php echo $tot ?></b>
<br>
<br>
<b>Avvertenza le pubblicazioni ancora in corso non verrano portate in stampa! </b>
<br>
<br>
<form method="post" action='<?php echo site_url()."/certifica/index/generastampareg" ?>'  >
    <table id="ricerca">
            <tr><th>dal(gg/mm/aaaa)</th><td><?php echo form_input('dal')?> </td>
            <th>al(gg/mm/aaaa)</th><td><?php echo form_input('al')?> </td>
        </tr>
        <tr><td><?php $par='id="ricerca" onclick="refresh();"'; echo form_submit('ricerca', 'Stampa'); ?></td></tr>
    </table>
</form>
<br>
<a href=<?php echo site_url().'/albo'?>> Riepilogo Atti</a>
<?php  require dirname(__FILE__).'/includes/footer.php'; ?>

