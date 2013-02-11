<?php
class Mycal_model extends CI_Model {
	
	var $conf;
	
	function Mycal_model () {
		parent::__construct();
		$this->load->model($this->config->item('share_model'),'cd');
                $this->conf = array(
			'show_next_prev' => true,
			'next_prev_url' => base_url() . 'agenda.php/agenda/display'
		);
		
		$this->conf['template'] = '
			{table_open}<table border="1" cellpadding="0" cellspacing="0" class="calendar">{/table_open}
			
			{heading_row_start}<tr>{/heading_row_start}
			
			{heading_previous_cell}<th><a href="{previous_url}">&lt;&lt;</a></th>{/heading_previous_cell}
			{heading_title_cell}<th colspan="{colspan}">{heading}</th>{/heading_title_cell}
			{heading_next_cell}<th><a href="{next_url}">&gt;&gt;</a></th>{/heading_next_cell}
			
			{heading_row_end}</tr>{/heading_row_end}
			
			{week_row_start}<tr>{/week_row_start}
			{week_day_cell}<td>{week_day}</td>{/week_day_cell}
			{week_row_end}</tr>{/week_row_end}
			
			{cal_row_start}<tr class="days">{/cal_row_start}
			{cal_cell_start}<td class="day">{/cal_cell_start}



                        {cal_cell_content}<span class="day_num">{day}</span>{content}{/cal_cell_content}
			{cal_cell_content_today}<span class="day_num highlight">{day}</span>{content}{/cal_cell_content_today}
			
			{cal_cell_no_content}<span class="day_num">{day}</span>{/cal_cell_no_content}
			{cal_cell_no_content_today}<span class="day_num highlight">{day}</span>{/cal_cell_no_content_today}
			
			{cal_cell_blank}&nbsp;{/cal_cell_blank}
			
			{cal_cell_end}</td>{/cal_cell_end}
			{cal_row_end}</tr>{/cal_row_end}
			
			{table_close}</table>{/table_close}
		';
            
            

            

		
	}
	
	function get_calendar_data($year, $month,$id_usr = null) {


            //echo 'year='.$year.'month='.$month;
		$sql ='SELECT DISTINCT DATE_FORMAT(data, '."'".'%Y-%m-%e'."'".') AS date';
                $sql=$sql.' FROM vagenda WHERE data LIKE '."'%".$year.'-'.$month."%'";
                if ($id_usr) $sql=$sql.' AND id_titolare='.$id_usr;
                $rs=$this->db->query($sql);


                $cal_data = array();

                foreach ($rs->result() as $row) { //for every date fetch data
                    $a = array();
                    $i = 0;
                    //echo $row->date;
                    $sql= 'SELECT id_appuntamento,oramin,richiedente,ragsocrich FROM vagenda WHERE  DATA='."'".$row->date."'";
                    $sql.= ' AND id_titolare='.$id_usr;
                    //echo $row->date.'-'.$sql;
                    $rsi=$this->db->query($sql);
                    //echo 'numero righe='.$rs->num_rows();
                     foreach ($rsi->result() as $r) {
                         //echo $r->titolo;
                         if ($r->ragsocrich==null) $rich=$r->richiedente;
                         else $rich=$r->ragsocrich;
                         $rich=substr($rich,0,20);
                         $a[$i] = $r->id_appuntamento.'-'.$r->oramin.'-'.$rich;     //make data array to put to specific date
                         $i++;
                     }
                        $cal_data[substr($row->date,8,2)] = $a;

                }

                return $cal_data;
		
	}


        function handle_titolari($rec){

            if ($rec['op']==1){
                
            //Controllo se il titolare non impegnato nella data ora dell'appuntamento
            $sql='SELECT date_format(data'.','."'".'%d/%m/%Y'."'".') as data,ora,min from vagenda WHERE id_appuntamento='.$rec['id_appuntamento'];
            //echo $sql;
            $rs=$this->db->query($sql);
            $result=$this->Check_appuntamento($rs->row()->data,$rec['id_titolare'],$rs->row()->ora,$rs->row()->min);
            //echo 'result='.$result;
            if ( $result > 0 ) return 0;
            $sql='INSERT INTO AGENDATITOLARI(id_titolare, id_agenda, titolo)';
            $sql.='VALUES ('.$rec['id_titolare'].','.$rec['id_appuntamento'].','."'".$rec['titolo']."'".')';
            $this->db->query($sql);
            }
            else {
                $sql='DELETE FROM AGENDATITOLARI WHERE id_agenda='.$rec['id_appuntamento'];
                $sql.=' AND id_titolare='.$rec['id_titolare'];
                echo $sql;
                $this->db->query($sql);
            }
           return 1;
        }

