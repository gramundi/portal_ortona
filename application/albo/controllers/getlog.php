<?php

/*
 * Sw di Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

class Getlog extends CI_Controller {

        // var per variabili della classe;
        function Getlog() {
		parent::__Construct();
                 $id_usr=$this->session->userdata('id_user');
                if ($id_usr==''){
                      echo '<h3>acesso non consentito<h3>';
                      exit;
                 }
                $this->load->model('login_model','lm');
	}
	
	function index() {
                
                $username= $this->input->post('par');
                //echo $username;
                $records = $this->lm->log_utenti($username);
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