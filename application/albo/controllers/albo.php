<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Sw di Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

class Albo extends CI_Controller {

        // var per variabili della classe;
        var $offset=0;
        var $limit=0;
        var $run=0;
        var $filter;
        var $ruolo='';
        function Albo() {
		parent::__construct();
                $id_usr=$this->session->userdata('id_user');
                 if ($id_usr==''){
                      echo '<h3>acesso non consentito<h3>';
                      exit;
                 }
                $this->load->model('albo_model','am');
                $this->load->model('enti_model','em');
                $this->load->model($this->config->item('share_model'),'cd');
                $this->load->library('pagination');
                


	}

         function set_filtro(){
            if ($_POST['ente']=='') $ente='NUL';
            else $ente=$_POST['ente'];

            if ($_POST['oggetto']=='') $oggetto='NUL';
            else $oggetto=$_POST['oggetto'];
            
            if ($_POST['gestore']=='') $gestore='NUL';
            else $gestore=$_POST['gestore'];

            if ($_POST['rif']=='') $rif='NUL';
            else $rif=$_POST['rif'];

            if ($_POST['tipo']=='') $tipo='NUL';
            else $tipo=$_POST['tipo'];

            $stato=$_POST['stato'];

            $this->filter=$ente.'-'.$oggetto.'-'.$gestore.'-'.$rif.'-'.$tipo.'-'.$stato.'-';
            $id_usr=$this->session->userdata('id_user');
            
            $this->cd->registra_filtro($this->filter,'repertorio',$id_usr);
            $this->index();
        }

        function reset_filtro(){
            $this->filter='NUL-NUL-NUL-NUL-NUL-NUL-';
            $id_usr=$this->session->userdata('id_user');
            $this->cd->registra_filtro($this->filter,'repertorio',$id_usr);
            
        }

        function index( ) {

                $id_usr=$this->session->userdata('id_user');
                if ($this->cd->get_privilegi($id_usr,'Repertorio')=='no privileges'){
                        $this->load->view('../../share_views/no_priv.php');
                        return;
                }
                if ($this->ruolo=='') $this->ruolo=$this->cd->get_privilegi($id_usr,'Repertorio');
               
                $this->filter=$this->cd->leggi_filtro('repertorio',$id_usr);

                $offset=$this->uri->segment(3);
                if ($offset==0) $offset=0;
                $limit=10;
                $config['uri_segment'] = 3;
                $config['base_url'] = base_url().'albo.php/albo/index';
                $config['total_rows'] =$this->cd->Count_All('vregistro',$this->filter);
                $config['per_page'] = '10';
                $this->pagination->initialize($config);
                //echo $this->filter;
                $this->_getregistro($this->ruolo,$config['total_rows'],$offset,$limit);
               
       }

       function cercaente() {

            $this->load->view('findente');
       }

