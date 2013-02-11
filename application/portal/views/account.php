<?php
/*
 * Module:account.php gestione utenti.
 * View the list of the users inside the system and allow the management
 * function.
 * Main Functions are imlemented by JQUERY AND AJAX building dynamic element
 * inside the page.
 *
 *
 * Date:10/02/2010
 * Author: Jhonny Ramundi <http://www.jhonnynext.it>
 */
?>
<?php require dirname(__FILE__) . '/includes/head.php'; ?>
<script type="text/javascript">

    function validaform(){
        //alert('validaform');
        var nome=$('#nome').val();
        var cognome=$('#cognome').val();
        var username=$('#username').val();
        var password=$('#password').val();

        if (nome && cognome && username && password ) return true;
        else{
            alert('inserire campi obbligatori');
            return false;
        }

    }
    /* Reset the field of the form wich add users */
    function add()
    {
        //Hide the the manage table of the  user
        $('#manageuser').hide();
        //Reset fields
        $('#nome').val('');
        $('#cognome').val('');
        $('#ruolo').val('');
        $('#username').val('');
        $('#password').val('');
        $('#manageuser').show();
        $('#op').val('2');
    }

//Build the table to modify the user in the row i
    function mod(id)
    {
        
        //la prima riga è l'intestazione della tabella
        var row=id+1;
        //var column=0;
        //var cell = $('tr:eq(' + row + ') td:eq(' + column + ')');
        //alert(row);
        //$('#manageuser').hide();
        //Got all the values from the row id
        var id_user=$('#ute tr:eq(' + row + ') td:eq(0)').text();

        var nome=$('#ute tr:eq(' + row + ') td:eq(1)').text();
        var cognome=$('#ute tr:eq(' + row + ') td:eq(2)').text();

        //Put the values inside the tables to allow the perform changing
        $('#iduser').val(id_user);
        $('#nome').val(nome);
        $('#cognome').val(cognome);
        $('#op').val('1');
        //Hide the other tables
        //$('#ute').hide();
        $('#ricerca').hide();
        $('#pag').hide();
        $('#manageuser').show();
        //alert(id+nome+cognome+ruolo);
    }

/*Get all the roles linked to application for the i user*/
function get_roles_app(i,applicazione)
{
        //devo attivare la select e aggiungerci le option
        // le option sono i ruoli per l'applicazione
        //alert('i='+i+'id='+id+'app='+applicazione);
        sel='sel'+i;
        //alert($('#'+sel).val());
        $('#'+sel).attr("disabled", false);
        ruolocurr=$('#'+sel).val();
        $.ajax({
            type: "POST",
            async: false,
            url: "<?php echo site_url() . "/utenti/get_roles"; ?>",
            data: "par="+applicazione,
            dataType:"jsondata",
            success: function(data){
                // ciclo l'array
                //alert(data);
                var myObject = eval('(' + data + ')');
                for(j=0; j<myObject.length; j++){
                    ruolo=myObject[j].ruolo;
                    //alert(ruolo);
                    if (ruolo!=ruolocurr)
                        $('#'+sel).append(new Option(ruolo, ruolo));
                }
            }
        });
        $('#'+sel).val(ruolocurr);
        //$('#'+sel+' option[value='+ruolocurr+']').remove();
    }

// Recupera i privilegi per l'applicazione app
function getpriv_app(app)  {
//alert (app);
//Rimuove le option della select list
$('#apppriv option').remove();
$.ajax({
            type: "POST",
            async: false,
            url: "<?php echo site_url() . "/utenti/get_roles"; ?>",
            data: "par="+app,
            dataType:"jsondata",
            success: function(data){
                // ciclo l'array
                //alert(data);
                var myObject = eval('(' + data + ')');
                for(j=0; j<myObject.length; j++){
                    ruolo=myObject[j].ruolo;
                    $('#apppriv').append(new Option(ruolo, ruolo));
                }
            }
        });
   $('#newpriv').show();
}

 //Prepara la maschera to add a privilege to user id
    function addpriv(id)
    {
        
        //alert('nuovi priv');
        $('#ute').hide();
        $('#pag').hide();
        $('#ricerca').hide();
        $('#manageuser').hide();
        $('#app option').remove();
        $.ajax({
            type: "POST",
            async: false,
            url: "<?php echo site_url()."/utenti/get_app"; ?>",
            data: "par="+id,
            dataType:"jsondata",
            success: function(data){
                // ciclo l'array
                //alert(data);
                if(data.length==0) {
                   
                     $('#ute').show();
                     $('#pag').show();
                     $('#ricerca').show();
                      alert('utente ha tutte le applicazioni');
                     return;
                    }
                else {
                    var myObject = eval('(' + data + ')');
                    for(j=0; j<myObject.length; j++){
                        app=myObject[j].applicazione;
                        $('#app').append(new Option(app, app));
                    }
                }
            }
        });

       
        getpriv_app($('#app').val());
        $('#user').val(id);


    }
