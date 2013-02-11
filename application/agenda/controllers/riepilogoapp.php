<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class riepilogoapp extends CI_Controller {

        // var per variabili della classe;
        var $offset=0;
        var $limit=0;
        var $run=0;
        var $filter;
        var $ruolo='';

        function riepilogoapp() {

		parent::__construct();

                $this->load->model('Mycal_model','cm');
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

function index($op,$id,$titolo=null) {

    switch($op){
        case 'delega': 
                $data['ruolo']=$this->ruolo;

                $data['id_appuntamento']=$id;
                $data['title']='Gestione Deleghe';
                $result=$this->cm->get_delegati_app($id);
                $data['delegati']=$result['records'];

                $data['dataapp']=$this->uri->segment(5);
                $data['oraapp']=$this->uri->segment(6);
                $data['minapp']=$this->uri->segment(7);
                $this->load->view('delegatiapp',$data);
               break;
        case 'cancella':
                $data['id']=$id;
                $op='del';
                $this->cm->gest_appuntamento($data,$op);
                $this->gest_app();
                break;
        case 'titolari' :
                $data['id_appuntamento']=$id;
                $data['title']='Associazione Titolari';
                $result=$this->cm->get_titolari_app($id);
                $data['titolari']=$result['records'];
                $data['nrtitolari']=$result['num_rows'];
                $data['ruolo']=$this->ruolo;
                $data['titolo']=$titolo;
                $data['caller']='riepilogoapp';
                $this->load->view('sctitolari',$data);
                break;
        case 'notifica' :
            $data['id_appuntamento']=$id;
            $data['time']=$this->uri->segment(5);
            $data['richiedente']=$this->uri->segment(6);
            $data['descrizione']=$this->uri->segment(7);
            $result=$this->cm->get_titolari_app($id);
            $data['titolari']=$result['records'];
            $this->notifica($id,$data);

            break;

    }

}

function set_filtro(){
            if ($_POST['richiedente']=='') $richiedente='NUL';
            else $richiedente=$_POST['richiedente'];

            if ($_POST['titolare']=='') $titolare='NUL';
            else $titolare=$_POST['titolare'];

            if ($_POST['dataapp']=='') $dataapp='NUL';
            else $dataapp=$_POST['dataapp'];

            $this->filter=$richiedente.'-'.$titolare.'-'.$dataapp.'-';
            $id_usr=$this->session->userdata('id_user');

            $this->cd->registra_filtro($this->filter,'agenda',$id_usr);
            $this->gest_app();
        }


	function gest_app( ) {

            $this->load->library('pagination');
            $id_usr=$this->session->userdata('id_user');


            $this->filter=$this->cd->leggi_filtro('agenda',$id_usr);

            $offset=$this->uri->segment(3);
            if ($offset==0) $offset=0;
                $limit=10;
                $config['uri_segment'] = 3;
                $config['base_url'] = base_url().'agenda.php/riepilogoapp/gest_app';
                $config['total_rows'] =$this->cd->Count_All('vagenda',$this->filter);
                $config['per_page'] = '10';
                $this->pagination->initialize($config);
                //echo $this->filter;
                $this->_getdata_appuntamenti($this->ruolo,$config['total_rows'],$offset,$limit);

        }




        function _getdata_appuntamenti($ruolo,$num_rows_fil,$off,$lim) {

            $data['num_rows_fil']=$num_rows_fil;
            $data['num_rows']=$this->cd->num_rows('vagenda');
            $data['ruolo']=$ruolo;
            $data['appuntamenti']=$this->cm->getdata_appuntamenti($this->session->userdata('id_user'),$this->filter,$off,$lim);
            $data['pag']=$this->pagination->create_links();
            $data['title']='Gestione appuntamenti ';
            $filtri=explode('-',$this->filter);
            $data['fil1']=$filtri[0];
            $data['fil2']=$filtri[1];
            $data['fil3']=$filtri[2];
            $this->load->view('appuntamenti', $data);

        }



        function notifica($id,$rec) {

         //Ricercare email in anagrafica utenti
         //Oggetto appuntamento

         if (strlen($rec['time'])==12) {
             $ora=substr($rec['time'],8,2);
             $min=substr($rec['time'],10,2);
         }
         else {
             $ora=substr($rec['time'],8,1);
             $min=substr($rec['time'],9,2);

         }
         $subject = "Schedulato appuntamento alle ore ".$ora.":".$min." del ".substr($rec['time'],0,2).'-'.substr($rec['time'],2,2).'-'.substr($rec['time'],4,4);
         $message = "Richiedente:".urldecode($rec['richiedente'])."\nArgomento:".urldecode($rec['descrizione']);
         //Casella del sistema
         $id_usr=$this->session->userdata('id_user');
         //$from = "g.cipriani@comuneortona.ch.it";
         $from=$this->cd->get_mail($id_usr);
         $headers = "From:" . $from;
         $to='';
         foreach($rec['titolari'] as $tit){


             $id=$tit['id_titolare'];
             $mail=$tit['email'];
             if ($mail!='')$to.=$mail.',';
         

        }
		if (mail($to,$subject,$message,$headers))
                echo 'notifica inviata con successo';
        else
        
            echo 'inoltro non effettuato contattare amministratore delsistema';

        $this->gest_app();
        //echo 'subject='.$subject.'message='.$message.'from='.$from.'to='.$to;
      }



    }