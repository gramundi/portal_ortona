<?php

/*
 *  Modulo Gestione Albo Pretorio
 */

class cross_data extends CI_Model {
    function cross_data() {
        parent::__construct();
    }



   //Dovrà essere evoluta a livello applicativo quando si gestiranno un unico model per portale accessibile a
   //tutti i controller di tutte le applicazioni
   function registra_filtro($filter,$maschera,$id_usr){

        $q='update filtri set filtro='."'".$filter."'".',stato='."'".'A'."'";
        $q=$q.' where maschera='."'".$maschera."'";
        $q=$q.' AND id_user='.$id_usr;
        //echo $q;
        $rs=$this->db->query($q);

   }

   //Dovrà essere evoluta a livello applicativo quando si gestiranno un unico model per portale accessibile a
   //tutti i controller di tutte le applicazioni
   function leggi_filtro($maschera,$id_usr){

        $q='select filtro from filtri where maschera='."'".$maschera."'";
        $q=$q.' and stato='."'".'A'."'";
        $q=$q.' and id_user='.$id_usr;
        $rs=$this->db->query($q);
        if ($rs->num_rows() >0) {
            return $rs->row()->filtro;
        }
   }

   //Dovrà essere evoluta a livello applicativo quando si gestiranno un unico model per portale accessibile a
   //tutti i controller di tutte le applicazioni
   function get_mail($id_usr){

        $q='select email from an_contatti where id_utente='.$id_usr;
        $rs=$this->db->query($q);
        if ($rs->num_rows() >0) {
            return $rs->row()->email;
        }
   }

  function get_tipiatti(){

    $sql='SELECT descrizione FROM tipi_atti';
    $rs=$this->db->query($sql);
    //log_message('debug', $rs->num_rows());
    if  ($rs->num_rows()>0){
            $data=$rs->result_array();
            $rs->free_result();
            return $data;
    }
   $rs->free_result();


 }