//Manage the privileges of the user i. This function build a table with all
//the application and privileges linked to the user id. For each privileges
//build a button to modify the privileges. The select box linked to the privilege
//implement onchange handle to modify the privileges.onchange is linked to the function
//changepriv() to record by AJAX the new privilege into DB.
//
    function privilegi(id)
    {
        $('#ute').hide();
        $('#ricerca').hide();
        $('#pag').hide();
        $('#manageuser').hide();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url() . "/utenti/get_privs"; ?>",
            data: "par="+id,
            dataType:"jsondata",
            success: function(data){
                // ciclo l'array
                //alert(data);
                if(data.length==0){
                    $('#mydata').hide();
                }
                var myObject = eval('(' + data + ')');
                var co='<tr> <th>APPLICAZIONE</th>';
                co=co+'<th> RUOLO</th>';
                co=co+'<th> AZIONI</th></tr>';
                for(i=0; i<myObject.length; i++){
                    applicazione=myObject[i].applicazione;
                    ruolo=myObject[i].ruolo;
                    co=co+'<tr>';
                    co=co+'<td>'+applicazione+'</td>';
                    co=co+'<td><select id="sel'+i+'"'+'  disabled="disabled" onchange=changepriv('+id+','+"'"+applicazione+"'"+','+i+')><option value='+ruolo+'>'+ruolo+'</option></select></td>';
                    co=co+'<td><button id="but'+i+'"'+'onclick=get_roles_app('+i+','+"'"+applicazione+"'"+')>modifica</button></td>';
                    co=co+'<td>'
                }
                co=co+'</tr>'
                co=co+'<tr><td><button onclick=hide_gestpriv()>fine</button></td></tr>';
                //alert(co);
                $('#privilegi').html(co);
                $('#privilegi').show();

            }
        });
       

    }
// hide the table to manage the privileges
    function hide_gestpriv()
    {
        //alert (app+'selecnr:'+i);
        $('#privilegi').hide();
        //Riabilita le altre tabelle
        $('#ute').show();
        $('#ricerca').show();
        $('#pag').show();
        
    }


 /*Someone ask to change privilege I need to register new privilege*/
 function changepriv(id_user,app,i)
 {
        //alert ('applicazione='+app+'selecnr:'+i);
        but='but'+i;
        sel='sel'+i;
        $('#'+but).attr("disabled", true);
        //alert($('#'+sel).val());
        priv=$('#'+sel).val();
        op='mod';
        $.ajax({
            type: "POST",
            url: "<?php echo site_url() . "/utenti/gest_priv"; ?>",
            data: "par="+priv+'-'+app+'-'+id_user+'-'+op,
            dataType:"jsondata",
            success: function(data){
            //alert(data);
             //alert ('privilegio modificato');
            }

         });
      

    }

function insert_priv(id){
 app=$('#app').val();
 priv=$('#apppriv').val();
 user=$('#user').val();
 op='ins';
 //alert('applicazione='+app+'privilegio='+priv+'user='+user);
 $('#newpriv').hide();
 $.ajax({
            type: "POST",
            async: false,
            url: "<?php echo site_url()."/utenti/gest_priv"; ?>",
            data: "par="+priv+'-'+app+'-'+user+'-'+op,
            dataType:"jsondata",
            success: function(data){
             //alert(data);
             //alert ('privilegio inserito');

            
            }
        });
  $('#ute').show();
  $('#ricerca').show();
  $('#pag').show();
 }

function cancella(id){
        alert (' Sei Sicuro');
        $('#iduser').val(id);

}


</script>

<hr>
<form method="post" action='<?php echo site_url() . "/utenti/set_filtro" ?>'  >
            <table id="ricerca">
                <tr>
                    <th>cognome</th>
                </tr>
                <tr>
                    <td><?php echo form_input('cognome', $fil1) ?> </td>
                </tr>
                <tr><td><?php $par = 'id="ricerca"';
        echo form_submit('ricerca', 'Cerca'); ?></td></tr>
        </table>
    </form>
