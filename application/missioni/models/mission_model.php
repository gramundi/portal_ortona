<?php

/*
 */

class Mission_model extends CI_Model {
    function Mission_model() {
        parent::__construct();
    }




function get_MissioniApprovate(){

    $sql='SELECT * FROM riepilogoindennita';
    log_message('debug', 'get_MissioniApprovate:'.$sql);
    $rs=$this->db->query($sql);
    if  ($rs->num_rows()>0){
            return $rs->result_array();

     }
     else return 0;

}

function get_UtentiInMissione(){
    $sql='SELECT * FROM riepilogomissdip';
    log_message('debug', 'get_UtentiInMissione:'.$sql);
    $rs=$this->db->query($sql);
        if  ($rs->num_rows()>0){
            return $rs->result_array();
            
        }
        else return 0;
}


function get_RiepilogoCapitoli(){
    $sql='SELECT * FROM riepilogocapitoli';
    log_message('debug', 'get_riepilogocapitoli:'.$sql);
    $rs=$this->db->query($sql);
        if  ($rs->num_rows()>0){
            return $rs->result_array();
            
        }
        else return 0;

}

function get_Capitoli(){

    $sql='SELECT voce FROM capitoli';
    $rs=$this->db->query($sql);
    return $rs->result_array();

}

    
function CercaUser($str){

     //echo 'CERCA USER';
     $data=array();
     $sql='SELECT id,username,nome,cognome FROM UTENTI WHERE COGNOME LIKE '."'%".$str."%'";
     //echo $sql;
     log_message('debug', 'CErcaUser:'.$sql);
     $rs=$this->db->query($sql);
     //echo $rs->num_rows();
     //echo $rs->num_rows().'---------------------'.$rs->num_rows();
        if  ($rs->num_rows()>0){
            
            $data['num_rows']=$rs->num_rows();
            $data['records']=$rs->result_array();
            //foreach ($data['records'] as $row)
              //   log_message('debug', 'CErcaUser:'.$row['id'].$row['nome'].$row['cognome']);
            return $data;
        }
    
}


function Get_Tipispese(){

    $sql='SELECT tipo from tipispese';
    //echo $sql;
    $rs=$this->db->query($sql);
    
    return $rs->result_array();
}

//Cerca la missione dell' utente $id con filtri:
    //$filter
function get_MissionUte($id_usr,$ruolo,$filter,$lim,$off){

  $data=array();
  $sql='SELECT * FROM VMISSIONI WHERE STATO!=3 ';
  $filtri=explode('-',$filter);
   //echo $filtri[0].'-'.$filtri[1].'-'.$filtri[2].'-'.$filtri[3];
  if ($filtri[0]!='NUL') $sql=$sql.' AND capitolo LIKE'."'".$filtri[0]."'";
  if ($filtri[1]!='NUL') $sql=$sql.' AND cognome LIKE'."'%".$filtri[1]."%'";
  if ($filtri[2]!='NUL') $sql=$sql.' AND citta LIKE '."'%".$filtri[2]."%'";
  if ($ruolo=='user') $sql=$sql.' AND ID_UTE='.$id_usr;
  $sql=$sql.' LIMIT '.$lim.' OFFSET '.$off;
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

   //Prende le Missioni Consolidate per le operazioni di revoca
   //Applica i filtri della Maschera di ricerca dei consolidati
   function get_Missio_Cons($anno,$mese,$utente){
   
        $data=array();
        $sql='SELECT * FROM VMISSIONI WHERE STATO=4 ';
        
        if ( $mese )$sql=$sql.' AND MESE= '."'".$mese."'";
        if ( $anno )$sql=$sql.' AND ANNO= '."'".$anno."'";
        if ( $utente)$sql=$sql.' AND COGNOME LIKE '."'".$utente."%'";
        $sql=$sql.' ORDER BY capitolo';
        echo $sql;
        $rs=$this->db->query($sql);
        log_message('debug', $rs->num_rows());
        if  ($rs->num_rows()>0){
            
            $data=$rs->result_array();
            return $data;
        }
        else {
            //Utente non ha missioni
            return false;

        }

            $rs->free_result();
       
       
   }


    //Ottiene Il Riepilogo delle Missioni con i costi aggrgati per singolo utente
    function GetRiepilogoMiss(){

        $sql=' select id,m1_mast_cod,nome,cognome,costo  from riepilogoindennita';
        $rs=$this->db->query($sql);
        if  ($rs->num_rows()>0){

            $data=$rs->result_array();
            return $data;
        }
        else {
            //Utente non ha missioni
            return false;

        }

    }

//Esegue il consolidamento delle Missioni
function Consolida_Miss_Ute($ute){
    $sql='UPDATE missioni set data_trasf=curdate(),stato=4 WHERE id_ute='.$ute;
    $this->db->query($sql);

}




//Prende la lista degli utenti
    function get_Utenti($num,$offset){
       //log_message('debug', 'get_Mission');
       $data=array();
       //$sql='SELECT * FROM utenti order by nome,cognome';
       $rs=$this->db->get('utenti',$num,$offset);
       if  ($rs->num_rows()>0){

            $data=$rs->result_array();
            return $data;
        }
        else {
            //Utente non ha missioni
            return false;

        }

            $rs->free_result();

    }

    //Estrapola i dati di intestazione della missione $id
    function get_datamiss($id){

        $q='SELECT * FROM VMISSIONI WHERE ID='.$id;
        $rs=$this->db->query($q);
        if  ($rs->num_rows()>0){
            $data=$rs->result_array();
            return $data;
        }
        else {

            //nessuna spesa della missione $id e di tipo $tipo
            return false;

        }

            $rs->free_result();
    }


    //Estrapola i dati di intestazione della missione $id
    function get_missione($id){

        $q='SELECT id_ute,nome,cognome,oggetto,capitolo,citta,data_r,data_p FROM VMISSIONI WHERE ID='.$id;

        //log_message('debug', 'get_speseditipo_query------>'.$q);
        $rs=$this->db->query($q);
        log_message('debug', 'get_datamiss_query------>'.$q.$rs->num_rows());
        if  ($rs->num_rows()>0){
            $data=$rs->row_array();
             return $data;
        }
        else {

            //nessuna spesa della missione $id e di tipo $tipo
            return false;

        }

            $rs->free_result();
    }


    //Estrapola le spese di missione $id e di tipo: $tipo
    function get_speseditipo($id,$tipo){

        $q='SELECT * FROM SPESE_TIPI WHERE id_trasferta='.$id.' and id_area='.$tipo;

        //log_message('debug', 'get_speseditipo_query------>'.$q);
        $rs=$this->db->query($q);
        log_message('debug', 'get_speseditipo_query------>'.$q.$rs->num_rows());
        if  ($rs->num_rows()>0){
            $data=$rs->result_array();
            return $data;
        }
        else {
            //nessuna spesa della missione $id e di tipo $tipo
            return false;
        }
            $rs->free_result();
    }


    //controlla e ritorna il numero di spese  per la missione id
    function check_spesemiss($id) {
     $q='SELECT * FROM SPESE WHERE id_trasferta='.$id;

     $rs=$this->db->query($q);
     log_message('debug', 'Check_spesemiss------>'.$q.$rs->num_rows());
     return $rs->num_rows()>0;

    }

    //Prende la data di partenza e la data di arrivo della Missione $id
    function GetDateMiss($id){
        //log_message('debug', 'get_Mission');

       $q='SELECT data_p,data_r from missioni where id='.$id;
       $res=$this->db->query($q);
       $data['partenza']=$res->row()->data_p;
       $data['rientro']=$res->row()->data_r;
       return $data;
    }


    //Prima prendo id capitolo dell'utente $id
    //e poi prendo la voce dalla tabella capitoli
    function get_capitoloutente($id){
        //log_message('debug', 'get_Mission');
      
       $q='SELECT id_cap from utenti where id='.$id;
       $res=$this->db->query($q);
       $id_cap=$res->row()->id_cap;
       $q='SELECT voce from capitoli where id='.$id_cap;
       $res=$this->db->query($q);
       if  ($res->num_rows()==1){
            //log_message('debug', 'result:'.$r);
            return $res->row()->voce;
        }
        else {
            //Utente non ha missioni
            return false;
        }

            $r->free_result();
       }


     //Cancellazione logica della MIssione e delle sue spese relative
     //In futuro implementare il Log delle Operazioni
     function delete($id){
        //cancellazione della Missione stato=3-->missione cancellata
         $data = array('stato' => 3);
         $this->db->where('id', $id);
         $this->db->update('missioni', $data);

         //cancellazione delle spese relative stato=0-->spesa cancellata
         $data = array('stato' => 0);
         $this->db->where('id_trasferta', $id);
         $this->db->update('spese', $data);

         
    }


    function GetResiduoCap($id){

       $q='SELECT capitolo from vmissioni where id='.$id;
       $res=$this->db->query($q);
       if  ($res->num_rows()==1){
            //log_message('debug', 'result:'.$r);
           $cap=$res->row()->capitolo;
           $q='SELECT residuo from capitoli where voce='."'".$cap."'";
           $res=$this->db->query($q);
           return $res->row()->residuo;
        }

    }


    function GetCostoMiss($id){

        $this->db->select_sum('costo');
        $this->db->where('id_trasferta', $id);
        $query = $this->db->get('spese');
        //echo $query->row()->costo;
        return $query->row()->costo;
    }


    //Cambia Lo stato di una missione mettendolo a $st
    // 1 Stato-->Missione Creata Quando si disapprova la missione torna nello stato creata
    // 2 Stato-->Missione Approvata
    // 3 Stato-->Missione Cancellata
    // 4 Stato-->Missione Consolidata
    function update_stato($id,$st,$co=0){

        if (($st==2)||($st==1)){
            //update capitoli set residuo=residuo-costo,liquidato=liquidato+costo where voce=capitolo della missione;
            $q='SELECT capitolo from vmissioni where id='.$id;
            $res=$this->db->query($q);
            $cap=$res->row()->capitolo;
            if  ($res->num_rows()==1){
                $q='UPDATE capitoli set residuo=';
                //st=1 Missione dissaprovata
                ($st==1)?$q=$q.'residuo+'.$co.',liquidato=liquidato-'.$co :$q=$q.'residuo-'.$co.',liquidato=liquidato+'.$co;
                $q=$q.' WHERE voce='."'".$cap."'";
                $this->db->query($q);
            }
        }
        if ($st==1) $co=0;
        $data = array('stato'=>$st,'costo'=>$co);
        $this->db->where('id', $id);
        $this->db->update('missioni', $data);
    }

    //Aggiorna missione o aggiunge una nuova missione ($id=0)
    function insert_update($id,$rec){

        if ($id != 0) { //Modifica Missione

        $ogg=$rec['oggetto'];
        $loc=$rec['localita'];
        $capitolo=$rec['capitolo'];
        $da='STR_TO_DATE('."'".$rec['data_p']."'".','."'".'%d/%m/%Y %H:%i'."'".')';
        $a='STR_TO_DATE('."'".$rec['data_r']."'".','."'".'%d/%m/%Y %H:%i'."'".')';
        $sql='UPDATE missioni set oggetto='."'".$ogg."'".',citta='."'".$loc."'".',data_p='.$da;
        $sql=$sql.',data_r='.$a.',ore_miss=HOUR(TIMEDIFF(data_r,data_p)) WHERE ID='.$id;

        $this->db->query($sql);

        }
        else {
        
        $mese = date("m");
        $anno = date("Y");
        

        $ute=$rec['id_ute'];
        $ogg=$rec['oggetto'];
        $loc=$rec['localita'];
        $capitolo=$rec['capitolo'];
        $da=$rec['data_p'];
        $a=$rec['data_r'];

        $q='INSERT INTO MISSIONI(ID_UTE,ANNO,MESE,OGGETTO,CAPITOLO,CITTA,DATA_INS,DATA_P,DATA_R,STATO,COSTO)';
        $q=$q.'VALUES ('.$ute.','."'".$anno."'".','."'".$mese."'".','."'".$ogg."'".','."'".$capitolo."'".','."'".$loc."'";
        $q=$q.',curdate(),STR_TO_DATE('."'".$da."'".','."'".'%d/%m/%Y %H:%i'."'".')';
        $q=$q.',STR_TO_DATE('."'".$a."'".','."'".'%d/%m/%Y %H:%i'."'".')'.',1,0)';
        //log_message('debug', 'insert_mission------>'.$q);
        $rs=$this->db->query($q);

        $this->db->select_max('id');
        $query = $this->db->get('MISSIONI');
        $maxid=$query->row()->id;
        $sql='update missioni set ore_miss=HOUR(TIMEDIFF(data_r,data_p)) where id='.$maxid;
        $rs=$this->db->query($sql);

       }

        


    }



    
}