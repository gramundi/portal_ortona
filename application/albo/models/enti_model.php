<?php

/*
 *  Modulo Gestione Albo Pretorio
 */

class enti_model extends CI_Model {
    function login_model() {
        parent::__construct();
    }
    
    

function CercaEnte($str){

     //echo 'CERCA ENTE';
     $data=array();
     $sql='SELECT id,tipo,nome FROM enti WHERE NOME LIKE '."'%".$str."%'";
     //log_message('debug', 'CErcaUser:'.$sql);
     $rs=$this->db->query($sql);
      if  ($rs->num_rows()>0){
            $data['num_rows']=$rs->num_rows();
            $data['records']=$rs->result_array();
            //foreach ($data['records'] as $row)
              //   log_message('debug', 'CErcaUser:'.$row['id'].$row['nome'].$row['cognome']);
            return $data;
        }
}

//Ritorna ilnome dell'ENTE a partire dal suo ID
function Get_Ente($id){

     //echo 'CERCA ENTE';

     $sql='SELECT nome FROM enti WHERE id='.$id;
     $rs=$this->db->query($sql);
     if  ($rs->num_rows()>0){
            return $rs->row()->nome;
        }
     return false;
}


//Ritorna La lista degli enti
function Get_Enti($ente){

     //echo 'CERCA ENTE';

     $sql='SELECT * FROM enti WHERE STATO!='."'".'C'."'";
     $sql=$sql.'AND NOME LIKE '."'%".$ente."%'";
     $rs=$this->db->query($sql);
     if  ($rs->num_rows()>0){
            return $rs->result_array();
        }
     return false;
}


     function insert_ente($rec){

        $tipo=$rec['tipo'];
        $nome=$rec['nome'];
        $indir=$rec['indir'];
        $tel=$rec['tel'];
        $piva=$rec['piva'];
        $cf=$rec['cf'];

        $q='INSERT INTO enti(tipo,nome,indir,tel,piva,cf,stato) ';
        $q=$q.'VALUES ('."'".$tipo."'".','."'".$nome."'".','."'".$indir."'".','."'".$tel."'".','."'".$piva."'";
        $q=$q.','."'".$cf."'".','."'".'A'."'".')';
        //echo $q;
        $rs=$this->db->query($q);

   }

   //Modifica Dati Ente
    function update_ente($rec){

        $id=$rec['id'];
        $tipo=$rec['tipo'];
        $nome=$rec['nome'];
        $indir=$rec['indir'];
        $tel=$rec['tel'];
        $piva=$rec['piva'];
        $cf=$rec['cf'];
        
        $q=' UPDATE enti SET  tipo = '."'".$tipo."'".',nome = '."'".$nome."'".',indir = '."'".$indir."'";
        $q=$q.', tel = '."'".$tel."'".', piva = '."'".$piva."'".', cf = '."'".$cf."'";
        $q=$q.' WHERE id='.$id;
        //echo $q;
        $rs=$this->db->query($q);
   }


   //Cancellazione Logica Ente C=Cancellato
   function delete_ente($id){

        
        $q=' UPDATE enti SET STATO='."'".'C'."'".'WHERE id='.$id;

        $rs=$this->db->query($q);

   }



function getid_ente($ente){

    $this->db->select('id');
    $this->db->where('nome', $ente);
    $query = $this->db->get('enti');
    return $query->row()->id;

}



  }