<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class gestcontatti extends CI_Controller {

        // var per variabili della classe;
        var $offset=0;
        var $limit=0;
        var $run=0;
        var $filter;
        var $ruolo='';

        function gestcontatti() {

		parent::__construct();
                $this->load->library('pagination');
                $this->load->model($this->config->item('share_model'),'cd');
                $this->load->model('contatti_model','cm');
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



        //Imposto il filtro in sessione viene chiamata dalla pagina di riepilogo
        //View del registro del repertorio Welcome_adm,Welcome
        function set_filtro(){

            if ($_POST['cognome']=='') $cognome='NUL';
            else $cognome=$_POST['cognome'];

            if ($_POST['ragsoc']=='') $ragsoc='NUL';
            else $ragsoc=$_POST['ragsoc'];

            $this->filter=$cognome.'-'.$ragsoc.'-';
            $id_usr=$this->session->userdata('id_user');
            $this->cd->registra_filtro($this->filter,'contatti',$id_usr);

            $this->index();
        }

 function index( ) {


                 $id_usr=$this->session->userdata('id_user');


                //Leggo il filtro impostato sulla maschera
                //La prima volta sulla tabella filtri è impostato il filtro nul
                //NUL-NUL-NUL-NUL
                $this->filter=$this->cd->leggi_filtro('contatti',$id_usr);
                //echo $this->filter;
                //echo 'prima volta '.$this->filter;
                $offset=$this->uri->segment(3);
                if ($offset==0) $offset=0;
                $limit=10;
                $config['uri_segment'] = 3;
                $config['base_url'] = base_url().'agenda.php/gestcontatti/index';
                $config['total_rows'] = $this->cd->Count_All('vcontatti',$this->filter);
                $config['per_page'] = '10';
                $this->pagination->initialize($config);
                //echo $this->filter;
                $this->_getcontatti($config['total_rows'],$offset,$limit);


      }


      function _getcontatti($num_rows_fil,$off,$lim){

       //Get Ordinanti Ammessibili
       $data['num_rows_fil']=$num_rows_fil;
       $data['num_rows']=$this->cd->num_rows('vcontatti');
       $data['ruolo']=$this->ruolo;
       $data['pag']=$this->pagination->create_links();
       $data['contatti']=$this->cm->getdata_contatti($this->filter,$off,$lim);;
       $data['title']='Anagarafica Contatti Calendario';
       $filtri=explode('-',$this->filter);

       $data['fil1']=$filtri[0];

       $data['fil2']=$filtri[1];
   
       $this->load->view('vcontatti', $data);
      }

      //operazioni DML in tabella contatti
      function dmlcontatti($op=null){

          if ($op==null) $op=$_POST['op'];
          switch ($op) {

          case 'add':
              $rec['nome']=$_POST['nome'];
              $rec['cognome']=$_POST['cognome'];
              $rec['ragsoc']=$_POST['ragsoc'];
              $rec['telef']=$_POST['telef'];
              $rec['sito']=$_POST['sito'];
              $rec['email']=$_POST['email'];
              $rec['emailsec']=$_POST['emailsec'];
              $rec['cell1']=$_POST['cell1'];
              $rec['cell2']=$_POST['cell2'];
              $rec['note']=$_POST['note'];
              $this->cm->dml_contatti($op,$rec);
              break;
          case 'mod':
              $rec['id']=$_POST['idcontatto'];
              $rec['nome']=$_POST['nome'];
              $rec['cognome']=$_POST['cognome'];
              $rec['ragsoc']=$_POST['ragsoc'];
              $rec['telef']=$_POST['telef'];
              $rec['sito']=$_POST['sito'];
              $rec['email']=$_POST['email'];
              $rec['emailsec']=$_POST['emailsec'];
              $rec['cell1']=$_POST['cell1'];
              $rec['cell2']=$_POST['cell2'];
              $rec['note']=$_POST['note'];
              $this->cm->dml_contatti($op,$rec);
              break;
          case 'del':
              $rec['id']=$this->uri->segment(4);
             
              $this->cm->dml_contatti($op,$rec);
              break;


         }
         $this->index();
      }

      function get_contatto() {

         

           $param= $this->input->post('par');
           $sp=explode('-',$param);

           $records = $this->cm->get_contatto($sp[0]);
           if ($records['num_rows']==0) return;
           $res=json_encode($records['records']);
           echo $res;
      }

}
