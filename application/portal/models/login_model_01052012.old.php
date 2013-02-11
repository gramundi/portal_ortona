<?php

/*
 *  Modulo Gestione Albo Pretorio
 */

class login_model extends CI_Model {
    function login_model() {
        parent::__construct();
    }
    
    function check_user($user) {

        //echo 'check user';
        $sql='select username from utenti WHERE USERNAME='."'".$user."'";
		$sql=$sql.' and STATO='."'".'A'."'";
        $q=$this->db->query($sql);
        //echo 'numero utenti='.$q->num_rows();
        if ($q->num_rows() >0) {
            return true;
        }
        return false;
    }

    //Conta il numero di righe presenti in tabella
    // Applicando il filtro per default se nullo conta il numero di righe
function Count_All($table,$filter=0){
    $sql='SELECT * from utenti where nome like '."'%".$filter."%'";
    //echo $sql;
    $rs=$this->db->query($sql);
    //echo 'numero utenti:'.$rs->num_rows();
    return $rs->num_rows();
 }


  function check_pass($user,$pass) {

        
        $sql='select password from utenti';
        $this->db->select('password');
        $this->db->where('username',$user);
        $q=$this->db->get('utenti');
        //echo $q->num_rows();
        if ($q->num_rows() == 1) {
                $row = $q->row();
                //echo $row->password;
                $pwdcry=$row->password;
                //echo $row->password;
                //echo '-------------------------------------------------';
                $pwd=$this->encrypt->decode($pwdcry);
                //echo '-------------------------------------------------';
                
                //echo 'encode'.$this->encrypt->encode('admin');
                //echo 'user='.$user.'password='.$pwd.'PASS='.$pass;
                if ($pwd==$pass)  return true;
        }
        return false;
}

    function get_id($user) {

        $this->db->select('id');
        $this->db->where('username',$user);
        $q=$this->db->get('utenti');
        $row=$q->row();
        return $row->id;

    }


    function get_ruolo($user){
        $this->db->select('ruolo');
        $this->db->where('username',$user);
        $q=$this->db->get('utenti');
        $row=$q->row();
        return $row->ruolo;

    }

    function get_user($user){

        $this->db->select('nome','cognome');
        $this->db->where('username',$user);
        $q=$this->db->get('utenti');
        $row=$q->row();
        $nome=$row->nome;
        //$cognome=$row->cognome;
        return $nome;

        }



    //Traccia il tempo di Login e Logout
    function log_user($op,$session_id){


        $id_user= $this->session->userdata('id_user');
        
        if($op=='login') {
            $q='INSERT INTO LOG(ID_UTENTE,DATAINI,DATAFIN,SESSION_ID)';
            $q=$q.' VALUES ('.$id_user.', NOW(),STR_TO_DATE('."'".'01,01,1900'."'".','."'".'%d,%m,%Y'."'".'),';
            $q=$q."'".$session_id."'".')';

            $rs=$this->db->query($q);

        }

         else //logout
         {

             $this->db->select('id');
             $this->db->where('session_id',$session_id);
             $q=$this->db->get('log');
             $row=$q->row();
             $id=$row->id;
             $q='UPDATE  LOG SET DATAFIN=NOW(),TEMPOCONN=(hour(datafin)*3600 + minute(datafin)*60+ second(datafin))-';
             $q=$q.'(hour(dataini)*3600 + minute(dataini)*60+ second(dataini))'.'WHERE ID='.$id;
             //echo $q;
             $rs=$this->db->query($q);

         }

   }


   //Dovrà essere evoluta a livello applicativo quando si gestiranno un unico model per portale accessibile a
   //tutti i controller di tutte le applicazioni
   function registra_filtro($filter,$maschera){

        $q='update filtri set filtro='."'".$filter."'".',stato='."'".'A'."'";
        $q=$q.' where maschera='."'".$maschera."'";
        $rs=$this->db->query($q);


   }

   //Dovrà essere evoluta a livello applicativo quando si gestiranno un unico model per portale accessibile a
   //tutti i controller di tutte le applicazioni
   function leggi_filtro($maschera){

        $q='select filtro from filtri where maschera='."'".$maschera."'";
        $q=$q.' and stato='."'".'A'."'";
        $rs=$this->db->query($q);
        if ($rs->num_rows() >0) {
            return $rs->row()->filtro;
        }


   }











