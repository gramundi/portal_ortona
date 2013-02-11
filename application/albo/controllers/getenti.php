<?php

/*
 * Sw di Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

class Getenti extends CI_Controller {

        // var per variabili della classe;
        function Getenti() {
		parent::__Construct();
                 $id_usr=$this->session->userdata('id_user');
                if ($id_usr==''){
                      echo '<h3>acesso non consentito<h3>';
                      exit;
                 }
                $this->load->model('albo_model','am');
	}
	
	function index() {

                //echo 'getenti-------------------------';
                $ente= $this->input->post('par');
                //echo $ente;
                $records = $this->am->CercaEnte($ente);
                //echo 'NUMMMMMMM'.$records['num_rows'];
                
                 /*
                  * Json build WITH json_encode.
                  */
               if ($records['num_rows']==0) return;

               //Codifica dei dati estrati in formato json attraverso la libreria json_encode
               //Prende un array associativo e lo formatta in notazione JSON
               $res=json_encode($records['records']);
               echo $res;
                    
                  
	}

}