<?php 
class repertorio extends CI_Controller {

        // var per variabili della classe;
        var $offset=0;
        var $limit=0;
        var $filter;
        function repertorio() {
               parent::__construct();
               $this->load->model($this->config->item('share_model'),'cd');
               // $this->load->library('pagination');
               

	}

         function set_filtro(){
            
            if ($_POST['tipo']=='') $tipo='NUL';
            else $tipo=$_POST['tipo'];
            if ($_POST['oggetto']=='') $tipo='NUL';
            else $tipo=$_POST['oggetto'];

            
        }

        function reset_filtro(){
            $this->filter='NUL-NUL-NUL';
            $id_usr=$this->session->userdata('id_user');
            $this->cd->registra_filtro($this->filter,'repertorio',$id_usr);
            
        }

        function index( ) {
            
               
                //$this->filter=$this->cd->leggi_filtro('repertorio',$id_usr);

                //$offset=$this->uri->segment(3);
                //if ($offset==0) $offset=0;
                //$limit=10;
                //$config['uri_segment'] = 3;
                //$config['base_url'] = base_url().'albo.php/albo/index';
                //$config['total_rows'] =$this->cd->Count_All('vregistro',$this->filter);
                //$config['per_page'] = '10';
                //$this->pagination->initialize($config);
                //echo $this->filter
                $this->filter='NUL-NUL-NUL-NUL-NUL-NUL';
                $this->_getregistro('public',0,10,10);
               
       }

       
       //Estrae il registro  con applicazione dei filtri
      function _getregistro($ruolo,$num_rows_fil,$off,$lim){
          
       
       $data['registro']=$this->cd->getregistropub(0,$this->filter,$off,$lim);
       //print_r($data);
       $this->load->view('public_view', $data);

          


        }
       

 }
