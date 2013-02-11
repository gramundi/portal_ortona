<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Eye View Design CMS module Ajax Model
 *
 * PHP version 5
 *
 * @category  CodeIgniter
 * @package   EVD CMS
 * @author    Frederico Carvalho
 * @copyright 2008 Mentes 100Limites
 * @version   0.1
*/

class Ajax_model extends CI_Model
{
	/**
	* Instanciar o CI
	*/
	public function Ajax_model()
    {
        parent::__construct();
        $this->load->library('flexigrid');
		
    }
	
	
        public function get_spese($mission=0)
	{
		//Select table name
		$table_name = "spese";

		//Build contents query
                $this->db->select('tipo,spese.id,spese.data,spese.descrizione,spese.qta,spese.cu');
                $this->db->from('spese');
                $this->db->join('tipispese', 'tipispese.id = spese.id_tipo');
                $this->db->where('spese.id_trasferta',$mission);

               	$this->flexigrid->build_query();


		//$this->db->select('id,descrizione,qta,cu')->from($table_name);
                //$this->db->where('id',$mission);
		//$this->CI->flexigrid->build_query();

		//Get contents
		$return['records'] = $this->db->get();

		//Build count query
		$this->db->select('count(id) as record_count')->from($table_name);

		$this->flexigrid->build_query(FALSE);
		$this->db->where('spese.id_trasferta',$mission);
                $record_count = $this->db->get();
                $row = $record_count->row();
                log_message('debug', 'NR-SPESE:'.$row->record_count);

		//Get Record Count
		$return['record_count'] = $row->record_count;

		//Return all
		return $return;
	}

        public function get_mission($id)
	{
		//Select table name
		$table_name = "missioni";

		//Build contents query
                $this->db->select('oggetto');
                //$this->db->from('missioni');
                $this->db->where('id',$id);
        	//$this->CI->flexigrid->build_query();
		//Get contents
		$q = $this->db->get('missioni');
                return $q->row()->oggetto;
	}


        public function get_oremiss($id)
	{
		//Select table name
		$table_name = "missioni";

		//Build contents query
                $this->db->select('ore_miss');
                //$this->db->from('missioni');
                $this->db->where('id',$id);
        	//$this->CI->flexigrid->build_query();
		//Get contents
		$q = $this->db->get('missioni');
                return $q->row()->ore_miss;
	}


        public function delete_spese($spese_id)
	{

                log_message('debug', 'DELETE-SPESE:'.$spese_id);
		$delete_spesa = $this->db->query('DELETE FROM spese WHERE id='.$spese_id);


		return TRUE;
	}

	
        public function get_id_tipo($tipo)
        {
            $sql='SELECT id from tipispese WHERE tipo='."'".$tipo."'";
            $rs=$this->db->query($sql);
            return $rs->row()->id; 
            
        }

	public function add_row($tipo,$id_mis,$data_s,$descr,$qta,$cu)
	{
            
            $id_tipo=$this->get_id_tipo($tipo);
            //$data_spe=date_format(date_create($data_s),'Y/m/d');
            $ins_row ='INSERT INTO spese(id_tipo,id_trasferta,data,descrizione,qta,cu,costo,stato)';
            $ins_row = $ins_row.' VALUES ('.$id_tipo.','.$id_mis.',STR_TO_DATE('."'".$data_s."'".','."'".'%d/%m/%Y %H:%i'."'".')';
            $ins_row = $ins_row.','."'".$descr."'".','.$qta.','.$cu;
            $ins_row = $ins_row.','.$qta*$cu.',0)';
            $this->db->query($ins_row);
            return TRUE;
	}




        public function mod_row($id_s,$id_mis,$tipo,$data_s,$qta,$des,$cu)
	{
            $id_tipo=$this->get_id_tipo($tipo);
            $upd_row ='UPDATE spese SET id_tipo='.$id_tipo.',id_trasferta='.$id_mis.',descrizione='."'$des'";
            $upd_row =$upd_row.',data=STR_TO_DATE('."'".$data_s."'".','."'".'%d/%m/%Y %H:%i'."'".'),'.'qta='.$qta.',cu='.$cu;
            $upd_row =$upd_row.',costo='.$qta*$cu;
            //$id_tipo=get_id_tipo($tipo);
            $upd_row =$upd_row.' WHERE id='.$id_s;
            $this->db->query($upd_row);
            
		return TRUE;
	}
}
?>