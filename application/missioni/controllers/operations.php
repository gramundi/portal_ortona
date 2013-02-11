<?php

/*
 * Sw di Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 * Modulo Operation
 * Funzioni Getutenti(),getDateMissione()
 *
 * Revision per integrazione portale: 20/11/2011
 */

class Operations extends CI_Controller {

        // var per variabili della classe;
        function Operations() {
		parent::__construct();
                 $id_usr=$this->session->userdata('id_user');
                if ($id_usr==''){
                      echo '<h3>acesso non consentito<h3>';
                      exit;
                 }
                $this->load->model('mission_model','mm');
                $this->load->library('session');
                $this->load->library('flexigrid');
	}
	
	function index() {
		
         log_message('debug','getusers');
           
	}

	function getutenti()
	{

                $nomeute= $this->input->post('par');
                //echo $nomeute;
                $records = $this->mm->CercaUser($nomeute);
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

        //E' Chiamata da un call Jquery AJAX per verificare se la data di una spesa
        // è all'interno delle date di missione
        //Viene utilizzata per validare le spese di una missione
        // Tutte le spese devono essere all'interno della durata della missione
        function getDateMissione()
	{

                //echo 'DateMissione';
                //$data= $this->input->post('missione');
                
                $data= $this->input->post('par');

                $id_miss=substr($data,0,strpos($data,','));
                $data_spesa=substr($data,strpos($data,',')+1);
                //echo $data.'--------'.$id_miss.'----'.$data_spesa;
                $res=array();
                $date = $this->mm->GetDateMiss($id_miss);
                //Controllo che la data del giustificativo di spesa
                //sia nel range della data di missione
                //
                //
                //echo $date['rientro'].'--'.$date['partenza'];
                $res['date']=$date;
                $res['result']='';

                $date_p = explode('-', $date['partenza']);
                $date_r = explode('-', $date['rientro']);
                $date_s = explode('-', $data_spesa);


                $partenza_gg = $date_p[0];
                $partenza_mm = $date_p[1];
                $partenza_aa = $date_p[2];
                //echo 'Partenza:'.$partenza_gg.'--'.$partenza_mm.'--'.$partenza_aa;

                $fine_gg = $date_s[2];
                $fine_mm = $date_s[1];
                $fine_aa = $date_s[0];
                //echo 'Spesa:'.$fine_gg.'--'.$fine_mm.'--'.$fine_aa;

                $date_diff = mktime(12, 0, 0, $fine_mm, $fine_gg, $fine_aa) - mktime(12, 0, 0, $partenza_mm, $partenza_gg, $partenza_aa);
                $date_diff_1  = floor($date_diff /86400/365);

                
                $partenza_gg = $date_r[0];
                $partenza_mm = $date_r[1];
                $partenza_aa = $date_r[2];
                $date_diff = mktime(12, 0, 0, $partenza_mm, $partenza_gg, $partenza_aa)-mktime(12, 0, 0, $fine_mm, $fine_gg, $fine_aa);
                $date_diff_2  = floor($date_diff /86400/365);

                
                //controllo che la data scontrino è maggiore= della data partenza
                //controllo che la data scontrio sia minore= della data rientro
                if (($date_diff_1 >=0) && ($date_diff_2 >=0)) $res['result']='ok';

                //echo 'differenza:'.$date_diff;

                //if($res['partenza'] > $data_spesa) $result='ko';
                //if($res['rientro']  < $data_spesa) $result='ko';

               //Codifica dei dati estrati in formato json attraverso la libreria json_encode
               //Prende un array associativo e lo formatta in notazione JSON
               //$res=json_encode($res['date']);
               echo $res['result'];

	}


}