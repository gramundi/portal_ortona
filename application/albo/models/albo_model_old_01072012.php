<?php

/*
 * Gestione Missioni Modulo Gestione Missione
 */

class albo_model extends CI_Model {
    function albo_model() {
        parent::__construct();
        $this->load->model('enti_model','em');
    }
    

  
  function get_infouser($user){

        $sql='SELECT nome,cognome from UTENTI WHERE USERNAME = '."'".$user."'";

        //echo $sql;

        $rs=$this->db->query($sql);

        if  ($rs->num_rows()>0){

            //echo $rs->num_rows();
            $data['nome']=$rs->row()->nome;
            $data['cognome']=$rs->row()->cognome;
            return $data;

        }
    }





 // recupera i dati storici del registro applicando i filtri
 // $ente,$ute
 // STATO 'I','P','A','C' INSERITA,PUBBLICATA,ANNULATA,CERTIFICATA


 //Recupera dati per la stampa del registro
 // Certifica gli atti pubblicati stato='P' fino al ad una data impostata
 
 function getdati_stampa($dal,$al) {


  $data=array();
  
 
  $sql='SELECT id,codice,ente,tipo,oggetto,descrizione,id_utente,responsabile  ,dal,al,periodo,stato';
  $sql=$sql.' FROM VREGISTRO WHERE (STATO ='."'".'P'."'".' OR STATO ='."'".'C'."'";
  $sql=$sql.') AND DATE(datareg) >= STR_TO_DATE('."'".$dal."'".','."'".'%d/%m/%Y'."'".')';
  $sql=$sql.'AND DATE(datareg) <= STR_TO_DATE('."'".$al."'".','."'".'%d/%m/%Y'."'".')';
  $sql=$sql.'AND STR_TO_DATE(al,'."'".'%d-%m-%Y'."'".') < CURDATE() ORDER BY ANNO,PROGR,CODICE,DAL';

  //echo $sql;
  $rs=$this->db->query($sql);

  if  ($rs->num_rows()>0){

            $data['records']=$rs->result_array();
            $data['tot']=$rs->num_rows();
            $data['numfields']=$rs->num_fields();
            $data['fields'] = $rs->field_data();
            $rs->free_result();
            return $data;
  }
  $data['tot']=0;
  $rs->free_result();
  return $data;

 }


 function get_tipiatti(){

    $sql='SELECT descrizione FROM TIPI_ATTI';
    $rs=$this->db->query($sql);
    //log_message('debug', $rs->num_rows());
    if  ($rs->num_rows()>0){
            $data=$rs->result_array();
            $rs->free_result();
            return $data;
    }
   $rs->free_result();

 }

 function get_giornale(){
     
    $data=array(); 
    $sql='SELECT * FROM LOG_REGISTRO'; 
    $rs=$this->db->query($sql);
    //log_message('debug', $rs->num_rows());
    if  ($rs->num_rows()>0){

            $data=$rs->result_array();
            $rs->free_result();
            return $data;
    }
   $rs->free_result();
     
 }

function getlast_registro(){

    $data=array();
    $this->db->select_max('id', 'id');
    $row=$this->db->get('vregistro');
    $rec=$this->db->get_where('vregistro', array('id' => $row->row()->id));
    $data=$rec->result_array();
    $rec->free_result();
    return $data;
    
}

 function getdata_registro($id,$filter,$off=0,$lim=6) {

  $data=array();
  $sql='SELECT id,codice,rif,ente,id_tipo,tipo,oggetto,descrizione,id_utente,responsabile,dal,al as al,periodo,stato';
  $sql=$sql.' FROM VREGISTRO WHERE STATO!='."'".'A'."'";

  
    
  $filtri=explode('-',$filter);

  
  if ($filtri[0]!='NUL') $sql=$sql.' AND ente LIKE '."'%".$filtri[0]."%'";
  if ($filtri[1]!='NUL') $sql=$sql.' AND oggetto LIKE '."'%".$filtri[1]."%'";
  if ($filtri[2]!='NUL') $sql=$sql.' AND responsabile LIKE '."'%".$filtri[2]."%'";
  if ($filtri[3]!='NUL') $sql=$sql.' AND rif LIKE '."'%".$filtri[3]."%'";
  if ($filtri[4]!='NUL') $sql=$sql.' AND tipo LIKE '."'%".$filtri[4]."%'";
  if ($filtri[5]!='T')   $sql=$sql.' AND stato='."'".$filtri[5]."'";
  
  if ($id!=0) $sql=$sql.'AND ID='.$id;
  $sql=$sql.' LIMIT '.$lim.' OFFSET '.$off;

  //echo 'FILTRI='.$filtri[0].$filtri[1].$filtri[2];
  //echo $sql;
  $rs=$this->db->query($sql);
  //log_message('debug', $rs->num_rows());
  if  ($rs->num_rows()>0){

            $data=$rs->result_array();
            $rs->free_result();
            return $data;
        }
  
   // Nessun record 
   $rs->free_result();
   return false;

}

