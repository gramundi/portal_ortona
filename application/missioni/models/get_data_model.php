<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Get_data extends Model
{
	/**
	* Instanciar o CI
	*/
public function Get_data_model()
    {
        parent::Model();
		$this->CI =& get_instance();
    }


public function get_data($params = "" , $page = "all")
	{

		$this->db->select("data")->from( "table_name");

		if (!empty($params))
		{
			if ( (($params ["num_rows"] * $params ["page"]) >= 0 && $params ["num_rows"] > 0))
			{
				if  ($params ["search"] === TRUE)
				{
					$ops = array (

							"eq" => "=",
							"ne" => "<>",
							"lt" => "<",
							"le" => "<=",
							"gt" => ">",
							"ge" => ">="
					);

				}

				if ( !empty ($params['search_field_1']))
				{
					$this->db->where ($params['search_field_1'], $params['user_id']);
				}

				if ( !empty ($params['search_field_2']))
				{
					$this->db->where ($params['search_field_2'], "1");
				}

				$this->db->order_by( "{$params['sort_by']}", $params ["sort_direction"] );

				if ($page != "all")
				{
					$this->db->limit ($params ["num_rows"], $params ["num_rows"] *  ($params ["page"] - 1) );
				}

				$query = $this->db->get();

			}
		}
		else
		{
				$this->db->limit (5);
				$query = $this->db->get ($this->_table );

		}
		return $query;
	}
}
?>
