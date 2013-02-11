<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Portale Applicazioni COntroller di Accesso al Portale di applicazioni
 * Configurazioni e Inizializzazioni
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.eu>
 */

class Welcome_portal extends CI_Controller {

        // var per variabili della classe;
        function Welcome_portal() {
		parent::__Construct();
                $this->load->model('login_model','lm');
                	}
	
	function index() {
              //Resetto il ruolo applicativo per l'utente loggato
              // SErver per mettere in sessiore il ruolo con privilegi per applicazione
              //
              $id_sessione=$this->session->userdata('id_sessione');
              $session_id=$this->session->userdata('session_id');
              if ($id_sessione!=$session_id)
                {
               //Sessione expired
               echo 'session-expired';
               //$this->lm->log_user('logout',$id_sessione);
               //$this->session->sess_destroy();
               //redirect('main');
                }
              $this->session->set_userdata('ruolo','');
              $data['title']='Portale Applicazioni';
              $id=$this->session->userdata('id_user');
              if ($this->session->userdata('ruolo_p')==''){
                        $ruolo_p=$this->lm->get_privilegi($id,'Portal');
                        //echo $ruolo_p;
                        $this->session->set_userdata('ruolo_p',$ruolo_p);

             }
             else $ruolo_p=$this->session->userdata('ruolo_p');
             $data['ruolo_p']=$ruolo_p;
             $this->load->view('welcome_portale',$data);
                
	}


        //Call Back AJAX to get roless of the application
        function get_messages(){
           //echo('check new messages');
           $id=$this->session->userdata('id_user');
           $records = $this->lm->check_newmessages($id);
           echo $records;
        }


}
?>