 function getdati_certificazione($id) {

  $data=array();
  $sql='SELECT ente,oggetto,codice,dal,al FROM VREGISTRO WHERE id='.$id;
  //echo $sql;
  $rs=$this->db->query($sql);
  //log_message('debug', $rs->num_rows());
  if  ($rs->num_rows()>0){

            $data=$rs->row_array();
            $rs->free_result();
            return $data;
        }
   // Nessun record
   $rs->free_result();
   return false;

}



function dml_atto($op,$rec){


 if ($op=='insert')   {

        $ute=$rec['id_utente'];
        $id_tipo=$rec['id_tipo'];
        $rif=$rec['rif'];
        $ogg=mysql_real_escape_string($rec['oggetto']);
        $descrizione=mysql_real_escape_string($rec['descrizione']);
        $periodo=$rec['periodo'];
        $id_ente=$rec['id_ente'];
        
        
        //ANNO IN CORSO
        $anno=date('Y');
        
        //Calcolo progressivo codice
        // se non trova record per l'anno nuovo Null è convertiro in 0 e si riparte
        // da 1
        $q='SELECT max(progr) as progr FROM REGISTRO WHERE anno='."'".$anno."'";
        $rs=$this->db->query($q);
        $progr=$rs->row()->progr+1;
        $codice=$progr.' '.date('Y');
        $q='INSERT INTO REGISTRO(ID_ENTE,ID_UTENTE,ID_TIPO,OGGETTO,DESCRIZIONE,DATAREG,DAL,AL,PERIODO,PROGR,ANNO,CODICE,RIF,STATO)';
        $q=$q.'VALUES ('.$id_ente.','.$ute.','.$id_tipo.','."'".$ogg."'".','."'".$descrizione."'".',';
        $q=$q.'CURDATE(),'.'CURDATE(),'.'DATE_ADD(CURDATE(),INTERVAL '.$periodo.' DAY),'.$periodo;
        $q=$q.','.$progr.','.$anno.','."'".$codice."'".','."'".$rif."'".','."'".'I'."')";
        $rs=$this->db->query($q);
       
    }
  //Bonifica Modofica rilasciando i vincoli senza più applicare i vincoli temporali
  // Tale operazione è permessa solo ad utenti amministratori   
 
  if ($op=='bonifica') {
      //Registro la modifica sul giornale delle modifiche(Log Registro)
       $q='insert into log_registro(id_registro,id_ente,id_utente,id_tipo,oggetto,descrizione,datareg';
       $q=$q.',datamod,dal,al,periodo,progr,anno,codice,rif,stato)  select * from registro where id='.$rec['id'];
       $this->db->query($q);
       //Registro L'utente che ha generato la modifica
       $this->db->select_max('id');
          $query = $this->db->get('LOG_REGISTRO');
          $id=$query->row()->id;
          $id_utente=$rec['id_utente'];
          $q='UPDATE LOG_REGISTRO SET ID_UTENTE_MOD='.$id_utente.',datamod=CURDATE() WHERE ID='.$id;
          //echo $q;
          $this->db->query($q);
        

          //applico la modifica sul registro
          $id_ente=$this->em->getid_ente($rec['ente']);
          $id_tipo=$rec['id_tipo'];
          $ogg=mysql_real_escape_string($rec['oggetto']);
          $descrizione=mysql_real_escape_string($rec['descrizione']);
          $rif=mysql_real_escape_string($rec['rif']);
          $periodo=$rec['periodo'];
          $q='UPDATE REGISTRO SET ID_TIPO='.$id_tipo.',RIF='."'".$rif."'".',OGGETTO='."'".$ogg."'".',DESCRIZIONE='."'".$descrizione."'";
          $q=$q.', periodo='.$periodo.','.' datamod=CURDATE(),id_ente='.$id_ente;
          $q=$q.' WHERE ID='.$rec['id'];
          //echo $q;
          $rs=$this->db->query($q);
      
     
    }  
    
 //End Bonifica
  
  if ($op=='update') {
      //Registro la modifica sul giornale delle modifiche(Log Registro)
      // Registrazione solo per i record allo stato 'P'
       if ($rec['stato']=='P'){

          //echo 'implementare giornale modifiche';
          //registro la modifica nel giornale
          $q='insert into log_registro(id_registro,id_ente,id_utente,id_tipo,oggetto,descrizione,datareg';
          $q=$q.',datamod,dal,al,periodo,progr,anno,codice,rif,stato)  select * from registro where id='.$rec['id'];
          $this->db->query($q);


          //Registro L'utente che ha generato la modifica
          $this->db->select_max('id');
          $query = $this->db->get('LOG_REGISTRO');
          $id=$query->row()->id;
          $id_utente=$rec['id_utente'];
          $q='UPDATE LOG_REGISTRO SET ID_UTENTE_MOD='.$id_utente.',datamod=CURDATE() WHERE ID='.$id;
          //echo $q;
          $this->db->query($q);
        

          //applico la modifica sul registro
          $id_ente=$this->em->getid_ente($rec['ente']);
          $id_tipo=$rec['id_tipo'];
          $ogg=mysql_real_escape_string($rec['oggetto']);
          $descrizione=mysql_real_escape_string($rec['descrizione']);
          $rif=mysql_real_escape_string($rec['rif']);
          $periodo=$rec['periodo'];
          $q='UPDATE REGISTRO SET ID_TIPO='.$id_tipo.',RIF='."'".$rif."'".',OGGETTO='."'".$ogg."'".',DESCRIZIONE='."'".$descrizione."'";
          $q=$q.', periodo='.$periodo.', dal=CURDATE(),al=DATE_ADD(CURDATE(),INTERVAL '.$periodo.' DAY),'.' datamod=CURDATE(),';
          $q=$q.'id_ente='.$id_ente;
          $q=$q.' WHERE ID='.$rec['id'];
          //echo $q;
          $rs=$this->db->query($q);
          



      }
      $id_ente=$this->em->getid_ente($rec['ente']);
      $id_tipo=$rec['id_tipo'];
      $ogg=mysql_real_escape_string($rec['oggetto']);
      $descrizione=mysql_real_escape_string($rec['descrizione']);
      $rif=mysql_real_escape_string($rec['rif']);
      $periodo=$rec['periodo'];
      $q='UPDATE REGISTRO SET ID_TIPO='.$id_tipo.',RIF='."'".$rif."'".',OGGETTO='."'".$ogg."'".',DESCRIZIONE='."'".$descrizione."'";
      $q=$q.', periodo='.$periodo.', dal=CURDATE(),al=DATE_ADD(CURDATE(),INTERVAL '.$periodo.' DAY),'.' datamod=CURDATE(),';
      $q=$q.'id_ente='.$id_ente;
      $q=$q.' WHERE ID='.$rec['id'];
      //echo $q;
      $rs=$this->db->query($q);
      
     
    }

    if ($op=='del') {
      $q='UPDATE REGISTRO SET STATO='."'".'A'."'".' WHERE ID='.$rec['id'];
      //echo $q;
      $rs=$this->db->query($q);
      //Registro la odifica sul gironale delle modifiche(Log Registro)

  }

  if ($op=='pub') {

      
      $q='UPDATE REGISTRO SET ID_UTENTE='.$this->session->userdata('id_user').',STATO='."'".'P'."'";
      $q=$q.',dal=CURDATE(),al=DATE_ADD(CURDATE(),INTERVAL PERIODO DAY),DATAMOD=NOW()';
      $q=$q.'WHERE ID='.$rec['id'];
      //echo $q;
      $rs=$this->db->query($q);
      
      //Registro la odifica sul gironale delle modifiche(Log Registro)

  }

  if ($op=='cer') {
      $q='UPDATE REGISTRO SET STATO='."'".'C'."'".' WHERE ID='.$rec['id'];
      //echo $q;
      $rs=$this->db->query($q);
      
      //Registro la odifica sul gironale delle modifiche(Log Registro)
  }



   }


  }