 function get_atti($filter) {

  $data=array();
  $sql='SELECT tipo,oggetto,descrizione';
  $sql=$sql.' FROM vregistro WHERE tipo='."'".$filter."'";
  $sql=$sql.' AND ANNO=2012';
  $sql=$sql.' AND STATO='."'".'P'."'";

  //echo 'FILTRI='.$filtri[0].$filtri[1].$filtri[2];
  echo $sql;
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


function num_rows($table){

    $sql='SELECT count(*) as tot FROM '.$table ;
    $sql=$sql.' WHERE STATO!='."'".'A'."'";
    
    $rs=$this->db->query($sql);

    return $rs->row()->tot;

}

 //Conta il numero di righe presenti nel registro che soddisfano il filtro impostato
function Count_All($table,$filter){

  $sql='SELECT * FROM '.$table ;
  

  $filtri=explode('-',$filter);

  switch($table){
     case 'vregistro':
         $sql=$sql.' WHERE STATO!='."'".'A'."'";
         if ($filtri[0]!='NUL') $sql=$sql.' AND ente LIKE '."'%".$filtri[0]."%'";
         if ($filtri[1]!='NUL') $sql=$sql.' AND oggetto LIKE '."'%".$filtri[1]."%'";
         if ($filtri[2]!='NUL') $sql=$sql.' AND responsabile LIKE '."'%".$filtri[2]."%'";
         if ($filtri[3]!='NUL') $sql=$sql.' AND rif LIKE '."'%".$filtri[3]."%'";
         if ($filtri[4]!='NUL') $sql=$sql.' AND tipo LIKE '."'%".$filtri[4]."%'";
         if ($filtri[5]!='T')   $sql=$sql.' AND stato='."'".$filtri[5]."'";

         break;
     case 'vordinanze':
          //Le Ordinanze non si possono cancellare quindi non esiste lo stato A-->Annulate
          // come fatto per il repertorio.
          $sql=$sql.' WHERE 1=1' ;
          if ($filtri[0]!='NUL') $sql=$sql.' AND tipo='."'".$filtri[0]."'";
          if ($filtri[1]!='NUL') $sql=$sql.' AND ordinante='."'".$filtri[1]."'";
          if ($filtri[2]!='NUL') $sql=$sql.' AND gestore LIKE '."'%".$filtri[2]."%'";
          if ($filtri[3]!='NUL') $sql=$sql.' AND oggetto LIKE '."'%".$filtri[3]."%'";
          if ($filtri[4]!='NUL') $sql=$sql.' AND rif LIKE '."'%".$filtri[4]."%'";
          break;

     case 'vmissioni':
          //UNiformare quanto prima lo stato ad un carattere alfanumerico A annulata=3
          $sql=$sql.' WHERE STATO!=3';
          if ($filtri[0]!='NUL') $sql=$sql.' AND capitolo LIKE '."'%".$filtri[0]."%'";
          if ($filtri[1]!='NUL') $sql=$sql.' AND cognome='."'".$filtri[1]."'";
          if ($filtri[2]!='NUL') $sql=$sql.' AND citta LIKE'."'%".$filtri[2]."%'";
          break;

     case 'vrubrica':
        $sql=$sql.' WHERE 1=1' ;
        if ($filtri[0]!='NUL') $sql=$sql.' AND cognome LIKE '."'%".$filtri[0]."'";
        if ($filtri[1]!='Tutti') $sql=$sql.' AND settore='."'".$filtri[1]."'";
        break;

     case 'vmessaggi':
        $sql=$sql.' WHERE 1=1' ;
        if ($filtri[0]!='NUL') $sql=$sql.' AND mittente LIKE '."'%".$filtri[0]."'";
        if ($filtri[1]!='NUL') $sql=$sql.' AND oggetto LIKE '."'%".$filtri[1]."%'";
        $sql=$sql.' AND stato!='."'".'D'."'";
        $dest=$this->session->userdata('id_user');
        $sql=$sql.' AND id_dest='.$dest;
        break;
     case 'vcontatti':
        $sql=$sql.' WHERE STATO!='."'".'A'."'";
        if ($filtri[0]!='NUL') $sql=$sql.' AND cognome LIKE '."'%".$filtri[0]."'";
        if ($filtri[1]!='NUL') $sql=$sql.' AND ragsoc LIKE '."'%".$filtri[1]."%'";

        break;
     case 'vregistropub':
        $sql=$sql.' WHERE 1=1' ;
        if ($filtri[0]!='NUL') $sql=$sql.' AND tipo LIKE '."'%".$filtri[0]."%'";
        if ($filtri[1]!='NUL') $sql=$sql.' AND oggetto LIKE '."'%".$filtri[1]."%'";
        break;


 }
    
    $rs=$this->db->query($sql);
    
   //echo 'num_righe='.$rs->num_rows();
   return $rs->num_rows();
 }


//Gestione Privilegi Ricava il privilegio sulle applicazione per
//per l'utente loggato

 function get_privilegi($id_user,$app){


     $this->db->select('ruolo');
     $this->db->where('id_user',$id_user );
     $this->db->where('applicazione',$app);
     $q=$this->db->get('privilegi');
     $rs=$q->row();
     if ($q->num_rows() > 0) return $rs->ruolo;
     else
         return 'no privileges';
  }

  function get_nome($id_user) {

        $sql='select concat(nome,cognome) as utente from utenti where id='.$id_user;
        $rs=$this->db->query($sql);
        return $rs->row()->utente;

    }

  
 function getregistropub($id,$filter,$off=0,$lim=6) {

  $data=array();
  $sql='SELECT id,richiedente,tipo,dal,al,oggetto';
  $sql=$sql.' FROM vregistropub';
  $sql=$sql.' WHERE 1=1' ;
   
  $filtri=explode('-',$filter);

  if ($filtri[0]!='NUL') $sql=$sql.'   AND tipo LIKE '."'%".$filtri[0]."%'";
  if ($filtri[1]!='NUL') $sql=$sql.'   AND oggetto LIKE '."'%".$filtri[1]."%'";
  //if ($filtri[2]!='NUL') $sql=$sql.' AND responsabile LIKE '."'%".$filtri[2]."%'";
  
  if ($id!=0) $sql=$sql.'AND ID='.$id;
  $sql=$sql.' LIMIT '.$lim.' OFFSET '.$off;

  $rs=$this->db->query($sql);
  if  ($rs->num_rows()>0){

            $data=$rs->result_array();
            $rs->free_result();
            return $data;
        }
  
   // Nessun record 
   $rs->free_result();
   return false;

}

function get_infoatto($id){
    
    $this->db->select('file');
     $this->db->where('id',$id );
     $q=$this->db->get('registro');
     $rs=$q->row();
     if ($q->num_rows() > 0) return $rs->file;
     
}

}