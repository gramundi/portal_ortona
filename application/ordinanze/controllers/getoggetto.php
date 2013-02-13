<?php

/*
 * Sw di Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

class getoggetto extends CI_Controller {

        // var per variabili della classe;
        function GetOggetto() {
		parent::__Construct();
                 $id_usr=$this->session->userdata('id_user');
                if ($id_usr==''){
                      echo '<h3>acesso non consentito<h3>';
                      exit;
                 }
                $this->load->model('ordinanze_model','om');
	}
	
	function index() {

                $rif= $this->input->post('par');
                $records = $this->om->get_oggetto($rif);
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
