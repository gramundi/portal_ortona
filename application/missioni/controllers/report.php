<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Report extends CI_Controller {

        // var per variabili della classe;
        function Report() {
		parent::__construct();
                 $id_usr=$this->session->userdata('id_user');
                if ($id_usr==''){
                      echo '<h3>acesso non consentito<h3>';
                      exit;
                 }
                $this->load->model('mission_model','mm');
	}

	function index($x) {

        //log_message('debug','reports');

        switch ($x){

            case 'm':$this->load->view('reports');break;
            
            case 'd':
                $dati['utenti']=$this->mm->get_UtentiInMissione();
                if ($dati['utenti']==0) {

                    echo 'missioni da autorizzare';
                    return;
                }
                $this->GeneraReportMiss($dati);
                break;
            case 'c':
                $dati['capitoli']=$this->mm->get_RiepilogoCapitoli();
                if ($dati['capitoli']==0) {
                    
                    echo 'missioni da autorizzare';
                    return;
                
                }
                $this->GeneraRiepilogoCapitoli($dati);
                break;

            default: $this->genreport($x); break;



        }
         //echo 'report';

}



function GeneraReportMiss($x) {


	    require('application/missioni/plugins/fpdf-1.6/fpdf.php');
	    $pwd = getcwd();
           //Costruttore del documento pdf
	    $pdf = new FPDF('P','cm');
	    ///////////////////////////////////////////////////////////////////////
	    // The page margins
            $pdf->setMargins(2, 2);
	    $pdf->addPage();
	    $pdf->SetAutoPageBreak(false);
            $bg = sprintf('%s/images/report.jpg',$pwd);
            $pdf->Image($bg,0,0,20);

            setlocale(0,"it_IT");
            #
            //%A - Nome completo del giorno della settimana in accordo con i parametri locali
            //%b - Nome del mese abbreviato in accordo con i parametri locali
            //%B - Nome completo del mese in accordo con i parametri locali
            $data=date(strftime ("%A %b %Y"));

            $utecurr='';
            $data=$x['utenti'];
            $pdf->setFont('Arial','B',10);
            $pdf->SetXY(2,4);
            $TotMiss=0;
            foreach($data as $x){
                if ($utecurr!=$x['nome']){
                   //Totale MIssioni Utente

                    if ($TotMiss!=0){
                            $pdf->ln(1);
                            $pdf->SetXY(16,$pdf->GetY());
                            $pdf->Cell($pdf->GetStringWidth($TotMiss),0,'Totale Costi:'.$TotMiss);
                            $TotMiss=0;
                    }
                   $utecurr=$x['nome'];
                   $pdf->ln(1);
                   $pdf->SetXY(2,$pdf->GetY());
                   $pdf->Cell($pdf->GetStringWidth($x['nome']),0,$x['nome']);
                   $pdf->SetXY(6,$pdf->GetY());
                   $pdf->Cell($pdf->GetStringWidth($x['cognome']),0,$x['cognome']);
                   $pdf->SetXY(10,$pdf->GetY());
                   $pdf->Cell($pdf->GetStringWidth($x['qualifica']),0,$x['qualifica']);
                }
                $pdf->ln(1);
                //Controllo se sono arrivato a fondo pagina e nel caso aggiungo una pagina
                if ($pdf->GetY() > 27) {
                        //echo 'NUOVA PAGINA';
                        $pdf->addPage();
                        $pdf->SetXY(2,4);

                }
                $pdf->SetXY(10,$pdf->GetY());
                //Gestione multilinea dell'oggetto
                $num_cell=intval(strlen($x['oggetto'])/40);
                $ly=$pdf->GetY();
                $amp_car=40;
                for ($i = 0; $i < $num_cell; $i++) {
                    $pdf->SetXY(10,$ly);
                    $pdf->Cell(0,0,substr($x['oggetto'],$amp_car*$i,$amp_car),0,'C');
                     //echo 'i='.$i.'str='.substr($oggetto,20*$i,20);
                     $ly=$ly+0.5;

                }
                $pdf->SetXY(10,$ly);
                $pdf->Cell(0,0,substr($x['oggetto'],$amp_car*$i,strlen($x['oggetto'])),0,'C');

                //$pdf->Cell($pdf->GetStringWidth($x['oggetto']),0,$x['oggetto']);

                $pdf->SetXY(18,$pdf->GetY());
                $pdf->Cell($pdf->GetStringWidth($x['costo']),0,$x['costo']);
                $TotMiss=$TotMiss+$x['costo'];
            }
            

          //$file_name=$dati['nome'].$dati['cognome'].$dati['oggetto'].$dati['id'];
            $file_name=$x['nome'].$x['cognome'];
	    $pdf->Output($file_name.'.pdf','I');
    }

function GeneraRiepilogoCapitoli($x) {


	     require('application/missioni/plugins/fpdf-1.6/fpdf.php');
	    $pwd = getcwd();
           //Costruttore del documento pdf
	    $pdf = new FPDF('P','cm');
	    ///////////////////////////////////////////////////////////////////////
	    // The page margins
            $pdf->setMargins(2, 2);
	    $pdf->addPage();
	    $pdf->SetAutoPageBreak(false);
            $bg = sprintf('%s/images/report.jpg',$pwd);
            $pdf->Image($bg,0,0,20);

            setlocale(0,"it_IT");
            #
            //%A - Nome completo del giorno della settimana in accordo con i parametri locali
            //%b - Nome del mese abbreviato in accordo con i parametri locali
            //%B - Nome completo del mese in accordo con i parametri locali
            $data=date(strftime ("%A %b %Y"));

            $capcurr='';
            $data=$x['capitoli'];
            $pdf->setFont('Arial','B',10);
            $pdf->SetXY(2,4);
            $TotC=0;
            foreach($data as $x){
                if ($capcurr!=$x['voce']){

                   //Totale MIssioni Utente
                   if ($TotC!=0){
                    $pdf->ln(1);
                    $pdf->SetXY(16,$pdf->GetY());
                    $pdf->Cell($pdf->GetStringWidth($TotC),0,'Totale Costi:'.$TotC);
                   }
                   $capcurr=$x['voce'];
                   $TotC=0;

                   $pdf->ln(1);
                   $pdf->SetXY(2,$pdf->GetY());
                   $pdf->Cell($pdf->GetStringWidth($x['voce']),0,$x['voce']);
                   $pdf->SetXY(6,$pdf->GetY());
                 }
                $pdf->ln(1);
                $pdf->SetXY(6,$pdf->GetY());
                $pdf->Cell($pdf->GetStringWidth($x['nome']),0,$x['nome']);
                $pdf->SetXY(10,$pdf->GetY());
                $pdf->Cell($pdf->GetStringWidth($x['cognome']),0,$x['cognome']);
                $pdf->SetXY(16,$pdf->GetY());
                $pdf->Cell($pdf->GetStringWidth($x['costo']),0,$x['costo']);

                $TotC=$TotC+$x['costo'];
            }

          //$file_name=$dati['nome'].$dati['cognome'].$dati['oggetto'].$dati['id'];
            $file_name='RiepilogoCapitoli';
	    $pdf->Output($file_name.'.pdf','I');
    }

    
function genreport($id) {



            $sp_area1=$this->mm->get_speseditipo($id,1);
            $sp_area2=$this->mm->get_speseditipo($id,2);
            $sp_area3=$this->mm->get_speseditipo($id,3);

            //Occorre inserire una funzione di stampa dati in libreria per stampare in modo formattato

            $allspese=array($sp_area1,$sp_area2,$sp_area3);
            $alldata['spese']=$allspese;
            $missione=$this->mm->get_missione($id);

            $alldata['missione']=$missione;

          /*
            foreach($missione as $row ){

                echo '---->'.$row['id'].$row['oggetto'].$row['capitolo'];


            }
            for( $i=0; $i<3; $i++ ){


                $vet=$allspese[$i];
                if ($vet){
                    foreach($vet as $row){

                    echo '----->'.$row['id_tipo'].$row['descrizione'].$row['qta'].$row['cu'].$row['area'].$row['tipo'];
                    }
                }

            }
        */

        $this->_generatePDF($alldata);

        }


        function _generatePDF($x) {


	    require('application/missioni/plugins/fpdf-1.6/fpdf.php');
	    $pwd = getcwd();

            //Costruttore del documento pdf
	    $pdf = new FPDF('P','cm');

	    ///////////////////////////////////////////////////////////////////////
	    // The page margins
	       $pdf->setMargins(2, 2);
	    ///////////////////////////////////////////////////////////////////////

	    $pdf->addPage();
	    $pdf->SetAutoPageBreak(false);
            $pdf->Header('Intestazione');
            //$missione=$x['missione'];

            $bg = sprintf('%s/images/report.jpg',$pwd);
            $pdf->Image($bg,0,0,20);

            ///////////////////////////////////////////////////////////////////////
	    // Dati Riassuntivi della Missione
	    //
            $pdf->setFont('Arial','B',12);
            //foreach($x['missione'])
            $dati=$x['missione'];
            $pdf->SetY(4);
            $pdf->Cell($pdf->GetStringWidth($dati['oggetto']),0,'MISSIONE DI:'.$dati['nome'].$dati['cognome']);
            $pdf->ln(2);

            //$pdf->Cell($pdf->GetStringWidth($dati['qualifica']),0,'QUALIFICA:'.$dati['qualifica']);
            //$pdf->SetXY(12,$pdf->GetY());
            //$pdf->Cell($pdf->GetStringWidth($dati['capitolo']),0,'CAPITOLO:'.$dati['capitolo']);
            //$pdf->ln(1);

            $pdf->Cell($pdf->GetStringWidth($dati['citta']),0,'LOCALITA:'.$dati['citta']);
            $pdf->SetXY(12,$pdf->GetY());
            $pdf->Cell($pdf->GetStringWidth($dati['oggetto']),0,'CAUSALE:'.$dati['oggetto']);
            $pdf->ln(1);

            $pdf->Cell($pdf->GetStringWidth($dati['data_p']),0,'DATA PARTENZA:'.$dati['data_p']);
            $pdf->SetXY(12,$pdf->GetY());
            $pdf->Cell($pdf->GetStringWidth($dati['data_r']),0,'DATA RIENTRO:'.$dati['data_r']);
            $pdf->ln(1);

            //$pdf->SetLineWidth(0.2);
            //$pdf->Line(0,$pdf->GetY(),20,$pdf->GetY());


            //Per ogni Area Genero Le spese
            $pdf->setFont('Arial','B',8);
            $TotG=0;
            for( $i=0; $i<3; $i++ ){
                $dati=$x['spese'][$i];
                if ($dati){

                    //Se l'area esiste stampo l'intestazione
                    $pdf->ln(2);
                    $pdf->setFont('Arial','B',12);

                    $pdf->Cell($pdf->GetStringWidth($dati[0]['area']),0,$dati[0]['area']);
                    $pdf->SetLineWidth(0.1);
                    $pdf->Line(2,$pdf->GetY()+0.5,20,$pdf->GetY()+0.5);
                    $pdf->ln(1);

                    $tot=0;
                    foreach($dati as $row){

                        $pdf->setFont('Arial','B',8);

                        //Stampo il Record
                        $pdf->SetX(2);
                        $pdf->Cell($pdf->GetStringWidth($row['tipo']),0,'TIPO:'.$row['tipo']);
                        $pdf->SetXY(7,$pdf->GetY());
                        $pdf->Cell($pdf->GetStringWidth($row['descrizione']),0,'DESCRIZIONE:'.$row['descrizione']);
                        $pdf->SetXY(16,$pdf->GetY());
                        $pdf->Cell($pdf->GetStringWidth($row['qta']),0,'QTA:'.$row['qta']);
                        $pdf->SetXY(18,$pdf->GetY());
                        $pdf->Cell($pdf->GetStringWidth($row['cu']),0,'COSTO:'.$row['cu'].'$');
                        $pdf->ln(1);
                        $tot=$tot+$row['cu']*$row['qta'];

                    }
                    if ($tot) {
                        $pdf->Cell($pdf->GetStringWidth($tot),0,'TOTALE:'.$tot."$");
                        $TotG=$TotG+$tot;

                    }
                }

             }

            $pdf->Line(2,$pdf->GetY()+0.5,20,$pdf->GetY()+0.5);
            $pdf->ln(1);
            $pdf->Cell($pdf->GetStringWidth($TotG),0,'TOTALE DA RIMBORSARE:'.$TotG.'$');

            $pdf->ln(1);
            setlocale(0,"it_IT");
            #
            //%A - Nome completo del giorno della settimana in accordo con i parametri locali
            //%b - Nome del mese abbreviato in accordo con i parametri locali
            //%B - Nome completo del mese in accordo con i parametri locali
            $data=date(strftime ("%A %b %Y"));


            $pdf->SetXY(1,28);
            $pdf->Cell($pdf->GetStringWidth($data),0,$data);


	    ///////////////////////////////////////////////////////////////////////
	    //$pdf->Output('/tmp/badge.pdf','F');
            $dati=$x['missione'];
            //$file_name=$dati['nome'].$dati['cognome'].$dati['oggetto'].$dati['id'];
            $file_name=$dati['nome'].$dati['cognome'];
	    $pdf->Output($file_name.'.pdf','I');

	
    }
}
?>