<p><b>Riepilogo Utenti</b></p>
<table id="ute">
    <tr><td><input type=submit value="Nuovo User" onclick="add()"></td></tr>
    <tr>
        <th>ID</th>
        <th>NOME</th>
        <th>COGNOME</th>
        <th>AZIONI</th>
    </tr>

<p><b><?php if ($utenti === false): ?>Non ci Sono Utenti</b>
<?php else: ?>
<?php $i = 1;
        foreach ($utenti as $m): ?>
        <tbody>
            <tr id="<?php echo $i ?>">
                <td><?php $id = $m['id'];
            echo $id ?></td>
                <td><?php echo $m['nome'] ?></td>
                <td><?php echo $m['cognome'] ?></td>
                <td><button name="modifica"  type="button" onclick="mod(<?php echo $i ?>)">modifica</button>
                <?php echo anchor("utenti/ManageUtenti/3/$id", "Cancella", 'class=button'); ?>
                <button name="privilegi" type="button" onclick="privilegi(<?php echo $id ?>)">ModificaPrivilegi</button>
                <button name="addpriv" type="button" onclick="addpriv(<?php echo $id ?>)">AggiungiPrivilegi</button>
            </td>
        </tr>
        <?php $i++;
            endforeach ?>


        </tbody>
    </table>
    <br>

<table id="pag"><tr><td><?php echo 'Pagine:' . $pag; ?></td></tr></table>
<?php endif; ?>

<table id="privilegi" style="display:none">  </table>

<table id="newpriv" style="display:none">
<tr><th>Applicazione </th><th>Ruoli</th></tr>
<tr>
    <input type="hidden" id="user" name="">
    <td><select id="app" onchange="getpriv_app(this.value);">Applicazione</select></td>
    <td><select id="apppriv">Ruolo</select></td>
</tr>
<tr><td><button onclick="insert_priv();">Fine</button></td></tr>
</table>

<form method="post" id="manageuser" style="display:none" action="<?php echo site_url('/utenti/ManageUtenti/'); ?>">
                        <br>
                        <table class="form">
                            <tbody>
                            <input type="hidden" id="op" name="op" >
                            <input type="hidden" id="iduser" name="iduser" >
                            <tr>
                                <th>Nome</th> <td><?php $par = 'id="nome"';
                    echo form_input('nome', '', $par) ?></td>
                <th>Cognome</th>
                <td><?php $par = 'id="cognome"';
                    echo form_input('cognome', '', $par) ?></td>
            </tr>
            <tr>
            <th><label for="qualifica">Qualifica</label></th>
                <td><?php
                    $js = ' id="qualifica" ';
                    echo form_dropdown('qualifica',
                            array('dir' => 'dir', 'c' => 'c', 'd3' => 'd3', 'b' => 'b', 'a' => 'a', 'd' => 'd', 'b3' => 'b3'), 'dir', $js);
    ?>
                </td>
            </tr>
            <tr>
                <th><label for="capitolo">Capitolo</label></th>
                <td><?php
                    $js = ' id="capitolo" ';
                    echo form_dropdown('capitolo',
                            array('segreteria' => 'segreteria', 'finanze' => 'finanze', 'uff tec' => 'uff tec',
                                'Bilbio' => 'Biblio', 'Pol Mun' => 'Polizia Municipale', 'Sociale' => ' Ass Sociale',
                                'Demo' => 'Serv Demografici', 'Urbanist' => 'Serv Urbanistici'), 'segreteria', $js);
    ?>
                </td>
                <th><label for="stato">Stato</label></th>
                <td><?php
                    $js = ' id="stato" ';
                    echo form_dropdown('stato',
                            array('Attivo' => 'Attivo', 'Disattivo' => 'Disattivo'), 'Attivo', $js);
    ?>
                </td>
            </tr>
            <tr>
                <th><label for="username">Nome Utente</label></th>
                <td><?php $par = 'id="username"';
                    echo form_input('username', '', $par) ?></td>
                <th><label for="password">Password</label></th>
                <td><?php $par = 'id="password"';
                    echo form_input('password', '', $par) ?></td>
            </tr>
            <tr><td><input type="submit" name="invia" value="Salva" onclick="return validaform();"></td></tr>
            </tbody>
        </table>
    </form>
<?php require dirname(__FILE__) . '/includes/footer.php'; ?>

