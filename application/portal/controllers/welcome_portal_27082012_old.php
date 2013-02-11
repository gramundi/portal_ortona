<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
 * Portale Applicazioni COntroller di Accesso al Portale di applicazioni
 * Configurazioni e Inizializzazioni
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.eu>
 */

class Welcome_portal extends CI_Controller {

        // var per variabili della classe;
        function Welcome_portal() {
		parent::__Construct();
                $id_usr=$this->session->userdata('id_user');
                if ($id_usr==''){
                      echo '<h3>acesso non consentito<h3>';
                      exit;
                 }
                $this->load->model('login_model','lm');
                	}
	
	function index() {
              $id_usr=$this->session->userdata('id_user');
              if ($id_usr==''){
                      $this->load->view('../../share_views/not_logged.php');
                       return;
                 }
              if ($this->session->userdata('ruolo_p')) $ruolo_p=$this->session->userdata('ruolo_p');
              else {
                    $ruolo_p=$this->lm->get_privilegi($id_usr,'Portal');
                        //echo $ruolo_p;
                    $this->session->set_userdata('ruolo_p',$ruolo_p);
             }
             //$this->load->view('welcome_portale',$data);
             $this->previsioni($ruolo_p,'Ortona');
	}



        //Call Back AJAX to get roless of the application
        function get_messages(){
           //echo('check new messages');
           $id=$this->session->userdata('id_user');
           $records = $this->lm->check_newmessages($id);
           echo $records;
        }


 function previsioni($ruolo_p,$location=null){


     $data['title']='Portale Applicativo Comune di Ortona';
     $data['ruolo_p']=$ruolo_p;

            if ($location==null)$location='ortona';
            // Catturiamo e salviamo il file XML sul nostro server per evitare alcuni problemi
            // che possono verificarsi su alcuni server
            //nella stringa curl_init inseriamo la località desiderata in questo esempio è Roma
            $ch= curl_init('http://www.google.com/ig/api?weather='.$location.',&hl=it');
            
            $fp = fopen("weather.xml", "w");
            //Con la funzione CURL settiamo i parametri in maniera che l'XML venga letto correttamente (encoding)
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);

           
           $xml = simplexml_load_file('weather.xml');
           $information = $xml->xpath("/xml_api_reply/weather/forecast_information");
           
           if (isset($information[0])){
                   $current = $xml->xpath("/xml_api_reply/weather/current_conditions");
                   $forecast_list = $xml->xpath("/xml_api_reply/weather/forecast_conditions");
                   $data['city']=$information[0]->city['data'];
                   $data['temp']=$current[0]->temp_c['data'];
                   $data['condition']=$current[0]->condition['data'];
                   $data['icon']=$current[0]->icon['data'];
                   $data['forecast_list']=$forecast_list;
                   $data['f_prev']=1;
                   $data['f_cal']=0;

                   //Applicazioni Contenuti Centrali
                   $data['f_prev']=1;
                   $data['f_cal']=0;


                   $this->load->view('welcome_portale',$data);
                  
        

                   }
           else {
                $data['f_prev']=0;
                $this->load->view('welcome_portale',$data);
           
           }
           
        }

  
    
        
    }


?>