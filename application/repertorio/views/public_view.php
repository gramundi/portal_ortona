<?php
/*
 * Gestione Missioni
 *
 * Author:
 * 	Jhonny Ramundi <http://www.jhonnynext.it>
 */
?>
<?php require dirname(__FILE__) . '/includes/head_public.php'; ?>
<script type="text/javascript">
    
$(function(){


$('#testo-rif').click(function(){

var testo=$(this).children(':nth-child(1)').text();
if (testo=='mostra'){
    $('#testo-rif > p').show();
    $(this).children(':nth-child(1)').replaceWith(' <a href="#">nascondi</a>');
    
}
else {
    $('#testo-rif > p').hide();
    $(this).children(':nth-child(1)').replaceWith(' <a href="#">mostra</a>');
    }
    });
 
 $('.getatto').click(function(){

    //Ajax call to get other data and file linked
    
    id=$(this).parent().parent().attr('id');
    //console.log(id);
    url='<?php echo site_url()."/repertorio/getdata"; ?>';
    $.getJSON(url, data={id:id}, function(data){
       //if (data == null) console.log('null');
       if (data != null){
            anno=data.substr(0,4);
            var docLocation = '<?php echo base_url()?>'+anno+'/'+data;
            var urlback = '<?php echo site_url()."/repertorio";?>';
            console.log(urlback+'--'+docLocation);
            $('body').html('<div><object data="'+docLocation+'" type="application/pdf" width="800" height="600"></object></div>'); 
            $('body').append('<div><a href="'+urlback+'">Torna Elenco Atti</a></div>');
       }
       
       
    });
 
 

});
    
    
});



</script>

<body>
    <div id="maindiv">    

        <h2><b>Registro pubblico degli atti in albo pretorio del comune di  ORTONA</b></h2>
        <div id="testo-rif">
            Riferimento Normativo:<a href="#">mostra</a>
            <p>

                L’Albo Pretorio Informatico è istituito, ai sensi e per gli effetti di cui all’articolo 32 della Legge n. 69 del 18 giugno 2009, con finalità di soddisfare il requisito di pubblicità legale degli atti e dei provvedimenti ivi pubblicati.

                Chiunque può ricercare, visionare e stampare gli atti durante il periodo di pubblicazione, direttamente e in ogni momento, da queste pagine. La richiesta di copia conforme all’originale degli atti pubblicati deve essere formalizzata con richiesta di accesso agli uffici competenti.

                Scaduti i termini della pubblicazione, gli atti potranno essere richiesti in visione e rilascio previa richiesta di accesso ai documenti ai sensi della legge 241/1990.

                Disciplina dell’Albo Pretorio informatico 
            </p>
        </div>
        
        <form id="filtrorep" method="post" action='<?php echo site_url() . "/repertorio/index" ?>'  >
            <label>Tipo</label>
            <?php echo form_input('tipo') ?>
            <label>Oggetto</label>
             <?php echo form_input('oggetto') ?>
            
            <?php echo form_submit('ricerca', 'Filtra'); ?>
        </form>
        <div id="dati-rep-pub">
         <div id="rowhead">
                <div id="cell">tipo</div>
                <div id="cell">provenienza</div>
                <div id="cell">pubblicato il</div>
                <div id="cell">scadenza il</div>
                <div id="cell">Seleziona</div>

         </div>
<?php if ($registro === false): ?>
                <p><b>Non ci Sono registrazioni</b></p>
            <?php else: ?>
                <?php $i = 1;
                foreach ($registro as $m): ?>
                    <div class="rowel" id =" <?php echo $m['id']; ?>">
                        <div id="cell"><?php echo $m['tipo'] ?></div>
                        <div id="cell"><?php echo $m['richiedente'] ?></div>
                        <div id="cell"><?php echo $m['dal'] ?></div>
                        <div id="cell"><?php echo $m['al'] ?></div>
                        <div id="cell"><div class="getatto">Visualizza Atto</div></div>

                    </div>
                    <div id="oggetto"><strong>oggetto:  </strong><?php echo $m['oggetto'] ?></div>
                    <?php $i++;
                endforeach ?>
            <?php endif; ?>
        </div>
        
    </div>
    <div id="paginator" align="center"><?php echo 'Pagina:' . $this->pagination->create_links(); ?></div>
        <?php require dirname(__FILE__) . '/includes/footer.php'; ?>
</body>
