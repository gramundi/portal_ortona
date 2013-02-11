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
                $this->load->model('cross_data','cd');
	}
	
	function index() {

            $id_usr=$this->session->userdata('id_user');
            $this->filter=$this->cd->leggi_filtro('utenti',$id_usr);
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
            $this->filter=$_POST['cognome'];
            $id_usr=$this->session->userdata('id_user');
            $this->cd->registra_filtro($this->filter,'utenti',$id_usr);
            $this->index();
        }

//Call Back AJAX to get applicazioni da assegnare
function get_app(){
           $id_user= $this->input->post('par');
           //echo 'user id='.$id_user;
           $records = $this->lm->get_app($id_user);
           //echo 'Numero ruoli='.$records['num_rows'];
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

//Call Back AJAX to get priviliges of the user
        function get_privs(){
           $id_user= $this->input->post('par');
           //echo 'user id='.$id_user;
           $records = $this->lm->get_ruoli_user($id_user);
           //echo 'Numero ruoli='.$records['num_rows'];
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

        //Call Back AJAX to manage priviliges of the user
        function gest_priv(){

        $par= $this->input->post('par');
        //echo 'parametri='.$par;
        $sp=explode('-',$par);
        $priv=$sp[0];
        $app=$sp[1];
        $id_user=$sp[2];
        $op=$sp[3];
        //echo 'priv='.$priv.'------applicazione='.$app.'-------user='.$id_user;
        $this->lm->gest_privileges($id_user,$app,$priv,$op);


        }


        //Call Back AJAX to get roless of the application
        function get_roles(){
           $application= $this->input->post('par');
           /*$sp=explode('-',$par);
           $application=$sp[0];
           $r_curr=$sp[1];
           echo 'app='.$application.'ruolo='.$r_curr;*/
           $records = $this->lm->get_ruoli_app($application);
           //echo 'Numero ruoli='.$records['num_rows'];
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

       function ManageUtenti($op=0,$id=0){

         if (isset($_POST['op']) ) $op=$_POST['op'];
         //Modifica Utente
         if ($op==1){
             //echo 'Modifica Utente';
             $data['id']=$_POST['iduser'];
             $data['nome']=$_POST['nome'];
             $data['cognome']=$_POST['cognome'];
             $data['stato']=($_POST['stato']== 'Attivo') ? 'A' : 'D' ;
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
            $data['stato']=($_POST['stato']== 'Attivo') ? 'A' : 'D' ;
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