	function gest_appuntamento($rec,$op) {

            $id_agenda=0;
           
          
           
            if ($op=='add'){

                $sql='INSERT INTO AGENDA(data, ora, ora_fraz, id_richiedente, tipo, titolo, descrizione)';
                $sql.='VALUES (STR_TO_DATE('."'".$rec['data']."'".','."'".'%d/%m/%Y'."'".'),'.$rec['ora'];
                $sql.=','."'".$rec['min']."'".','.$rec['richiedente'].','."'".$rec['tipo']."'".',';
                $sql.="'".$rec['titolo']."'".','."'".$rec['descrizione']."'".')';
                
                $this->db->query($sql);
                $rs=$this->db->select_max('id','maxid')->get('agenda');
                $id_agenda=$rs->row()->maxid;

                $sql='INSERT INTO AGENDATITOLARI(id_titolare, id_agenda, titolo)';
                $sql.='VALUES ('.$rec['id_titolare'].','.$id_agenda.','."'".$rec['titolo']."'".')';
                $this->db->query($sql);
                
                return $id_agenda;


            }
            //Modifica appuntamento
            if ($op=='mod'){
                $sql='UPDATE AGENDA SET data=STR_TO_DATE('."'".$rec['data']."'".','."'".'%d/%m/%Y'."'".')';
                $sql.=',ora='.$rec['ora'].',ora_fraz='."'".$rec['min']."'".',id_richiedente='.$rec['richiedente'];
                $sql.=',tipo='."'".$rec['tipo']."'".',titolo='."'".$rec['titolo']."'";
                $sql.=',descrizione='."'".$rec['descrizione']."'".' WHERE id='.$rec['id'];
                
                //echo $sql;
                $this->db->query($sql);
                
                $id_agenda=$rec['id'];
                return $id_agenda;
            }

            if ($op=='del'){
                $sql='DELETE FROM  AGENDATITOLARI WHERE id_agenda='.$rec['id'];
                $this->db->query($sql);
                $sql='DELETE FROM AGENDA WHERE id='.$rec['id'];
                $this->db->query($sql);
            }
		
	}


//Recupera i dati di un singolo appuntamento
function get_appuntamento($id,$id_titolare) {
   $data=array();

   $sql='SELECT ora,min,id_richiedente,richiedente, tipo,titolari,oggetto,descrizione from VAGENDA WHERE id_appuntamento='.$id;
   $sql.=' AND id_titolare='.$id_titolare;
   //echo $sql;
   $rs=$this->db->query($sql);
   //log_message('debug', $rs->num_rows());
   if  ($rs->num_rows()>0){
     $data['records']=$rs->result_array();
     $data['num_rows']=$rs->num_rows;
     $rs->free_result();
     return $data;
   }
}