     function insert_user($rec){

        $nome=$rec['nome'];
        $cognome=$rec['cognome'];
        $ruolo=$rec['ruolo'];
        $username=$rec['username'];
        $password=$this->encrypt->encode($rec['password']);

        $q='INSERT INTO UTENTI(username, password, nome, cognome, ruolo, id_qua, id_cap,stato) ';
        $q=$q.'VALUES ('."'".$username."'".','."'".$password."'".','."'".$nome."'".','."'".$cognome."'";
        $q=$q.','."'".$ruolo."'".','.'1,1,'."'".'A'."'".')';
        

        $rs=$this->db->query($q);

        //ID Ultimo Utente Aggiunto
        $q='SELECT max(id) as max from UTENTI';
        $rs=$this->db->query($q);
        $id=$rs->row()->max;

        //Scrivo il Diritto di accesso
        $q='INSERT INTO diritti(id_user, diritto, Note) VALUES ('.$id;
        $q=$q.','."'".'diritto utente'."'".','."'".'Diritti dell utente'."'".')';
        $rs=$this->db->query($q);

        //Recupero id diritto aggiunto per l'utente;
        $q='SELECT max(id) as max from DIRITTI';
        $rs=$this->db->query($q);
        $id=$rs->row()->max;

        //Accesso Standard sul Portal
        $q='INSERT INTO diritti_ruoliapplicativi (id_diritto, id_ruoloapp) VALUES('.$id;
                        $q=$q.',8)';
        $rs=$this->db->query($q);

        //Accesso Standard sulle Missioni
        $q='INSERT INTO diritti_ruoliapplicativi (id_diritto, id_ruoloapp) VALUES('.$id;
        $q=$q.',7)';
        $rs=$this->db->query($q);



        //Specializzo L'acceso per ruolo
        //Valido ad Oggi solo sul Repertorio

        
        switch ($ruolo) {
            case 'admin':
                        $q='INSERT INTO diritti_ruoliapplicativi (id_diritto, id_ruoloapp) VALUES('.$id;
                        $q=$q.',1)';
                        $rs=$this->db->query($q);
                        break;
            case 'resppub':
                         $q='INSERT INTO diritti_ruoliapplicativi (id_diritto, id_ruoloapp) VALUES('.$id;
                         $q=$q.',2)';
                         $rs=$this->db->query($q);
                         break;
            case 'publisher':
                         $q='INSERT INTO diritti_ruoliapplicativi (id_diritto, id_ruoloapp) VALUES('.$id;
                         $q=$q.',4)';
                         $rs=$this->db->query($q);
                         break;
            case 'normal':$q='INSERT INTO diritti_ruoliapplicativi (id_diritto, id_ruoloapp) VALUES('.$id;
                        $q=$q.',3)';
                        $rs=$this->db->query($q);
                        break;
            default:break;
        }

   }

   //Modifica Dati utente
    function update_user($rec){

        $id=$rec['id'];
        $nome=$rec['nome'];
        $cognome=$rec['cognome'];
        $ruolo=$rec['ruolo'];
        $username=$rec['username'];
        $password=$this->encrypt->encode($rec['password']);
        $q=' UPDATE UTENTI SET  username = '."'".$username."'".',password = '."'".$password."'".',nome = '."'".$nome."'";
        $q=$q.', cognome = '."'".$cognome."'".', ruolo = '."'".$ruolo."'";
        $q=$q.' WHERE id='.$id;
        //echo $q;
        $rs=$this->db->query($q);
   }

   //Cancellazione Logica degli Utenti
   function delete_user($id){

        $q=' UPDATE UTENTI SET STATO='."'".'C'."'".'WHERE id='.$id;
        $rs=$this->db->query($q);
   }







//Prende la lista degli utenti
function get_Utenti($filter,$num,$offset=5){
       //log_message('debug', 'get_Mission');
       $data=array();
       $sql='SELECT * FROM utenti WHERE stato='."'".'A'."'";
       $sql=$sql.' AND NOME like '."'%".$filter."%'".'  LIMIT '.$num.' OFFSET '.$offset;
       //echo $sql;
       $rs=$this->db->query($sql);
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

function log_utenti($username){

    $sql='SELECT NOME,COGNOME,DATAINI,DATAFIN,SECONDI FROM VACCESSI WHERE USERNAME LIKE '."'%".$username."%'";
    $sql=$sql.'ORDER BY datafin DESC';
    //echo $sql;
    $rs=$this->db->query($sql);
    $data['records']=$rs->result_array();
    $data['num_rows']=$rs->num_rows();

    return $data;


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

function get_privilegi($id_user,$app){



     //echo $app.'user:'.$id_user;
     $this->db->select('ruolo');
     $this->db->where('id_user',$id_user );
     $this->db->where('applicazione',$app);
     $q=$this->db->get('privilegi');
     $rs=$q->row();
     if ($q->num_rows() > 0) return $rs->ruolo;
     else
         echo 'no privileges';
  }

  }