       //Estrae il registro  con applicazione dei filtri
      function _getregistro($ruolo,$num_rows_fil,$off,$lim){

       $data['num_rows_fil']=$num_rows_fil;
       $data['num_rows']=$this->cd->num_rows('vregistro');
       $data['ruolo']=$ruolo;
       $data['pag']=$this->pagination->create_links();
       // $this->filter=$this->am->leggi_filtro();
       //echo 'filtro='.$this->filter;
       
       switch ($ruolo)
           {

               

                case 'admin':
                     //Utente Amministartore
                     //Pagina di Ammninistrazione
                     $data['registro']=$this->am->getdata_registro(0,$this->filter,$off,$lim);;
                     $data['title']='Benvenuto '.'Amministratore';
                     $filtri=explode('-',$this->filter);
                     $data['fil1']=$filtri[0];
                     $data['fil2']=$filtri[1];
                     $data['fil3']=$filtri[2];
                     $data['fil4']=$filtri[3];
                     $data['fil5']=$filtri[4];
                     if  ($filtri[5]=='NUL') $data['fil6']='Tutte';
                     else $data['fil6']=$filtri[5];
                     //$data['session_id']=$ute;
                     $this->load->view('welcome_adm', $data);
                     break;
                case 'resppub':
                    //Utente Responsabile Publisher Può vedere tutto e operare
                    // anche sugli atti degli altri
                     $data['registro']=$this->am->getdata_registro(0,$this->filter,$off,$lim);
                     $data['title']='Benvenuto '.$this->session->userdata('nome');
                     $filtri=explode('-',$this->filter);
                     $data['fil1']=$filtri[0];
                     $data['fil2']=$filtri[1];
                     $data['fil3']=$filtri[2];
                     $data['fil4']=$filtri[3];
                     $data['fil5']=$filtri[4];
                     if  ($filtri[5]=='NUL') $data['fil6']='Tutte';
                     else $data['fil6']=$filtri[5];
                     //Inserie la welcome all' utente
                     //$data['session_id']=$ute;
                     $this->load->view('welcome_adm', $data);
                        break;
                case 'publisher':
                    //Utente Publisher o Utente resp publisher
                    //Publisher Può vedere tutto ma operare solo sulle sue
                    //echo "Publisher";
                     $data['registro']=$this->am->getdata_registro(0,$this->filter,$off,$lim);
                     $data['title']='Benvenuto '.$this->session->userdata('nome');
                     $filtri=explode('-',$this->filter);
                     $data['fil1']=$filtri[0];
                     $data['fil2']=$filtri[1];
                     $data['fil3']=$filtri[2];
                     $data['fil4']=$filtri[3];
                     $data['fil5']=$filtri[4];
                     if  ($filtri[5]=='NUL') $data['fil6']='Tutte';
                     else $data['fil6']=$filtri[5];
                     //Inserie la welcome all' utente
                     //$data['session_id']=$ute;
                     $this->load->view('welcome_adm', $data);
                        break;
                case 'normal':
                    //Utente Può solo visualizzare il registro
                    //echo "NOrmal";
                    $data['registro']=$this->am->getdata_registro(0,$this->filter,$off,$lim);
                    $data['title']='Benvenuto '.$this->session->userdata('nome');
                    $filtri=explode('-',$this->filter);
                    $data['fil1']=$filtri[0];
                     $data['fil2']=$filtri[1];
                     $data['fil3']=$filtri[2];
                     $data['fil4']=$filtri[3];
                     $data['fil5']=$filtri[4];
                     if  ($filtri[5]=='NUL') $data['fil6']='Tutte';
                     else $data['fil6']=$filtri[5];
                    //Inserie la welcome all' utente
                    //$data['session_id']=$ute;
                    $this->load->view('welcome', $data);

                default: break;
        }

          
        }


         function giornale() {

                 $data['log']=$this->am->get_giornale();
                 $this->load->view('giornale', $data);


         }


         function clona($id=0) {
             
             //Recupero i Tipi Atti per la gestione della combo-box

             $data['tipi_atti']=$this->am->get_tipiatti();
            
             //Caso Clonazione Ultimo Atto
             if ($id==0) {
                $rec=$this->am->getlast_registro();

                  foreach ($rec as $row){
                    $data['ente']=$row['ente'];
                    $data['id_ente']=$this->em->getid_ente($data['ente']);
                    $data['rif']=$row['rif'];
                    $data['oggetto']='';
                    $data['descrizione']=$row['descrizione'];
                    $data['periodo']=$row['periodo'];
                    $data['id_tipo']=$row['id_tipo'];
                    $data['stato']=$row['stato'];

                  }
                  $data['op']='insert';
                  $data['title']='Copia Ultimo Record';
                  $this->load->view('addmodreg',$data);

               }
               //Caso Clonazione Riga Atto $id!=0
               else{
                   $rec=$this->am->getdata_registro($id,'NUL-NUL-NUL-NUL-NUL-NUL');

                  foreach ($rec as $row){
                    $data['ente']=$row['ente'];
                    $data['id_ente']=$this->em->getid_ente($data['ente']);
                    $data['rif']=$row['rif'];
                    $data['oggetto']='';
                    $data['descrizione']=$row['descrizione'];
                    $data['periodo']=$row['periodo'];
                    $data['id_tipo']=$row['id_tipo'];
                    $data['stato']=$row['stato'];
                    $data['codice']=$row['codice'];

                  }
                  $data['op']='insert';
                  $data['title']='Copia Record==>'.$data['codice'];
                  $this->load->view('addmodreg',$data);

               } 


         }