 //Recupera dal DB tutti gli appuntamenti che validano il filtro
 function getdata_appuntamenti($filter,$off=0,$lim=6 ) {

  $data=array();


  $sql='SELECT id_appuntamento,tipo,id_richiedente,richiedente,id_titolare,titolari';
  $sql.=' ,date_format(data,'."'".'%d/%m/%Y'."'".') as data,oramin,oggetto,descrizione';
  $sql.=' from vagenda WHERE STATO!='."'".'A'."'";

  $filtri=explode('-',$filter);

  if ($filtri[0]!='NUL') $sql=$sql.' AND richiedente LIKE '."'%".$filtri[0]."%'";
  if ($filtri[1]!='NUL') $sql=$sql.' AND titolari LIKE '."'%".$filtri[1]."%'";
  if ($filtri[2]!='NUL') $sql=$sql.' AND data=str_to_date('."'".$filtri[2]."'".','."'".'%d/%m/%Y'."')";
  //echo $sql;
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



        function generate ($year, $month,$id_usr = null) {

            
            $this->load->library('calendar', $this->conf);
	    $data_cal = $this->get_calendar_data($year, $month,$id_usr);
            if ($id_usr) $nomecal=$this->cd->get_nome($id_usr);
            else $nomecal='calendario completo';
            return $this->calendar->generate_multi($nomecal,$year, $month, $data_cal);
		
	}



        function get_agendeingestione($id_responsabile) {

            $sql='SELECT id_titolare as id_user,nomegest FROM RESPONSABILITITOLARI WHERE id_responsabile='.$id_responsabile;
            $rs=$this->db->query($sql);
            if( $rs->num_rows() > 0 ) {
              $data['num_gest']=$rs->num_rows();
              $data['gestori']=$rs->result_array();
              $rs->free_result();
              return $data;

            }
            else return 0;
                    

	}

        //Recupera tutti gli utenti che possono gestire un agenda con qualsiasi ruolo
        // Reucpero tutti gli utenti tramite la select sulla vista dei privilegi
        function get_allgestori() {

            $sql='SELECT id_user,concat(nome,'."'".'-'."'".',cognome) as nomegest';
            $sql.=' FROM PRIVILEGI where applicazione='."'".'Agenda'."'";
            $rs=$this->db->query($sql);
            if  ($rs->num_rows()>0){
                $data=$rs->result_array();
                $rs->free_result();
                return $data;
        }
        }

        function get_richiedenti($str) {

            $sql='SELECT id,concat(cognome,nome,ragsoc) as richiedente,tel,sito,email from vcontatti WHERE cognome like '."'%".$str."%'";
            $rs=$this->db->query($sql);
            if  ($rs->num_rows()>0){
                $data['records']=$rs->result_array();
                $data['num_rows']=$rs->num_rows();
                $rs->free_result();
                return $data;
        }
        }


// Tutti i titolari ammissibili non presenti nell'appuntamento $id_appuntamento
function get_titolari($str,$id_appuntamento) {

      
            $sql='SELECT id_user,concat(nome,'."'".'-'."'".',cognome) as titolare from privilegi WHERE cognome like '."'%".$str."%'";
            $sql.=' AND applicazione='."'".'Agenda'."'";
            $sql.=' AND id_user not in( SELECT id_titolare from agendatitolari WHERE id_agenda='.$id_appuntamento.')';
            //echo $sql;
            $rs=$this->db->query($sql);
            if ( $rs->num_rows()>0 ){
                $data['records']=$rs->result_array();
                $data['num_rows']=$rs->num_rows();
                $rs->free_result();
                return $data;
        }
        }

// Tutti i titolari di un determinato appuntamento
function get_titolari_app($id_appuntamento) {
         
 $sql='SELECT id_titolare,titolare,email from titolariperappuntamento WHERE id='.$id_appuntamento;
 $rs=$this->db->query($sql);
 if ( $rs->num_rows()>0 ){
        $data['records']=$rs->result_array();
        $data['num_rows']=$rs->num_rows();
        $rs->free_result();
        return $data;
 }
}

// Tutti i delegati di un determinato appuntamento
function get_delegati_app($id_appuntamento) {

 $sql='SELECT id_appuntamento,id_titolare,id_delegato,titolare,delegato from vdelegatiapp WHERE id_appuntamento='.$id_appuntamento;
 $rs=$this->db->query($sql);
 if ( $rs->num_rows()>0 ){
        $data['records']=$rs->result_array();
        $data['num_rows']=$rs->num_rows();
        $rs->free_result();
        return $data;
 }
}



function Check_appuntamento($dataapp,$id_titolare,$ora,$min){

  //Controlla se il titolare id_titolare è impegnato nel data-ora dell'appuntamento
  $sql='SELECT min FROM VAGENDA WHERE id_titolare='.$id_titolare;
  $sql.=' and ora='.$ora.' and date(data)=str_to_date('."'".$dataapp."'".','."'".'%d/%m/%Y'."')";
  $sql.=' and min='."'".$min."'";
  //echo $sql;
  $rs=$this->db->query($sql);
  return $rs->num_rows();//-->0 non ci sono appuntamenti >0 appuntamento esistente a questo ora


  //Controlla se il titolare è impegnato in una delega;

  //

}
        
function get_orari_validi($dataapp,$id_titolare){

        $data=array();
        for ($ora=8;$ora<19;$ora++){    
        
            for($i=0;$i<4;$i++){
                switch($i){
                  case 0:$el='00';break;
                  case 1:$el='15';break;
                  case 2:$el='30';break;
                  case 3:$el='45';break;
                     
                }
            //Check se ho un appuntamento alle ora:min($ora:$el)
            $sql='select min from vagenda where id_titolare='.$id_titolare;
            $sql.=' and ora='.$ora.' and date(data)=str_to_date('."'".$dataapp."'".','."'".'%d/%m/%Y'."')";
            $sql.=' and min in ('."'".$el."'".')';
            
            $rs=$this->db->query($sql);
            if ( $rs->num_rows()==0 ){
                $data[$ora][$el]=$el;
                
            }
          }

        }
        foreach ( $data as $key1=>$val ){
                print "$key1<br>";
                foreach ( $val as $key2=>$final_val ){
                    print "$key2: $final_val<br>";
                }

           }
        return $data;
 }


}