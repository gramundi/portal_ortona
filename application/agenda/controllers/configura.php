<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class configura extends CI_Controller {

        // var per variabili della classe;
        var $offset=0;
        var $limit=0;
        var $run=0;
        var $filter;
        var $ruolo='';

        function configura() {

		parent::__construct();

                $this->load->model($this->config->item('share_model'),'cd');
                $id_usr=$this->session->userdata('id_user');


                if ($id_usr==''){
                      echo '<h3>acesso non consentito<h3>';
                      exit;
                 }
                $this->ruolo=$this->cd->get_privilegi($id_usr,'Agenda');
                if ($this->ruolo=='no privileges'){
                        $this->load->view('../../share_views/no_priv.php');
                        return;
                }

	}

        function index() {
            $data['title']='Configurazione Utenti con ruolo respage';
            $data['ruolo']=$this->ruolo;
            $this->load->view('configurazioni',$data);
        }
}