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

</script>

<h1><?php echo $title;  ?></h1>
<form method="post" action='<?php echo site_url()."/agenda/set_calendario" ?>'  >
    <table id="ricerca">
        <tr>
            <th>Agenda di:(Scegli gli appuntamenti da visualizzare)</th>
            
        </tr>
        <tr>
            <td><?php 
            foreach ($gestori as $row)
                $options[$row['id_user']]=$row['nomegest'];
            echo form_dropdown('gestore',$options); ?>
             </td>
        </tr>
        <tr><td><?php echo form_submit('agenda', 'Visualizza Agenda'); ?></td></tr>
    </table>
</form>
<br>
<?php  require dirname(__FILE__).'/includes/footer.php'; ?>
