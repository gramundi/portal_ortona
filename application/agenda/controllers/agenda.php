<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class agenda extends CI_Controller {

        // var per variabili della classe;
        var $offset=0;
        var $limit=0;
        var $run=0;
        var $filter;
        var $ruolo='';

        function agenda() {
                
		parent::__construct();

                $this->load->model('Mycal_model','cm');
                $this->load->model('contatti_model','com');
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

            switch($this->ruolo){
                case 'admin':
                    //leggo tutti gli utenti abilitati all'applicazione
                    $data['ruolo']=$this->ruolo;
                    $data['gestori']=$this->cm->get_allgestori();
                    $data['title']='scelta agenda';
                    $this->load->view('sceltaagenda',$data);
                    break;
                case 'respage':

                    $res=$this->cm->get_agendeingestione($this->session->userdata('id_user'));
                    if ($res['num_gest']){

                        $data['ruolo']=$this->ruolo;
                        $data['title']='scelta agenda';
                        $data['gestori']=$res['gestori'];
                        $this->load->view('sceltaagenda',$data);

                    }
                    else
                        //Ho il ruolo di responsabile ma non ho asscociato nessun gestore
                        //gestisco solo la mi agenda come se fossi Normal
                        $this->display(null,null,$this->session->userdata('id_user'));
                    
                    break;
                case 'normal':
                    $this->set_calendario();
                    break;
            }



        }

        function set_calendario(){

            $id_usr=$this->session->userdata('id_user');
            if(isset($_POST['gestore'])) $gest=$_POST['gestore'];
            else $gest=$id_usr;
            
            
            $this->cd->registra_filtro($gest,'calendario',$id_usr);
            $this->display(null,null,$gest);
        }


        function display($year = null, $month = null, $filtro = null) {

            $this->load->model('Mycal_model');
            $id_usr=$this->session->userdata('id_user');
            if ($filtro==null) $filtro=$this->cd->leggi_filtro('calendario',$id_usr);
            if (!$year) $year = date('Y');
            if (!$month) $month = date('m');
            $data['calendar'] = $this->cm->generate($year, $month,$filtro);

            $data['titolare']=$this->cd->get_nome($filtro);
			
            $data['id_titolare']=$filtro;
            $data['title']='Gestione Appuntamenti';
            $data['year'] = $year; // ADD THIS
            $data['month'] = $month;
            $data['ruolo'] =$this->ruolo;
            
            $this->load->view('calendar',$data);
        
            
        }

      

        function gestapp() {


          $data['id']=$_POST['id_app'];
          $data['data']=$_POST['data'];
          $data['ora']=$_POST['ora'];
          $data['min']=$_POST['min'];

          $data['richiedente']=$_POST['id_richiedente'];
          if($this->ruolo=='normal') $data['tipo']='S';
          else $data['tipo']=$_POST['tipo'];
          $data['titolo']=$_POST['titol'];
          $data['descrizione']=$_POST['descr'];
          $data['id_titolare']=$_POST['id_titolare'];

          $op=$_POST['tipoop'];

          //echo 'tipo operazione'.$op;
          if ($data['tipo']=='S') {
               
               $this->cm->gest_appuntamento($data,$op);
               $this->display(null,null,null);
          }
          else {
                
                
                $result=$this->cm->gest_appuntamento($data,$op);
                $data['id_appuntamento']=$result;
                $data['title']='Associazione Titolari';
                $data['ruolo']=$this->ruolo;
                $result=$this->cm->get_titolari_app($result);
                $data['titolari']=$result['records'];
                $data['nrtitolari']=$result['num_rows'];
                $data['caller']='agenda';
                $this->load->view('sctitolari',$data);
          }
          
          

        }

        function cerca_richiedenti() {

            $str= $this->input->post('par');
           
            $records = $this->cm->get_richiedenti($str);
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

       function cerca_titolari() {

           $param= $this->input->post('par');
           $sp=explode('-',$param);

            $records = $this->cm->get_titolari($sp[0],$sp[1]);
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

       

       function associa_titolari() {

           $param= $this->input->post('par');
           $sp=explode('-',$param);

           $data['op']=$sp[0];
           $data['id_appuntamento']=$sp[1];
           $data['id_titolare']=$sp[2];
           $data['titolo']=$sp[3];
           echo $this->cm->handle_titolari($data);
 
       }

       function valida_ora() {

           $param= $this->input->post('par');
           $sp=explode('-',$param);
           echo $this->cm->Check_appuntamento($sp[0],$sp[1],$sp[2],$sp[3]);
      }

      function get_data_app() {

           $param= $this->input->post('par');
           $sp=explode('-',$param);
           
           $records = $this->cm->get_appuntamento($sp[0],$sp[1]);
           if ($records['num_rows']==0) return;
           $res=json_encode($records['records']);
           echo $res;
      }

      function salva_newcontatto(){

          $param= $this->input->post('par');
          $sp=explode('-',$param);
              $rec['nome']=$sp[0];
              $rec['cognome']=$sp[1];
              $rec['ragsoc']=$sp[2];
              $rec['telef']=$sp[3];
              $rec['sito']=$sp[4];
              $rec['email']=$sp[5];
              $rec['emailsec']=$sp[6];
              $rec['cell1']=$sp[7];
              $rec['cell2']=$sp[8];
              $rec['note']=$sp[9];
              $op='add';
              echo $this->com->dml_contatti($op,$rec);
      }

}
?>