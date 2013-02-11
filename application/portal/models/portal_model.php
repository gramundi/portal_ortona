<?php

/*
 *  Modulo Gestione Albo Pretorio
 */

class portal_model extends CI_Model {
    function portal_model() {
        parent::__construct();
    }
    


    
 function getdata_rubrica($filter,$off=0,$lim=6) {


  $data=array();
  $sql='SELECT * FROM VRUBRICA WHERE 1=1 ';

  $filtri=explode('-',$filter);

  //echo $filtri[0].'-'.$filtri[1].'-'.$filtri[2].'-'.$filtri[3];
  if ($filtri[0]!='NUL') $sql=$sql.' AND cognome like'."'%".$filtri[0]."%'";
  if ($filtri[1]!='Tutti') $sql=$sql.' AND settore='."'".$filtri[1]."'";

  $sql=$sql.' LIMIT '.$lim.' OFFSET '.$off;

  //echo 'FILTRI='.$filtri[0].$filtri[1].$filtri[2];
  //echo '#######SQL=:'.$sql;
  $rs=$this->db->query($sql);
  //log_message('debug', $rs->num_rows());
  if  ($rs->num_rows()>0){

            $data=$rs->result_array();
            $rs->free_result();
            return $data;
        }
   else {
   // Nessun record
   
   return false;
   }
   $rs->free_result();

}

function getdata_messaggi($filter,$off=0,$lim=6) {


  $data=array();
  $sql='SELECT * FROM VMESSAGGI WHERE 1=1 ';

  $filtri=explode('-',$filter);

  //echo $filtri[0].'-'.$filtri[1].'-'.$filtri[2].'-'.$filtri[3];
  if ($filtri[0]!='NUL') $sql=$sql.' AND mittente like'."'%".$filtri[0]."%'";
  if ($filtri[1]!='NUL') $sql=$sql.' AND oggetto  like'."'%".$filtri[1]."%'";
  //Get only mine msg
  $sql=$sql.' AND stato!='."'".'D'."'";
  $dest=$this->session->userdata('id_user');
  $sql=$sql.' AND id_dest='.$dest;
  $sql=$sql.' LIMIT '.$lim.' OFFSET '.$off;

  //echo 'FILTRI='.$filtri[0].$filtri[1].$filtri[2];
  //echo '#######SQL=:'.$sql;
  $rs=$this->db->query($sql);
  //log_message('debug', $rs->num_rows());
  if  ($rs->num_rows()>0){

            $data=$rs->result_array();
            $rs->free_result();
            return $data;
        }
   else {
   // Nessun record

   return false;
   }
   $rs->free_result();

}



  //Controlla se sono arrivati nuovi messaggi
  function check_newmessages($id_user){

     $sql='SELECT count(*) as nrmsg  FROM messaggi WHERE id_dest='.$id_user;
     $sql=$sql.' AND stato='."'".'C'."'";
     $rs=$this->db->query($sql);
     return $rs->row()->nrmsg;
     
  }


  //Inserisce un nuovo messaggio nel database
  function record_msg($id_dest,$oggetto,$testo,$rif){

     $id_mitt=$this->session->userdata('id_user');

     echo('id_dest:'.$id_dest.'oggetto:'.$oggetto.'testo:'.$testo.'rif:'.$rif);
     $sql='INSERT INTO MESSAGGI(id_rif,data_crea,id_mitt, id_dest, oggetto, testo) VALUES ('.$rif.',NOW(),'.$id_mitt;
     $sql=$sql.','.$id_dest.','."'".$oggetto."'".','."'".$testo."'".')';
     echo $sql;
     $rs=$this->db->query($sql);

  }

  //Inserisce un nuovo messaggio nel database
  function change_stato($id,$stato){

     $sql='UPDATE MESSAGGI SET STATO='."'".$stato."'".' WHERE ID='.$id;
     //echo $sql;
     $rs=$this->db->query($sql);


  }

  //Get Nr. Post it utente 
  function num_postit($id_usr){

     $sql='SELECT count(*) as nrnotes from NOTES WHERE id_user='.$id_usr;
     $rs=$this->db->query($sql);
     return $rs->row()->nrnotes;

  }

  //Get Max Rif
  function rif_max($id_usr){
     $sql='SELECT ifnull(max(rif),0) as maxrif from NOTES WHERE id_user='.$id_usr;
     $rs=$this->db->query($sql);
     return $rs->row()->maxrif;

  }

  //Get Post it utente
  function get_postit($id_usr){

     $sql='SELECT * from NOTES WHERE id_user='.$id_usr;
     $rs=$this->db->query($sql);
     if  ($rs->num_rows()>0){

            $data=$rs->result_array();
            $rs->free_result();
            return $data;
        }
     
     $rs->free_result();

  }

//Gestisce le note nel DB
  function gest_note($op,$rif,$testo=0){

     $id_usr=$this->session->userdata('id_user');
     if($op=='del'){
        $sql='DELETE from NOTES WHERE rif='.$rif.' AND id_user='.$id_usr;
        //echo $sql;
        $rs=$this->db->query($sql);
     }
     else {
        $sql='SELECT * from NOTES WHERE id_user='.$id_usr.' AND rif='.$rif;
        $rs=$this->db->query($sql);
        if  ($rs->num_rows()>0){
            $sql='UPDATE NOTES SET testo='."'".$testo."'".' WHERE id_user='.$id_usr.' AND rif='.$rif;
            //echo $sql;
            $rs=$this->db->query($sql);
        }
        else {
            $sql='INSERT INTO NOTES(id_user, dt, rif,testo) VALUES ('.$id_usr.',NOW(),'.$rif.','."'".$testo."'".')';
            //echo $sql;
            $rs=$this->db->query($sql);


        }

        }
     


  }


  }