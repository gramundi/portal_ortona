<?php

/*
 * Sw di Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

class manage extends CI_Controller {

        function Manage() {
                parent::__construct();
                 $id_usr=$this->session->userdata('id_user');
                if ($id_usr==''){
                      echo '<h3>acesso non consentito<h3>';
                      exit;
                 }
                $this->load->model('ordinanze_model','am');
                
	}


 function index($op) {
             
            //$this->session->set_userdata('filter','NUL-NUL-NUL-NUL-NUL');
            $data['tipi_ordinanze']=$this->am->get_tipi();
            $data['ordinanti']=$this->am->get_ordinanti();
            if ($op=='add') { //add chiama il form di gestione registro
                  $data['title']='Nuova Ordinanza';
                  $data['op']='insert';
                  $data['ordinante']='';
                  $data['rif']='';
                  $data['oggetto']='';
                  $data['descrizione']='';
                  //Creo una nuova entry sul registro Form senza dati
                  $this->load->view('addmod',$data);

              }

            if ($op=='mod') {
                  $data['title']='Modifica ORDINANZA';
                  $data['op']='update';
                  $id=$this->uri->segment(4);
                  $data['id']=$id;
                  $rec=$this->am->getdata_ordinanze($id,'NUL-NUL-NUL-NUL');
                  foreach ($rec as $row){
                    $data['ordinante']=$row['ordinante'];
                    $data['rif']=$row['rif'];
                    $data['oggetto']=$row['oggetto'];
                    $data['descrizione']=$row['descrizione'];
                  }
                  //Creo una nuova entry sul registro Form senza dati
                  $this->load->view('addmod',$data);
              }

              if ($op=='bon'){
               $data['title']='Bonifica ORDINANZA';
                  $data['op']='bonifica';
                  $id=$this->uri->segment(4);
                  $data['id']=$id;
                  $rec=$this->am->getdata_ordinanze($id,'NUL-NUL-NUL-NUL');
                  foreach ($rec as $row){
                    $data['ordinante']=$row['ordinante'];
                    $data['rif']=$row['rif'];
                    $data['oggetto']=$row['oggetto'];
                    $data['descrizione']=$row['descrizione'];
                  }
                  //Creo una nuova entry sul registro Form senza dati
                  $this->load->view('addmod',$data);
            }

               if ($op=='insert'){ //recupero dati dal form  e li memorizzo sul DB
               $data['id_utente']=$this->session->userdata('id_user');
               $data['ordinante']=$_POST['ordinante'];
               $data['rif']=$_POST['rif'];
               $data['oggetto']=$_POST['oggetto'];
               $data['descrizione']=$_POST['descrizione'];
               //echo $data['id_utente'].'-'.$data['ordinante'].'-'.$data['tipo'].'-'.$data['rif'].$data['oggetto'].$data['descrizione'];
               $this->am->dml_ordinanze($op,$data);
               redirect('ordinanze');

               }

               if ($op=='update'){ //recupero dati dal form  e li memorizzo sul DB
               $data['id_utente']=$this->session->userdata('id_user');
               $data['ordinante']=$_POST['ordinante'];
               $data['rif']=$_POST['rif'];
               $data['oggetto']=$_POST['oggetto'];
               $data['descrizione']=$_POST['descrizione'];
               $data['id']=$_POST['id'];
               //echo $data['id_utente'].'-'.$data['ordinante'].'-'.$data['tipo'].'-'.$data['rif'].$data['oggetto'].$data['descrizione'];
               $this->am->dml_ordinanze($op,$data);
               redirect('ordinanze');

            }

            if ($op=='conferma'){
                $id=$this->uri->segment(4);
                $data['id']=$id;
                $this->am->dml_ordinanze($op,$data);
                redirect('ordinanze');
            }
            
            if ($op=='bonifica'){
                $data['id_utente']=$this->session->userdata('id_user');
               $data['ordinante']=$_POST['ordinante'];
               $data['rif']=$_POST['rif'];
               $data['oggetto']=$_POST['oggetto'];
               $data['descrizione']=$_POST['descrizione'];
               $data['id']=$_POST['id'];
                $this->am->dml_ordinanze($op,$data);
                redirect('ordinanze');
            }



 }


 function upload( $cod=0,$id=0 ) {

     
     $anno=date('Y')."/";
     
     $errore="";

     //echo $rif;
     if ($cod) {

     $data['title']='UPLOAD ORDINANZA';
     $data['cod']=$cod;
     $data['id']=$id;
     $data['errore']=$errore;
     $this->load->view('upload',$data);



     }


     if(isset($_FILES['user_file']))
    {

        $file = $_FILES['user_file'];
        


       if($file['error'] == UPLOAD_ERR_OK and is_uploaded_file($file['tmp_name']))
        {
            //Elimina i spazi e rinomina il file da uploadare secondo il codice
            //Codice progressivo dell'ordinanza
            $filename=str_replace('%20',"",$_POST['cod'].'.pdf');
            $filename=substr($filename, 0, strlen($filename)-8).'_'.substr($filename,strlen($filename)-8,strlen($filename));
            move_uploaded_file($file['tmp_name'], $anno.$filename);
            $op='upload';
            $data['id']=$_POST['id'];
            $data['nomefile']=$filename;
            $this->am->dml_ordinanze($op,$data);

        }
         redirect('ordinanze');
    }

 }
 }
