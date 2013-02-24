<?php 
class repertorio extends CI_Controller {

        // var per variabili della classe;
        var $offset=0;
        var $limit=0;
        var $filter;
        function repertorio() {
               parent::__construct();
               $this->load->model($this->config->item('share_model'),'cd');
               $this->load->library('pagination');
              
	}

     

        function index( ) {
            
               
                
                //l'utente ha richiesto ricerca
                if (isset($_POST['tipo'])) 
                    //registro il filtro in sessione
                    $this->session->set_userdata('tipo',$_POST['tipo']);
               if (isset($_POST['oggetto'])) 
                    //registro il filtro in sessione
                    $this->session->set_userdata('oggetto',$_POST['oggetto']);
                
                //se il filtro è in session me lo riprendo altrimenti azzero il filtro
                if ($this->session->userdata('tipo'))
                    $tipo=$this->session->userdata('tipo');
                else 
                    $tipo='NUL';
                
                //se il filtro è in session me lo riprendo altrimenti azzero il filtro
                if ($this->session->userdata('oggetto'))
                    $oggetto=$this->session->userdata('oggetto');
                else 
                    $oggetto='NUL';
                $this->filter=$tipo.'-'.$oggetto.'-';
                
                $offset=$this->uri->segment(3);
                if ($offset==0) $offset=0;
                $limit=10;
                $config['uri_segment'] = 3;
                $config['base_url'] = base_url().'repertorio.php/repertorio/index';
                
                $config['total_rows'] =$this->cd->Count_All('vregistropub',$this->filter);
                $config['per_page'] = '10';
                $this->pagination->initialize($config);
                $this->_getregistro('public',0,$offset,$limit);
               
       }

       
       //Estrae il registro  con applicazione dei filtri
      function _getregistro($ruolo,$num_rows_fil,$off,$lim){
          
       
       $data['registro']=$this->cd->getregistropub(0,$this->filter,$off,$lim);
       $data['title']='Repertorio Comune Ortona';
       $this->load->view('public_view', $data);

          


        }
        
       function getdata(){
           
           $id=$_GET['id'];
           $info=$this->cd->get_infoatto($id);
           echo json_encode($info);
           
       } 
       

 }
