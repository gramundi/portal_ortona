<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Controller di Navigazione Albo Pretorio
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.eu>
 */

class Albo_Pretorio extends CI_Controller {

        // var per variabili della classe;
        function Albo_Pretorio() {
		parent::__Construct();
                // devo riferire i model del repertorio
                $this->load->model('cross_data','cd');
                $this->load->library('pagination');
                }

	function index($tipo='') {

             if ($tipo!='') {
                 //Ricavo gli atti di quel tipo
                 $data['tipo']=$tipo;
                 $data['atti']=$this->cd->get_atti($tipo);
                 $data['tipi']=$this->cd->get_tipiatti();
                 $this->load->view('categorie',$data);
              }

             else {
                     //Leggo le tipologie presenti e le propongo in scelta
                     $data['tipo']='NUL';
                     $data['atti']='';
                     $data['tipi']=$this->cd->get_tipiatti();
                     $this->load->view('categorie',$data);
            }
            $offset=$this->uri->segment(3);
            if ($offset==0) $offset=0;
            $limit=10;
            $config['uri_segment'] = 3;
            $config['base_url'] = base_url().'portal.php/albo_pretorio/index';
            $config['total_rows'] =$this->cd->Count_All('vregistro',$tipo);
            echo $config['total_rows'];
            $config['per_page'] = '10';
            $this->pagination->initialize($config);
            $data['pag']=$this->pagination->create_links();
	}


       
}
?>