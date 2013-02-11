<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class consolida extends CI_Controller {
// Attenzione un errore di sintassi dentro questo modulo implica il non funzionamento della flexgrid poichè
// Tale controller rappresenta il gestore del flexgrid


    function Consolida ()
	{
		parent::__construct();
		 $id_usr=$this->session->userdata('id_user');
                if ($id_usr==''){
                      echo '<h3>acesso non consentito<h3>';
                      exit;
                 }
                $this->load->model('mission_model','mm');
                              
	}
	
	function index()
	{
	 $this->verifica();

	}


        function verifica(){
        $data['missioni']=$this->mm->get_MissioniApprovate();
        $data['title']='Missioni Approvate';
        $this->load->view('missioniapp',$data);


        }

        function trasferisci(){

            $data=$this->mm->GetRiepilogoMiss();
            if ($data==0){
                $data['msg']='NESSUNA MISSIONIE DA TRASFERIRE';
                $this->load->view('msg',$data);
            }

            // definizione Connessione Database Remoto
            //$conn = oci_connect('anagtest', 'anagtest', '//192.168.1.4/ORCL');
            $conn = oci_connect('missioni', 'missioni', '//127.0.0.1/XE');
            //Genero le righe di consolidamento su st_mvalor
            
            //01/mese sistema -1/anno
            $today = date("d-m-y");
            $mesepre = date("m")-1;
            $dataini='01-'.$mesepre.'-'.date("y");
            $datafin='30-'.$mesepre.'-'.date("y");
            //echo 'today:'.$today.'mese'.$month.'daini='.$dataini;
            //$this->session->userdata('username');
            $user='admin';
            foreach($data as $x){
            
            $qry_grp='select ST_TAB_GRUP from ST_LIQUID WHERE ST_MAST_COD='.$x['m1_mast_cod'];
            $stid = oci_parse($conn, $qry_grp);
            oci_execute($stid, OCI_DEFAULT);
            $res=oci_fetch_array($stid,OCI_ASSOC);
            $st_tab_grup=$res['ST_TAB_GRUP'];
            $q_max_prog='SELECT MAX(st_num_prog) as mass from st_mvalor WHERE ST_MAST_COD='.$x['m1_mast_cod'];
            $stid = oci_parse($conn, $q_max_prog);
            oci_execute($stid);
            $row = oci_fetch_array($stid, OCI_ASSOC);
            $st_num_prog=$row['MASS']+1;
            //echo 'ST_TAB_GRUP='.$st_tab_grup;
            $query='insert into st_mvalor(ST_MAST_COD,ST_NUM_PROG,ST_TAB_GRUP,ST_TIP_MOV,ST_TIP_LIQ,ST_COD_VOCE,';
            $query=$query.'ST_CAR_N01,ST_SW1,ST_QUANTITA,ST_VALUNIT,ST_IMPTOTA,ST_POSTAMPA,ST_DAT_ELAB,';
            $query=$query.'ST_DAT_INIZIO,ST_DAT_FINE,ST_SIMP_ACC,ST_UTENTE) VALUES';
            $query=$query.'('.$x['m1_mast_cod'].','.$st_num_prog.','."'".$st_tab_grup."'".','."'M',0,'00880','C','N',1,".$x['costo'].','.$x['costo'];
            $query=$query.",'00',".'to_date('."'".$today."'".','."'dd-mm-yy'),".'to_date('."'".$dataini."'".','."'dd-mm-yy'),";
            $query=$query.'to_date('."'".$datafin."'".','."'dd-mm-yy'),".'0,'."'".$user."'".')';
            //echo $query;
            $stid = oci_parse($conn, $query);
            oci_execute($stid, OCI_DEFAULT);
            oci_commit($conn);
            oci_free_statement($stid);

            $this->mm->Consolida_Miss_Ute($x['id']);

        }
        //echo 'Trasferimento Eseguito Con Successo';
        oci_close($conn);

        $data['msg']='Esportazione delle Missioni in retribuzione personale eseguita con Successo';
        $this->load->view('msg',$data);
        
        }

        function revoca(){


            $a=0;
            $m=0;
            $u=0;
            $data['title']='Revoca Missioni';
            if (isset($_POST['anno'])) $a=$_POST['anno'];
            if (isset($_POST['mese'])) $m=$_POST['mese'];
            if (isset($_POST['utente'])) $u=$_POST['utente'];
            echo $a.'-'.$m.'-'.$u;
            $data['missioni']=$this->mm->get_Missio_Cons($a,$m,$u);
            $this->load->view('revmissioni',$data);

        }

}
?>

