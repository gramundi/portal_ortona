<?php

/*
 * Gestione Missioni Modulo Gestione Missione
 */

class ordinanze_model extends CI_Model {
    function ordinanze_model() {
        parent::__construct();
        
    }
    

 
  function get_infouser($user){

        $sql='SELECT nome,cognome from utenti WHERE USERNAME = '."'".$user."'";

        //echo $sql;

        $rs=$this->db->query($sql);

        if  ($rs->num_rows()>0){

            //echo $rs->num_rows();
            $data['nome']=$rs->row()->nome;
            $data['cognome']=$rs->row()->cognome;
            return $data;

        }
    }


 function get_tipi(){

    $sql='SELECT descrizione FROM tipi_ordinanze';
    $rs=$this->db->query($sql);
    //log_message('debug', $rs->num_rows());
    if  ($rs->num_rows()>0){
            $data=$rs->result_array();
            $rs->free_result();
            return $data;
    }
   $rs->free_result();

 }



 function get_ordinanti(){

    $sql='SELECT cognome FROM ordinanti where stato='."'".'A'."'";
    $rs=$this->db->query($sql);
    //log_message('debug', $rs->num_rows());
    if  ($rs->num_rows()>0){
            $data=$rs->result_array();
            $rs->free_result();
            return $data;
    }
   $rs->free_result();

 }


 function get_oggetto($rif){

    $sql='SELECT oggetto FROM registro WHERE codice='."'".$rif."'";
    $rs=$this->db->query($sql);
    //log_message('debug', $rs->num_rows());
    if  ($rs->num_rows()>0){
            $data['num_rows']=$rs->num_rows();
            $data['records']=$rs->result_array();
            $rs->free_result();
            return $data;
    }
   $rs->free_result();

 }



