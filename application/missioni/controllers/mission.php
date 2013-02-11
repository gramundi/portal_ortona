<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * Sw di Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

class Mission extends CI_Controller {

        var $offset=0;
        var $limit=0;
        var $run=0;
        var $filter;
        var $ruolo_miss='';

        // var per variabili della classe;
        function Mission() {
		parent::__construct();
                $id_usr=$this->session->userdata('id_user');
                if ($id_usr==''){
                      echo '<h3>acesso non consentito<h3>';
                      exit;
                 }
                $this->load->helper('flexigrid');
                $this->load->model('ajax_model');
                $this->load->model('mission_model','mm');
                
                $this->load->model('/../../portal/models/cross_data','cd');
                $this->load->helper('date');
		$this->load->library('table');
                $this->load->library('pagination');
        }
	
	function index() {

                         
                $id_usr=$this->session->userdata('id_user');
                if ($this->cd->get_privilegi($id_usr,'Missioni')=='no privileges'){
                        $this->load->view('../../share_views/no_priv.php');
                        return;
                }
                if ($this->ruolo_miss=='') $this->ruolo_miss=$this->cd->get_privilegi($id_usr,'Missioni');
                //Leggo il filtro impostato sulla maschera
                //La prima volta sulla tabella filtri è impostato il filtro nul
                $this->filter=$this->cd->leggi_filtro('missioni',$id_usr);
                //echo $this->filter;
                $offset=$this->uri->segment(3);
                if ($offset==0) $offset=0;
                $limit=10;
                $config['uri_segment'] = 3;
                $config['base_url'] = base_url().'missioni.php/mission/index';
                $config['total_rows'] =$this->cd->Count_All('vmissioni',$this->filter);
                $config['per_page'] = '10';
                $this->pagination->initialize($config);
                
                $this->_getmissioni($id_usr,$this->ruolo_miss,$limit,$offset);
              
       }

       // Intercetta le chiamate alle funzioni di gestione Missione

       function manage($id,$op) {
            //Carico Le librerie ed utility del framework
            $this->load->helper('form');
            
            // $id."-".$op;
            switch ($op) {
                case 'app':$this->_approva($id,'app');break;
                case 'dis':$this->_approva($id,'dis');break;
                case 'det':$this->riepilogo_spese($id);break;
                case 'del':$this->mm->delete($id);redirect('mission');break;
                case 'sta':redirect('report/index/'.$id);
                case 'cln':$this->clona($id);break;
            }
	}


        function clona($id) {

         $data['capitoli']=$this->mm->get_Capitoli();
         $rec=$this->mm->get_missione($id);
         foreach ($rec as $row){
             $data['ogg']=$rec['oggetto'];
             $data['capitolo']=$rec['capitolo'];
             $data['loc']=$rec['citta'];
             $data['data_r']=$rec['data_r'];
             $data['data_p']=$rec['data_p'];
             $data['spese']='';
             


         }
         $data['op']='add';
         $data['title']='Clona Record';
         $this->load->view('addmodmiss',$data);

        }


        //Approva e disapprova le missioni aggiornando i campi liquidato e residuo
        //sulla tabella capitoli
        function _approva($id,$op){

            //controllo se Posso Approvare
            //costo missione <=residuo sul capitolo di spesa della Missione;
            //

            $residuo=$this->mm->GetResiduoCap($id);
            //Calcolo il costo della missione sommando tutte le spese
            $costo=$this->mm->GetCostoMiss($id);
            if (($op=='app') && ($costo==0)) {
                $data['msg']='Impossibile Approvare Missione Senza Costi';
                $this->load->view('msg', $data);
                return;
            }
            if ($op=='app'){
                    //echo $residuo.'----'.$costo;
                    if ($residuo > $costo ){

                        //Approvazione missione cambio stato e registro costo Totale
                        // su Missioni;
                        $this->mm->update_stato($id,2,$costo);
                        redirect('mission');

                    }
                    else {

                        //impossibile approvare missione residuo in capitolo insufficiente;
                        $str='Impossibile Approvare Missione Dipsonibilità='.$residuo;
                        $str=$str.'Missione da approvare importo='.$costo;
                        $data['msg']=$str;
                        $this->load->view('msg', $data);
                
                    }
            }
            else {
                //Disapprovo Missione
                $this->mm->update_stato($id,1,$costo);
                redirect('mission');

            }
         
        }
        
        function insupd($op) {


            $this->load->model('mission_model','mm');
         
            if (isset($_POST['id_user'])) $id=$_POST['id_user'];
            else $id=$this->session->userdata('id_user');
            
            $data['id_ute']=$id;
            //echo 'capitolo='.$_POST['capitolo'];
            $data['capitolo']=$_POST['capitolo'];
            $data['oggetto']=$_POST['oggetto'];
            $data['localita']=$_POST['localita'];
            
            $data['data_p']=$_POST['data_p'];
            $data['data_r']=$_POST['data_r'];
            if ($op=='add'){
                      $mission=0;
            }
            else $mission=$_POST['missione'];
            $this->mm->insert_update($mission,$data);
            redirect('mission');
        }

        //Aggiungi Una missione ad Utente da Parte di un utente Admin
        function newmissadm($user,$id_user){ 

              $data['capitoli']=$this->mm->get_Capitoli();
              $data['op']='add';
              $data['missione']='';
              $data['title']='Creazione Missione';
              $data['capitolo']=$this->mm->get_capitoloutente($id_user);
              $data['ogg']='';
              $data['loc']='';
              $data['data_p']='';
              $data['data_r']='';
              $data['id_user']=$id_user;
              $data['username']=$user;
              $data['spese']='';
              $this->load->view('addmodmiss',$data);

          }


