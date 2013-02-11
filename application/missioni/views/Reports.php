<?php

/*
 * Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

 ?>
<?php  require dirname(__FILE__).'/includes/head_1.php'; ?>
<p><h1>Reportistica Missioni</h1>
<table>
    <tbody>
        <tr>
            <td>
                    <?php echo anchor('report/index/d', 'Riepilogo Dipendenti');?>
            </td>
        </tr>
        <tr>
            <td>
                    <?php echo anchor('report/index/c', 'Riepilogo capitoli');?>
            </td>
        </tr>
           <tr>
            <td>
                    <?php echo anchor('mission/', 'Home');?>
            </td>
        </tr>
        </tbody>
</table>

<?php  require dirname(__FILE__).'/includes/footer.php'; ?>

