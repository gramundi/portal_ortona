<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Sw di Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

class ordinanze extends CI_Controller {

        // var per variabili della classe;
        var $offset=0;
        var $limit=0;
        var $run=0;
        var $filter;
        function Ordinanze() {
                
		echo 'entro controller ordinanze';	
		parent::__construct();
                $id_usr=$this->session->userdata('id_user');
                if ($id_usr==''){
                      echo '<h3>acesso non consentito<h3>';
                      exit;
                 }
                $this->load->model('ordinanze_model','am');
                $this->load->model('/../../portal/models/cross_data','cd');
                $this->load->library('pagination');
                


	}

        //Imposto il filtro in sessione viene chiamata dalla pagina di riepilogo
        //View del registro del repertorio Welcome_adm,Welcome
        function set_filtro(){

            if ($_POST['tipo']=='Tutte') $tipo='NUL';
            else $tipo=$_POST['tipo'];

            if ($_POST['ordinante']=='Tutti') $ordinante='NUL';
            else $ordinante=$_POST['ordinante'];

            if ($_POST['gestore']=='') $gestore='NUL';
            else $gestore=$_POST['gestore'];

            if ($_POST['oggetto']=='') $oggetto='NUL';
            else $oggetto=$_POST['oggetto'];

            if ($_POST['rif']=='')$rif='NUL';
            else $rif=$_POST['rif'];

            $this->filter=$tipo.'-'.$ordinante.'-'.$gestore.'-'.$oggetto.'-'.$rif.'-';
            $id_usr=$this->session->userdata('id_user');
            $this->cd->registra_filtro($this->filter,'ordinanze',$id_usr);
            
            $this->index();
        }

 function index( ) {

                
                 $id_usr=$this->session->userdata('id_user');
                 
                 if ($this->cd->get_privilegi($id_usr,'Ordinanze')=='no privileges'){

                     $this->load->view('../../share_views/no_priv.php');
                       return;
                 }
                //Leggo il filtro impostato sulla maschera
                //La prima volta sulla tabella filtri è impostato il filtro nul
                //NUL-NUL-NUL-NUL
                $this->filter=$this->cd->leggi_filtro('ordinanze',$id_usr);
                //echo $this->filter;
                $ruolo=$this->cd->get_privilegi($id_usr,'Ordinanze');
                //echo 'prima volta '.$this->filter;
                $offset=$this->uri->segment(3);
                if ($offset==0) $offset=0;
                $limit=10;
                $config['uri_segment'] = 3;
                $config['base_url'] = base_url().'ordinanze.php/ordinanze/index';
                $config['total_rows'] = $this->cd->Count_All('vordinanze',$this->filter);
                $config['per_page'] = '10';
                $this->pagination->initialize($config);
                //echo $this->filter;
                $this->_getordinanze($ruolo,$config['total_rows'],$offset,$limit);
             
            
      }

      //Estrae l'elenco  registro delle ordinanze
      function _getordinanze($ruolo,$num_rows_fil,$off,$lim){
       
       //Get Ordinanti Ammessibili
       $data['num_rows_fil']=$num_rows_fil;
       $data['num_rows']=$this->cd->num_rows('vordinanze');
       $data['ruolo']=$ruolo;
       $data['ordinanti']=$this->am->get_ordinanti();

       $data['pag']=$this->pagination->create_links();
       $data['registro']=$this->am->getdata_ordinanze(0,$this->filter,$off,$lim);;
       $data['title']='Benvenuto '.$this->session->userdata('nome');
       $filtri=explode('-',$this->filter);

       $data['fil1']=$filtri[0];

       if ($filtri[1]=='NUL') $data['fil2']='Tutti';
       else $data['fil2']=$filtri[1];

       $data['fil3']=$filtri[2];
       
       $data['fil4']=$filtri[3];

       $data['fil5']=$filtri[4];
       
       $this->load->view('ordinanze_view', $data);
      }
 }