        function addmod($op) {

          
            $data['op']=$op;
            $data['capitoli']=$this->mm->get_Capitoli();
            if ($op=='add') {

              $ruolo=$this->cd->get_privilegi($id_usr,'Missioni');
              //echo $user;
              if ($ruolo=='admin' or $ruolo='respmiss') {
                  $this->load->view('finduser');
                  return;
              }
              else //caso inserzione  missione utente loggato
              {

                  $data['spese']=0;
                  $data['missione']='';
                  $data['title']='Creazione Missione';
                  $id=$this->session->userdata('id_user');;
                  $data['capitolo']=$this->mm->get_capitoloutente($id);
                  $data['ogg']='';
                  $data['loc']='';
                  $data['data_p']='';
                  $data['data_r']='';
              }
            }
           else {
                  
                  $id=$this->uri->segment(4);
                  $data['spese']=$this->mm->check_spesemiss($id);
                  $data['missione']=$id;
                  $data['title']='Modifica Dati Missione';
                  $res=$this->mm->get_missione($id);
                  $data['ogg']=$res['oggetto'];
                  $data['capitolo']=$res['capitolo'];
                  $data['loc']=$res['citta'];
                  $data['data_p']=$res['data_p'];
                  $data['data_r']=$res['data_r'];

            }
              
          $this->load->view('addmodmiss',$data);

       }


        //Imposto il filtro in sessione viene chiamata dalla pagina di riepilogo
        //View del registro del repertorio Welcome_adm,Welcome
        function set_filtro(){
            if ($_POST['capitolo']=='') $capitolo='NUL';
            else $capitolo=$_POST['capitolo'];
            if ($_POST['cognome']=='') $cognome='NUL';
            else $cognome=$_POST['cognome'];
            if ($_POST['localita']=='') $localita='NUL';
            else $localita=$_POST['localita'];
            $this->filter=$capitolo.'-'.$cognome.'-'.$localita.'-';
            $id_usr=$this->session->userdata('id_user');
            $this->cd->registra_filtro($this->filter,'missioni',$id_usr);
            $this->index();
        }







       //Ricerca tutte le missioni dell' utente loggato nel caso dell'amministratore estrae tutte
       //le mision presenti nell tabella Missioni
       function _getmissioni($id_usr,$ruolo,$lim,$off){


        $data['ruolo']=$ruolo;
        $data['missioni']=$this->mm->get_MissionUte($id_usr,$ruolo,$this->filter,$lim,$off);

        $data['title']='Buongiorno'.'Amministratore';
        $data['pag']=$this->pagination->create_links();
        $data['title']='Benvenuto '.$this->session->userdata('nome');
        $filtri=explode('-',$this->filter); 
        $data['fil0']=$filtri[0];
        $data['fil1']=$filtri[1];
        $data['fil2']=$filtri[2];
        $this->load->view('welcome_adm', $data);

        }

         function riepilogo_spese($id){

            // Integrazione componente flexgrid con il modulo di gestione Missioni

           /*
		 * 0 - display name
		 * 1 - width
		 * 2 - sortable
		 * 3 - align
		 * 4 - searchable (2 -> yes and default, 1 -> yes, 0 -> no.)
		 */


                  $colModel['tipo'] = array('Tipo',150,TRUE,'left',0);
                  $colModel['id'] = array('Id',40,FALSE,'center',1);
                  $colModel['data'] = array('Data',80,FALSE,'center',1);
                  $colModel['descrizione'] = array('Descrizione',300,TRUE,'left',0);
		  $colModel['qta'] = array('Qta',20,TRUE,'center',0);
		  $colModel['cu'] = array('Costo Unitario',80,TRUE,'left',0);
            	/*
		 * Aditional Parameters
		 */
		$gridParams = array(
		'width' => 800,
		'height' => 200,
		'rp' => 10,
		'rpOptions' => '[10,15,20,25,40]',
		'pagestat' => 'Displaying: {from} to {to} of {total} items.',
		'blockOpacity' => 0.5,
		'title' => 'Riepilogo Spese Trasferta',
		//'showTableToggleBtn' => true
		);

		/*
		 * 0 - display name
		 * 1 - bclass
		 * 2 - onpress
		 */
                $buttons[] = array('Cancella','delete','test');
		$buttons[] = array('separator');
		$buttons[] = array('Seleziona Tutti','add','test');
		$buttons[] = array('Deseleziona','delete','test');
		$buttons[] = array('separator');
                $buttons[] = array('Aggiungi','add','aggiungi');
                $buttons[] = array('separator');
                $buttons[] = array('Modifica','add','modifica');

                //Get a Mission Title
                $data['title']=$this->ajax_model->get_mission($id);
                $data['oremiss']=$this->ajax_model->get_oremiss($id);
                $data['missione']=$id;
		$data['periodo']=$this->mm->GetDateMiss($id);
                $data['tspese']=$this->mm->Get_Tipispese();


		//Build js
		//View helpers/flexigrid_helper.php for more information about the params on this function
		//Costruisce il js della griglia flexgrid 
                //Libreria nell' helper
                $grid_js = build_grid_js('flex1',site_url()."/ajax/index/$id",$colModel,'id','asc',$gridParams,$buttons);
		$data['js_grid'] = $grid_js;
                //echo 'load-flexgrid()';
		$this->load->view('flexigrid',$data);
	}
 }
