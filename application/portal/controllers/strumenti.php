<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.eu>
 */


class strumenti extends CI_Controller {
var $filter;
        // var per variabili della classe;
        function Strumenti() {
		parent::__Construct();
                $this->load->library('pagination');
                $this->load->model('portal_model','pm');
                $this->load->model('cross_data','cd');
                $this->load->model('login_model','lm');
                	}

	function index($func=0) {


                $id_usr=$this->session->userdata('id_user');
                if ($func==1) {

                    $this->filter=$this->cd->leggi_filtro('messaggi',$id_usr);
                    $offset=$this->uri->segment(4);
                    if ($offset==0) $offset=0;
                    $limit=10;
                    $config['uri_segment'] = 4;
                    $config['base_url'] = base_url().'portal.php/strumenti/index/1';
                    $config['total_rows'] =$this->cd->Count_All('vmessaggi',$this->filter);
                    
                    $config['per_page'] = '10';
                    $this->pagination->initialize($config);
                    //echo $this->filter;
                    $data['messaggi']=$this->pm->getdata_messaggi($this->filter,$offset,$limit);
                    $data['pag']=$this->pagination->create_links();

                    $data['title']='Benvenuto '.$this->session->userdata('nome');
                    $filtri=explode('-',$this->filter);
                    $data['fil1']=$filtri[0];
                    $data['fil2']=$filtri[1];
                    $this->load->view('messaggi', $data);


                }
                if ($func==2) {

                    $this->filter=$this->cd->leggi_filtro('rubrica',$id_usr);
                    $offset=$this->uri->segment(4);
                    if ($offset==0) $offset=0;
                    $limit=10;
                    $config['uri_segment'] = 4;
                    $config['base_url'] = base_url().'portal.php/strumenti/index/2';
                    $config['total_rows'] =$this->cd->Count_All('vrubrica',$this->filter);
                    
                    $config['per_page'] = '10';
                    $this->pagination->initialize($config);
                    //echo $this->filter;
                    $data['rubrica']=$this->pm->getdata_rubrica($this->filter,$offset,$limit);
                    $data['pag']=$this->pagination->create_links();
                    $data['title']='Benvenuto '.$this->session->userdata('nome');
                    $filtri=explode('-',$this->filter);
                    $data['fil1']=$filtri[0];
                    if ($filtri[1]=='NUL') $data['fil2']='Tutti';
                    else $data['fil2']=$filtri[1];

                    $this->load->view('rubrica', $data);


                }
                //Gestione Postit
                if ($func==3) {

                    $id=$this->session->userdata('id_user');
                    $data['nr_postit']=$this->pm->num_postit($id);
                    $data['max_rif']=$this->pm->rif_max($id);
                    if ($data['nr_postit'] > 0) $data['postit']=$this->pm->get_postit($id);
                    else $data['postit']=0;
                    $data['title']='Gestione Postit';
                    //echo $data['nr_postit'];
                    $this->load->view('sticky_notes',$data);

                }

                if ($func==4) {

                    $data['title']='log Accessi';
                    $this->load->view('log_accessi',$data);

                }


                       
	}


       
        function getlog(){
            
          $username= $this->input->post('par');
               
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




        //Gestione Postit in DB
        function gest_pst(){

            $par= $this->input->post('par');
            //echo 'parametri='.$par;
            $sp=explode('-',$par);

            $op=$sp[0];
            $rif=$sp[1];
            $content=$sp[2];
            $this->pm->gest_note($op,$rif,$content);

        }


         //Imposto il filtro in sessione viene chiamata dalla pagina di riepilogo
        //View del registro del repertorio Welcome_adm,Welcome
        function set_filtro($masch){

            $id_usr=$this->session->userdata('id_user');

            switch ($masch){
                case 'rubrica': 
                    if ($_POST['cognome']=='') $cognome='NUL';
                    else $cognome=$_POST['cognome'];
                    if ($_POST['sett']=='') $sett='NUL';
                    else $sett=$_POST['sett'];
                    $this->filter=$cognome.'-'.$sett.'-';
                    $this->cd->registra_filtro($this->filter,$masch,$id_usr);
                    $this->index(2);
                    break;
                case 'messaggi':
                    if ($_POST['mittente']=='') $mittente='NUL';
                    else $mittente=$_POST['mittente'];
                    if ($_POST['oggetto']=='') $oggetto='NUL';
                    else $oggetto=$_POST['oggetto'];
                    $this->filter=$mittente.'-'.$oggetto.'-';
                    $this->cd->registra_filtro($this->filter,$masch,$id_usr);
                    $this->index(1);
                    break;
            }

        }

        function invia(){


                $par= $this->input->post('par');
                //echo 'parametri='.$par;
                $sp=explode('-',$par);

                $id_dest=$sp[0];
                $oggetto=$sp[1];
                $testo=$sp[2];
                $rif=$sp[3];
                $this->pm->record_msg($id_dest,$oggetto,$testo,$rif);
           
        }

        function upd_stato($op,$id){

        //echo 'op='.$op.'id='.$id;
        if ($op=='del'){
            //Richiesta di cancellazione messaggio;
            $this->pm->change_stato($id,'D');
        }
        else {
            //Aggiornamento stato
            $this->pm->change_stato($id,'R');

        }
        redirect('strumenti/index/1');
        
        }



}
?>