        //Gestisce tutte le operazioni DML sul registro: update,insert
        function addmod($op,$id=0,$id_ente=0) {

             //Reset Filtro in sessione
             //$this->set_filtro();

            //Recupero i Tipi Atti per la gestione della combo-box

            $data['tipi_atti']=$this->am->get_tipiatti();
            if ($op=='ric') { //ricerca ente richiedente

                  $this->load->view('findente');

              }

            if ($op=='add') { //add chiama il form di gestione registro
                  $data['title']='Nuovo Atto  ALBO PRETORIO';
                  $data['op']='insert';
                  $data['rif']='';
                  $data['oggetto']='';
                  $data['id_tipo']='';
                  $data['descrizione']='';
                  $data['periodo']='';
                  $data['id_ente']=$id_ente;
                  //echo $data['id_ente'];
                  
                  $data['ente']=$this->em->get_ente($id_ente);
                  
                  //Creo una nuova entry sul registro Form senza dati
                  $this->load->view('addmodreg',$data);

              }


            if ($op=='mod') { //mod chiama il form di gestione registro
                  //Verifico se è possibile attuare la modifica condizione:
                  //(data corrente < al)
                  $data['title']='Modifica Atto ALBO PRETORIO';
                  $data['op']='update';
                  
                  $id=$this->uri->segment(4);
                  $data['id']=$id;
                  $rec=$this->am->getdata_registro($id,'NUL-NUL-NUL-NUL-NUL-NUL');
                  
                  foreach ($rec as $row){
                    $data['ente']=$row['ente'];
                    $data['rif']=$row['rif'];
                    $data['oggetto']=$row['oggetto'];
                    $data['descrizione']=$row['descrizione'];
                    $data['periodo']=$row['periodo'];
                    $data['id_tipo']=$row['id_tipo'];
                    $data['stato']=$row['stato'];
   
                  }
                  //echo $id.'-'.$data['stato'];
                  //

                  //Creo una nuova entry sul registro Form senza dati
                  $this->load->view('addmodreg',$data);

              }


            if ($op=='insert'){ //recupero dati dal form  e li memorizzo sul DB
               $data['id_utente']=$this->session->userdata('id_user');
               $data['ente']=$_POST['ente'];
               $data['id_ente']=$this->em->getid_ente($_POST['ente']);
               $data['id_tipo']=$_POST['id_tipo'];
               $data['rif']=$_POST['rif'];
               $data['oggetto']=$_POST['oggetto'];
               $data['descrizione']=$_POST['descrizione'];
               $data['periodo']=$_POST['periodo'];
               $this->reset_filtro();
               $this->am->dml_atto($op,$data);
               
               redirect('albo');

            }

            if ($op=='update'){


               //recupero dati dal form  e li memorizzo sul DB
               $data['id_utente']=$this->session->userdata('id_user');
               $data['id']=$_POST['id'];
               $data['ente']=$_POST['ente'];
               $data['id_tipo']=$_POST['id_tipo'];
               $data['rif']=$_POST['rif'];
               $data['oggetto']=$_POST['oggetto'];
               $data['descrizione']=$_POST['descrizione'];
               $data['periodo']=$_POST['periodo'];
               $data['stato']=$_POST['stato'];
               $this->am->dml_atto($op,$data);
               redirect('albo');

            }

            if ($op=='del'){ //recupero dati dal form  e li memorizzo sul DB
               //$data['utente']=$this->session->userdata('id_user');
               $data['id']=$id;
               $this->am->dml_atto($op,$data);
               redirect('albo');

            }

            if ($op=='pub'){ //recupero dati dal form  e li memorizzo sul DB
               //$data['utente']=$this->session->userdata('id_user');
               $data['id']=$id;
               $this->am->dml_atto($op,$data);
               redirect('albo');

            }

             if ($op=='cer'){ //recupero dati dal form  e li memorizzo sul DB
               //$data['utente']=$this->session->userdata('id_user');
               $data['id']=$id;
               $this->am->dml_atto($op,$data);
               redirect('certifica/index/certificato/'.$id);

            }

        }

        function bonifica($op) {


             
             if ($op=='bon'){
               $data['tipi_atti']=$this->am->get_tipiatti();
               $data['title']='Bonifica Atto ALBO PRETORIO';
               $data['op']='bonifica';
                  $id=$this->uri->segment(4);
                  $data['id']=$id;
                  $rec=$this->am->getdata_registro($id,'NUL-NUL-NUL-NUL-NUL-NUL');

                  foreach ($rec as $row){
                    $data['ente']=$row['ente'];
                    $data['rif']=$row['rif'];
                    $data['oggetto']=$row['oggetto'];
                    $data['descrizione']=$row['descrizione'];
                    $data['periodo']=$row['periodo'];
                    $data['id_tipo']=$row['id_tipo'];
                    $data['stato']=$row['stato'];

                  }
                  //echo $id.'-'.$data['stato'];
                  //

                  //Creo una nuova entry sul registro Form senza dati
                  $this->load->view('bonifica',$data);
             }
             else {

               //recupero dati dal form  e li memorizzo sul DB
               $data['id_utente']=$this->session->userdata('id_user');
               $data['id']=$_POST['id'];
               $data['ente']=$_POST['ente'];
               $data['id_tipo']=$_POST['id_tipo'];
               $data['rif']=$_POST['rif'];
               $data['oggetto']=$_POST['oggetto'];
               $data['descrizione']=$_POST['descrizione'];
               $data['periodo']=$_POST['periodo'];
               $data['stato']=$_POST['stato'];
               $this->reset_filtro();
               $this->am->dml_atto($op,$data);
               
               redirect('albo');

               }

        }


       

 }
