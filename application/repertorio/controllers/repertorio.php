<?php 
class repertorio extends CI_Controller {

        // var per variabili della classe;
        var $offset=0;
        var $limit=0;
        var $filter;
        function repertorio() {
               parent::__construct();
              $this->load->model($this->config->item('share_model'),'cd');
              //$this->load->model('cross_data','cd');
		
		$this->load->library('pagination');
              
	}

     

        function index( ) {
            
               
                //l'utente ha richiesto ricerca
                if (isset($_POST['tipo'])) 
                    //registro il filtro in sessione
                    $this->session->set_userdata('filter',$_POST['tipo']);
               
                //se il filtro è in session me lo riprendo altrimenti azzero il filtro
                if ($this->session->userdata('filter'))
                    $this->filter=$this->session->userdata('filter');
                else 
                    $this->filter='NUL';
                $offset=$this->uri->segment(3);
                if ($offset==0) $offset=0;
                $limit=3;
                $config['uri_segment'] = 3;
                $config['base_url'] = base_url().'repertorio.php/repertorio/index';
                
                $config['total_rows'] =$this->cd->Count_All('vregistropub',$this->filter);
                $config['per_page'] = '3';
                $this->pagination->initialize($config);
                $this->_getregistro('public',0,$offset,$limit);
               
       }

       
       //Estrae il registro  con applicazione dei filtri
      function _getregistro($ruolo,$num_rows_fil,$off,$lim){
          
       
       $data['registro']=$this->cd->getregistropub(0,$this->filter,$off,$lim);
       $data['title']='Repertorio Comune Ortona';
       $this->load->view('public_view', $data);

          


        }
       

 }
