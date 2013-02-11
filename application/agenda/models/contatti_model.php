<?php
class contatti_model extends CI_Model {
	

	function contatti_model () {
		parent::__construct();
		
    	
	}

        //Gestione Operazioni DML su tabella AN_CONTATTI gestione contatti per
        //Appuntamenti Amministrazione
	function dml_contatti($op,$rec) {

          
            if ($op=='add'){
               $sql= 'INSERT INTO AN_CONTATTI(tipo, nome, cognome, ragsoc, email,emailsec, cell1,cell2,tel,sito,note)';
               $sql.='VALUES ('."'".'EST'."'".','."'".$rec['nome']."'".','."'".$rec['cognome']."'";
               $sql.=', '."'".$rec['ragsoc']."'".', '."'".$rec['email']."'".', '."'".$rec['emailsec']."'".', '."'".$rec['cell1']."'";
               $sql.=', '."'".$rec['cell2']."'".', '."'".$rec['telef']."'".', '."'".$rec['sito']."'";
               $sql.=', '."'".$rec['note']."'".')';

               //echo $sql;
               $this->db->query($sql);

               $rs=$this->db->select_max('id','maxid')->get('an_contatti');
               return $rs->row()->maxid;


            }
            //Modifica appuntamento
            if ($op=='mod'){
               
                $sql ='UPDATE AN_CONTATTI SET NOME='."'".$rec['nome']."'".',COGNOME='."'".$rec['cognome']."'";
                $sql.=',RAGSOC='."'".$rec['ragsoc']."'".',EMAIL='."'".$rec['email']."'".',EMAILSEC='."'".$rec['emailsec']."'".',CELL1='."'".$rec['cell1']."'";
                $sql.=',CELL2='."'".$rec['cell2']."'".',SITO='."'".$rec['sito']."'".',TEL='."'".$rec['telef']."'";
                $sql.=',NOTE='."'".$rec['note']."'".' WHERE id='.$rec['id'];
                //echo $sql;
                $this->db->query($sql);
            }
            if ($op=='del'){
                $sql='UPDATE AN_CONTATTI SET STATO='."'".'A'."'".'WHERE ID='.$rec['id'];
                $this->db->query($sql);
                
            }
		
	}

        
        function getdata_contatti($filter,$off=0,$lim=6 ){

            $data = array();
            $sql = 'SELECT * from vcontatti WHERE STATO!=' . "'" . 'A' . "'";
            
            $filtri = explode('-', $filter);
            
            if ($filtri[0] != 'NUL')
                $sql = $sql . ' AND cognome LIKE ' . "'%" . $filtri[0] . "%'";
            if ($filtri[1] != 'NUL')
                $sql = $sql . ' AND ragsoc  LIKE ' . "'%" . $filtri[1] . "%'";

            $sql = $sql . ' LIMIT ' . $lim . ' OFFSET ' . $off;
          
            $rs = $this->db->query($sql);
            if ($rs->num_rows() > 0) {
                $data = $rs->result_array();
                $rs->free_result();
                return $data;
            }
            // Nessun record
            $rs->free_result();
            return false;
    }

    //Recupera i dati di un singolo contatto
    function get_contatto($id) {
        $data = array();

        $sql = 'SELECT * from VCONTATTI WHERE id='.$id;
        //echo $sql;
        $rs = $this->db->query($sql);
        //log_message('debug', $rs->num_rows());
        if ($rs->num_rows() > 0) {
            $data['records'] = $rs->result_array();
            $data['num_rows'] = $rs->num_rows;
            $rs->free_result();
            return $data;
        }
    }



}