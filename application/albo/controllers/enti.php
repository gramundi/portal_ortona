<?php

/*
 * Sw di Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */

class Enti extends CI_Controller {

        // var per variabili della classe;
        function Enti() {
		parent::__Construct();
                 $id_usr=$this->session->userdata('id_user');
                if ($id_usr==''){
                      echo '<h3>acesso non consentito<h3>';
                      exit;
                 }
                $this->load->model('enti_model','em');
	}
	
	function index() {

                $ente= $this->input->post('par');
                $records = $this->em->CercaEnte($ente);
                //echo 'NUMMMMMMM'.$records['num_rows'];

                 /*
                  * Json build WITH json_encode.
                  */

                 if ($records['num_rows']==0) return;

               //Codifica dei dati estrati in formato json attraverso la libreria json_encode
               //Prende un array associativo e lo formatta in notazione JSON
               $res=json_encode($records['records']);
               echo $res;
               
	}





        function ManageEnti($op=0,$id=0){

         if (isset($_POST['op']) ) $op=$_POST['op'];
        
         //Lista enti
         if ($op==0){
             $stringa='';
             if (isset($_POST['ente']))$stringa=$_POST['ente'];
             $records['enti'] = $this->em->Get_Enti($stringa);
             $this->load->view('list_enti',$records);

         }
         //Modifica Ente
         if ($op==1){
             //echo 'Modifica Utente';
             $data['id']=$_POST['idente'];
             $data['tipo']=$_POST['tipo'];
             $data['nome']=$_POST['nome'];
             $data['indir']=$_POST['indir'];
             $data['tel']=$_POST['tel'];
             $data['piva']=$_POST['piva'];
             $data['cf']=$_POST['cf'];
             $this->em->update_ente($data);
             redirect('enti/ManageEnti/0/0');
             }
        //Aggiungi Ente
        if ($op==2){

            //echo 'Aggiungi';
             $data['tipo']=$_POST['tipo'];
             $data['nome']=$_POST['nome'];
             $data['indir']=$_POST['indir'];
             $data['tel']=$_POST['tel'];
             $data['piva']=$_POST['piva'];
             $data['cf']=$_POST['cf'];
            $this->em->insert_ente($data);
            redirect('enti/ManageEnti/0/0');

        }
        //cancella Ente
        if ($op==3){


            $id=$this->uri->segment(4);
            //echo 'Cancella utente='.$id;
            $this->em->delete_ente($id);
            redirect('enti/ManageEnti/0/0');

        }
       }


}