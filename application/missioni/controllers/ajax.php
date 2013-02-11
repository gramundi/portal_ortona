<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Ajax extends CI_Controller {
// Attenzione un errore di sintassi dentro questo modulo implica il non funzionamento della flexgrid poichè
// Tale controller rappresenta il gestore del flexgrid


    function Ajax ()
	{
		parent::__construct();
		$this->load->model('ajax_model');
                //log_message('debug', 'ajax controller');
		$this->load->library('flexigrid');
                
	}
	
	function index($id)
	{
		log_message('debug', 'ajax controller');
                
                //List of all fields that can be sortable. This is Optional.
		//This prevents that a user sorts by a column that we dont want him to access, or that doesnt exist, preventing errors.
		$valid_fields = array('data','descrizione','qta','cu');
		
		$this->flexigrid->validate_post('id','asc',$valid_fields);

		$records = $this->ajax_model->get_spese($id);

                log_message('debug',$records['record_count'] );
                //echo 'numero rec='.$records['record_count'];
                $this->output->set_header($this->config->item('json_header'));

                    /*
                     * Json build WITH json_encode. If you do not have this function please read
                     * http://flexigrid.eyeviewdesign.com/index.php/flexigrid/example#s3 to know how to use the alternative
                     */
                if ($records['record_count']==0){
                    
                    $record_items[]=array();

                    }
                foreach ($records['records']->result() as $row)
                    {
                            $record_items[] = array($row->id,
                            $row->tipo,
                            $row->id,
                            $row->data,
                            $row->descrizione,
                            $row->qta,
                            $row->cu,
                            '<a href=\'#\'><img border=\'0\' src=\''.$this->config->item('base_url').'images/close.png\'></a> '
                            );
                    }
                    //Print please
                    //echo 'ntrec='.$records['record_count'];
                    $this->output->set_output($this->flexigrid->json_build($records['record_count'],$record_items));

                }
	
	
	//Cancellazione Spesa
	function deletec()
	{
		$spese_ids_post_array = split(",",$this->input->post('items'));
		
		foreach($spese_ids_post_array as $index => $spese_id)
			if (is_numeric($spese_id) && $spese_id >= 0)
				$this->ajax_model->delete_spese($spese_id);
						
			
		$error = "Selected spese (id's: ".$this->input->post('items').") deleted with success";

		$this->output->set_header($this->config->item('ajax_header'));
		$this->output->set_output($error);
	}

        //Aggiunta Spesa
	function addmod_row()
	{
            //$nu= $this->input->post('par');
            $id_mis=$_POST['missione'];
            $op=$_POST['op'];
            $tipo=$_POST['tipo'];
            $qta=$_POST['qta'];
            $data_s=$_POST['data'];
            $des=$_POST['des'];
            $cu=$_POST['cu'];
            if ($op=='add'){

                echo 'Aggiungo Spesa';

                $this->ajax_model->add_row($tipo,$id_mis,$data_s,$des,$qta,$cu);

                }
                
                else {
                    //modifica spesa
                    $id_s=$_POST['id_s'];
                    $this->ajax_model->mod_row($id_s,$id_mis,$tipo,$data_s,$qta,$des,$cu);

                }
                 echo 'addmod_row';
                
	}

       

}
?>