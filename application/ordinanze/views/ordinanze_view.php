<?php

/*
 * Gestione Ordinanze
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

 ?>
<?php  require dirname(__FILE__).'/includes/head.php'; ?>
<script type="text/javascript">

</script>

<h1><?php echo $title;  ?></h1>
<h4><b>Riepilogo Registro delle ORDINANZE COMUNE ORTONA</b></h4>
<form method="post" action='<?php echo site_url()."/ordinanze/set_filtro" ?>'  >
    <table id="ricerca">
        <tr>
            <th>tipo</th>
            <th>ordinante</th>
            <th>gestore</th>
            <th>oggetto</th>
            <th>riferimento</th>
            
        </tr>
        <tr>
            <td><?php $options1 = array('Tutte' =>'Tutte','dirigenziale'   => 'dirigenziale', 'sindacale' => 'sindacale');
                      echo form_dropdown('tipo', $options1, $fil1); ?> </td>
            <td><?php
            foreach ($ordinanti as $row) 
                $options[$row['cognome']]=$row['cognome'];
            $options['Tutti']='Tutti';
            echo form_dropdown('ordinante',$options,$fil2); ?>
            </td>
            <td><?php echo form_input('gestore',$fil3)?> </td>
            <td><?php echo form_input('oggetto',$fil4)?> </td>
             <td><?php echo form_input('rif',$fil5)?> </td>
             
            
        </tr>
        <tr><td><?php $par='id="ricerca" onclick="refresh();"'; echo form_submit('ricerca', 'Cerca'); ?></td></tr>
    </table>
</form>
<br>
<br>
<table id="dati">
    <tr>
        <th>codice</th>
        <th>rif</th>
        <th>ordinante</th>
        <th>tipo</th>
        <th>oggetto</th>
        <th>gestore</th>
        <th>file</th>
        <th>stato</th>
        <?php if ($ruolo!='normal') echo '<th>azioni</th>'; ?>
          
    </tr>
    <?php  if($registro === false): ?>
    <p><b>Non ci Sono Ordinanze</b></p>
<?php  else: ?>
<?php $i = 1;foreach($registro as $m): ?>
    <tr><?php $id=$m['id'];  ?>
        <td><?php echo $m['codice']?></td>
        <td><?php echo $m['rif']?></td>
        <td><?php echo $m['ordinante'] ?></td>
        <td><?php echo $m['tipo'] ?></td>
        <td><?php echo $m['oggetto'] ?></td>
        <td><?php echo $m['gestore'] ?></td>
        <td><?php if ($m['file']) {
            $atts = array('width'=> '800','height'=> '600','scrollbars' => 'yes','status' => 'yes','resizable'  => 'yes',
              'screenx'    => '0','screeny'    => '0'
            );
            $anno=substr($m['file'],strpos($m['file'],"_")+1,4);
            echo anchor_popup(base_url().$anno.'/'.($m['file']),$m['file'],$atts);
            } ?></td>
        <td><?php echo $m['stato']?></td>
        <?php
                  $codice=$m['codice'];
                  switch($ruolo) {
                  
                  //Può gestire le sue registrazioni e vedere le altre
                  case 'Normal':
                            $id_user=$this->session->userdata('id_user');
                            if ($id_user==$m['id_utente']) 
                                switch ($m['stato']) {

                                case 'I':
                                        echo '<td>';
                                        echo anchor("manage/index/conferma/".$id, "Conferma",'class=button').
                                             anchor("manage/index/mod/".$id, "Modifica",'class=button').
                                             anchor("manage/upload/".$codice.'/'.$id, "Upload",'class=button');
                                        echo '</td>';
                                        break;

                                                              
                                }
                            else  echo "<td>Nessuna Azione</td>";
                            break;
                   //Può gestire tutte le registrazioni
                   case 'respord':
                            switch ($m['stato']){

                                    case 'I':
                                        echo '<td>';
                                        echo anchor("manage/index/conferma/".$id, "Conferma",'class=button').
                                             anchor("manage/index/mod/".$id, "Modifica",'class=button').
                                             anchor("manage/upload/".$codice.'/'.$id, "Upload",'class=button');
                                        echo '</td>';
                                        break;

                                   
                                    
                            }
                            break;
                   case 'admin':
                            switch ($m['stato']) {

                                    case 'I':
                                        echo '<td>';
                                        echo anchor("manage/index/conferma/".$id, "Conferma",'class=button').
                                             anchor("manage/index/mod/".$id, "Modifica",'class=button').
                                             anchor("manage/upload/".$codice.'/'.$id, "Upload",'class=button');
                                        echo '</td>';
                                        break;

                                    case 'C':
                                        echo '<td>';
                                        echo
                                        anchor("manage/index/bon/".$id,"Correggi",array('class' => 'button','onclick'=>"return check_constr($i,'c')"));
                                        echo '</td>';
                                        break;
                                }
                             break;
                   
              }
                                    ?>
        </tr>
    <?php $i++; endforeach ?>
   <?php  endif; ?>
</table>
<h6><?php echo 'filtrati '.$num_rows_fil.' su '.$num_rows.' elementi totali' ?></h6>
<table>
    <tr> <td align="center"><?php echo 'Pagina:'.$this->pagination->create_links(); ?></td>    </tr>

</table>

<?php  require dirname(__FILE__).'/includes/footer.php'; ?>