 function getdata_ordinanze($id,$filter,$off=0,$lim=6) {

  
  $data=array();
  $sql='SELECT id,codice,rif,ordinante,gestore,tipo,oggetto,descrizione,gestore,file,stato';
  $sql=$sql.' FROM vordinanze WHERE 1=1 ';
 
  $filtri=explode('-',$filter);

  //echo $filtri[0].'-'.$filtri[1].'-'.$filtri[2].'-'.$filtri[3];
  if ($filtri[0]!='NUL') $sql=$sql.' AND tipo='."'".$filtri[0]."'";
  if ($filtri[1]!='NUL') $sql=$sql.' AND ordinante='."'".$filtri[1]."'";
  if ($filtri[2]!='NUL') $sql=$sql.' AND gestore LIKE '."'%".$filtri[2]."%'";
  if ($filtri[3]!='NUL') $sql=$sql.' AND oggetto LIKE '."'%".$filtri[3]."%'";
  //if ($filtri[4]!='NUL') $sql=$sql.' AND rif LIKE '."'%".$filtri[4]."%'";
  
  
  if ($id!=0) $sql=$sql.' AND ID='.$id;
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
  
   // Nessun record 
   $rs->free_result();
   return false;

}

//Conta il numero di righe presenti nel registro che soddisfano il filtro impostato
function Count_All($table,$filter){

  $sql='SELECT * FROM '.$table ;
  $filtri=explode('-',$filter);

  $rs=$this->db->query($sql);
   //echo 'num_righe='.$rs->num_rows();
   return $rs->num_rows();
 }



function get_id_ordinante($filtro){

    $sql='SELECT id from ordinanti WHERE cognome='."'".$filtro."'";
    $rs=$this->db->query($sql);
    return $rs->row()->id;

}


function get_id_tipo($ordinante){

    $sql='SELECT id_tipo_ord from ordinanti WHERE id='."'".$ordinante."'";
    //echo $sql;
    $rs=$this->db->query($sql);
    return $rs->row()->id_tipo_ord;

}


function dml_ordinanze($op,$rec){


 if ($op=='insert')   {

        $utente=$rec['id_utente'];
        $id_ordinante=$this->get_id_ordinante($rec['ordinante']);
        $id_tipo=$this->get_id_tipo($id_ordinante);
        $rif=$rec['rif'];
        $oggetto=mysql_real_escape_string($rec['oggetto']);
        $descrizione=mysql_real_escape_string($rec['descrizione']);

        //ANNO IN CORSO
        $anno=date('Y');
        
        //Calcolo progressivo codice
        // se non trova record per l'anno nuovo Null è convertiro in 0 e si riparte
        // da 1
        $q='SELECT max(progr) as progr FROM ordinanze WHERE anno='."'".$anno."'";
        $rs=$this->db->query($q);
        $progr=$rs->row()->progr+1;
        $codice=$progr.' '.date('Y');
        
        $sql='INSERT INTO ordinanze (id_utente,id_ordinante, id_tipo, oggetto, descrizione, datareg, datamod, anno, progr, codice, rif, stato)';
        $sql=$sql.' VALUES ('.$utente.','.$id_ordinante.','.$id_tipo.', '."'".$oggetto."'".', '."'".$descrizione."'".', CURDATE(),CURDATE()';
        $sql=$sql.', '.$anno.', '.$progr.', '."'".$codice."'".', '."'".$rif."'".', '."'".'I'."'".')';
        $rs=$this->db->query($sql);
       
    }

 if ($op=='update'){

        $utente=$rec['id_utente'];
        $id_ordinante=$this->get_id_ordinante($rec['ordinante']);
        $id_tipo=$this->get_id_tipo($id_ordinante);
        $rif=$rec['rif'];
        $oggetto=mysql_real_escape_string($rec['oggetto']);
        $descrizione=mysql_real_escape_string($rec['descrizione']);
        $id=$rec['id'];

        $sql='UPDATE ordinanze SET id_utente='.$utente.',id_ordinante='.$id_ordinante.',id_tipo='.$id_tipo;
        $sql=$sql.',oggetto='."'".$oggetto."'".',descrizione='."'".$descrizione."'";
        $sql=$sql.',datamod=CURDATE(),rif='."'".$rif."'".' WHERE id='.$id;
        $rs=$this->db->query($sql);


    }

    
 if ($op=='bonifica'){


        $utente=$rec['id_utente'];
        $id_ordinante=$this->get_id_ordinante($rec['ordinante']);
        $id_tipo=$this->get_id_tipo($id_ordinante);
        $rif=$rec['rif'];
        $oggetto=mysql_real_escape_string($rec['oggetto']);
        $descrizione=mysql_real_escape_string($rec['descrizione']);
        $id=$rec['id'];
          //Da implementare

          $sql_rec='SELECT concat(concat('."'".'id_utente='."'".',id_utente,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'id_ordinante='."'".',id_ordinante,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'id_tipo='."'".',id_tipo,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'oggetto='."'".',oggetto,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'descrizione='."'".',descrizione,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'datareg='."'".',datareg,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'datamod='."'".',datamod,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'anno='."'".',anno,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'progr='."'".',progr,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'codice='."'".',codice,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'rif='."'".',rif,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'file='."'".',file,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'stato='."'".',stato,'."'".'#'."'".'))as rec_ori FROM ordinanze WHERE id='.$id;
          $rs=$this->db->query($sql_rec);
          $rec_ori=$rs->row()->rec_ori;
          //echo 'risultato='.$rec_ori;

          $sql='INSERT into log_ordinanze(data_mod,id_ute,id_recmod,rec_ori) values (CURDATE(),'.$utente;
          $sql=$sql.','.$id.','."'".$rec_ori."'".')';
          //echo 'insert_log_ordinanze='.$sql;

          $rs=$this->db->query($sql);

          //Predno id LOG per utilizzarlo dopo la modifica
           $this->db->select_max('id');
          $query = $this->db->get('log_ordinanze');
          $id_log=$query->row()->id;
          //echo $id_log;

        // Applico la Modifica sulle ordinanze
        $sql='UPDATE ordinanze SET id_utente='.$utente.',id_ordinante='.$id_ordinante.',id_tipo='.$id_tipo;
        $sql=$sql.',oggetto='."'".$oggetto."'".',descrizione='."'".$descrizione."'";
        $sql=$sql.',datamod=CURDATE(),rif='."'".$rif."'".' WHERE id='.$id;

        //echo 'UPDATE ordinanze='.$sql;
        $rs=$this->db->query($sql);

        //Registro il record modificato sul registro
          $sql_rec='SELECT concat(concat('."'".'id_utente='."'".',id_utente,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'id_ordinante='."'".',id_ordinante,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'id_tipo='."'".',id_tipo,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'oggetto='."'".',oggetto,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'descrizione='."'".',descrizione,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'datareg='."'".',datareg,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'datamod='."'".',datamod,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'anno='."'".',anno,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'progr='."'".',progr,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'codice='."'".',codice,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'rif='."'".',rif,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'file='."'".',file,'."'".'#'."'".'),';
          $sql_rec=$sql_rec.'concat('."'".'stato='."'".',stato,'."'".'#'."'".'))as rec_mod FROM ordinanze WHERE id='.$id;
          $rs=$this->db->query($sql_rec);
          $rec_mod=$rs->row()->rec_mod;


        $sql='UPDATE log_ordinanze SET rec_mod='."'".$rec_mod."'".'WHERE id='.$id_log;
        $rs=$this->db->query($sql);
        
     
 }



// Lo stato delle ordinanze può essere I-->Inserita, C-->Confermata,B-->Bonificata
// La bonifica è permessa solo agli amministratori poichè cambia le informazioni di
// un ordinanza già confermata e pubblicata.
if ($op=='conferma'){
        $id=$rec['id'];
        $sql='UPDATE ordinanze SET stato='."'".'C'."'".' WHERE id='.$id;
        $rs=$this->db->query($sql);
    }



 if ($op=='upload'){
        $nome=$rec['nomefile'];
        $id=$rec['id'];
        $sql='UPDATE ordinanze SET file='."'".$nome."'".' WHERE id='.$id;
        $rs=$this->db->query($sql);
    }

 }


  }