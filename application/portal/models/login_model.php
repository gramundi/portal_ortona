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
                //echo 'user='.$user.'password='.$pwd.'pass interfaccia='.$pass;

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
            //We check if the session_id is already in the log. In that case 
            //The user is trying to login with the same browser and pc is not allowed
            $q='SELECT COUNT(*) as u_att FROM log WHERE session_ID='."'".$session_id."'";
            $rs=$this->db->query($q);
            if ($rs->row()->u_att) return 'l_att';
            else{
                $q='INSERT INTO log(ID_UTENTE,DATAINI,DATAFIN,SESSION_ID)';
                $q=$q.' VALUES ('.$id_user.', NOW(),STR_TO_DATE('."'".'01,01,1900'."'".','."'".'%d,%m,%Y'."'".'),';
                $q=$q."'".$session_id."'".')';

                $rs=$this->db->query($q);
            }
        }

         else //logout
         {

             $this->db->select('id');
             $this->db->where('session_id',$session_id);
             $q=$this->db->get('log');
            
             if ($q->num_rows()>0) {
                     $id=$q->row()->id;
                     $q='UPDATE  log SET DATAFIN=NOW(),TEMPOCONN=(hour(datafin)*3600 + minute(datafin)*60+ second(datafin))-';
                     $q=$q.'(hour(dataini)*3600 + minute(dataini)*60+ second(dataini))'.'WHERE ID='.$id;
                     $rs=$this->db->query($q);
            }
         }

   }


// Stati Utente A--> Attivo,D-->Disattivo,C-->Cancellato

     function insert_user($rec){

        $nome=$rec['nome'];
        $cognome=$rec['cognome'];
        $stato=$rec['stato'];
        $username=$rec['username'];
        $password=$this->encrypt->encode($rec['password']);

        $q='INSERT INTO utenti(username, password, nome, cognome, id_qua, id_cap,stato) ';
        $q=$q.'VALUES ('."'".$username."'".','."'".$password."'".','."'".$nome."'".','."'".$cognome."'";
        $q=$q.','.'1,1,'."'".$stato."'".')';
        

        $rs=$this->db->query($q);

        //ID Ultimo Utente Aggiunto
        $q='SELECT max(id) as max from utenti';
        $rs=$this->db->query($q);
        $id=$rs->row()->max;

        //Scrivo il Diritto di accesso
        $q='INSERT INTO diritti(id_user, diritto, Note) VALUES ('.$id;
        $q=$q.','."'".'diritto utente'."'".','."'".'Diritti dell utente'."'".')';
        $rs=$this->db->query($q);

        //Recupero id diritto aggiunto per l'utente;
        $q='SELECT max(id) as max from diritti';
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

   

   }

   //Modifica Dati utente
   // Stati Utente A--> Attivo,D-->Disattivo,C-->Cancellato
    function update_user($rec){

        $id=$rec['id'];
        $nome=$rec['nome'];
        $cognome=$rec['cognome'];
        $stato=$rec['stato'];
        $username=$rec['username'];
        $password=$this->encrypt->encode($rec['password']);
        $q=' UPDATE utenti SET  username = '."'".$username."'".',password = '."'".$password."'".',nome = '."'".$nome."'";
        $q=$q.', cognome = '."'".$cognome."'".', stato = '."'".$stato."'";
        $q=$q.' WHERE id='.$id;
        //echo $q;
        $rs=$this->db->query($q);
   }

   //Cancellazione Logica degli Utenti
   // Stati Utente A--> Attivo,D-->Disattivo,C-->Cancellato
   //
   function delete_user($id){

        $q=' UPDATE utenti SET STATO='."'".'C'."'".'WHERE id='.$id;
        $rs=$this->db->query($q);
   }







//Prende la lista degli utenti
function get_Utenti($filter,$num,$offset=5){
       //log_message('debug', 'get_Mission');
       $data=array();
       $sql='SELECT * FROM utenti WHERE stato='."'".'A'."'";
       $sql=$sql.' AND COGNOME like '."'%".$filter."%'".'  LIMIT '.$num.' OFFSET '.$offset;
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

    $sql='SELECT NOME,COGNOME,DATAINI,DATAFIN,SECONDI FROM vaccessi WHERE USERNAME LIKE '."'%".$username."%'";
    $sql=$sql.'ORDER BY datafin DESC';
    //echo $sql;
    $rs=$this->db->query($sql);
    $data['records']=$rs->result_array();
    $data['num_rows']=$rs->num_rows();

    return $data;


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

//Implementa la gestione dei privilegi su un utente
  function gest_privileges($id_user,$app,$priv,$op){

      $sql = "call gestione_privilegi(?,?,?,?)";
      $execute = $this->db->query($sql, array($op,$id_user,$app,$priv));
  
  }

  //Controlla se sono arrivati nuovi messaggi
  function check_newmessages($id_user){

     $sql='SELECT count(*) as nrmsg  FROM messaggi WHERE id_dest='.$id_user;
     $sql=$sql.' AND stato='."'".'C'."'";
     $rs=$this->db->query($sql);
     return $rs->row()->nrmsg;
     
  }



 function get_ruoli_user($id_user){

     
     $sql='SELECT ruolo,applicazione  FROM privilegi WHERE id_user='.$id_user;
     $rs=$this->db->query($sql);
     if  ($rs->num_rows()>0){
            $data['num_rows']=$rs->num_rows();
            $data['records']=$rs->result_array();
            return $data;
     }
        else {
            //Utente non ha privilegi //impossible
            return false;

        }

     $rs->free_result();
  }

  function get_app($id_usr){


     $sql='SELECT applicazione FROM applicazioni WHERE applicazione';
     $sql=$sql.' NOT IN (SELECT APPLICAZIONE FROM privilegi WHERE id_user='.$id_usr.')';
     //echo $sql;
     $rs=$this->db->query($sql);
     if  ($rs->num_rows()>0){
            $data['num_rows']=$rs->num_rows();
            $data['records']=$rs->result_array();
            return $data;
     }
        else {
            //Utente non ha privilegi //impossible
            return false;

        }

     $rs->free_result();
  }


  function get_ruoli_app($app){


     $sql='SELECT ruolo FROM ruoliapplicativi WHERE appliccazione='."'".$app."'";
     //echo $sql;
     $rs=$this->db->query($sql);
     if  ($rs->num_rows()>0){
            $data['num_rows']=$rs->num_rows();
            $data['records']=$rs->result_array();
            return $data;
     }
        else {
            //Utente non ha privilegi //impossible
            return false;

        }

     $rs->free_result();
  }


  }