<?php

/*
 * Sw di Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

class Utenti extends CI_Controller {


        var $filter;
        // var per variabili della classe;
        function Utenti() {
		parent::__construct();
                $this->load->model('login_model','lm');
                $this->load->helper('date');
		$this->load->library('table');
                $this->load->library('pagination');
	}
	
	function index() {


            $this->filter=$this->lm->leggi_filtro('utenti');
            $offset=$this->uri->segment(3);
            if ($offset==0) $offset=0;
            $limit=5;
            $config['uri_segment'] = 3;
            $config['base_url'] = base_url().'portal.php/utenti/index/';
            $config['total_rows'] = $this->lm->Count_All('utenti',$this->filter);
            $config['per_page'] = '5';

            $this->pagination->initialize($config);

            $data['utenti']=$this->lm->get_Utenti($this->filter,$limit,$offset);
            $data['pag']=$this->pagination->create_links();
            $data['title']='Gestione Utenti';
            $filtri=explode('-',$this->filter);
            $data['fil1']=$filtri[0];
            $this->load->view('account',$data);
	}

	//Imposto il filtro per la vista dell'applicazione
        function set_filtro(){
            $this->filter=$_POST['nome'];
            $this->lm->registra_filtro($this->filter,'utenti');
            $this->index();
        }

       
       function ManageUtenti($op=0,$id=0){

         if (isset($_POST['op']) ) $op=$_POST['op'];
         //Modifica Utente
         if ($op==1){
             //echo 'Modifica Utente';
             $data['id']=$_POST['iduser'];
             $data['nome']=$_POST['nome'];
             $data['cognome']=$_POST['cognome'];
             $data['ruolo']=$_POST['ruolo'];
             $data['username']=$_POST['username'];
             $data['password']=$_POST['password'];
             $this->lm->update_user($data);
             redirect('utenti');
             }
        //Aggiungi Utente
        if ($op==2){

            //echo 'Aggiungi';
            $data['nome']=$_POST['nome'];
            $data['cognome']=$_POST['cognome'];
            $data['ruolo']=$_POST['ruolo'];
            $data['username']=$_POST['username'];
            $data['password']=$_POST['password'];
            $this->lm->insert_user($data);
            redirect('utenti');
            
        }
        //cancella Utente
        if ($op==3){

            
            $id=$this->uri->segment(4);
            //echo 'Cancella utente='.$id;
            $this->lm->delete_user($id);
            redirect('utenti');
            
        }
       }
}