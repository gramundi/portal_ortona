<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Certifica extends CI_Controller {

        // var per variabili della classe;
        function Certifica() {
		parent::__construct();
                $id_usr=$this->session->userdata('id_user');
                if ($id_usr==''){
                      echo '<h3>acesso non consentito<h3>';
                      exit;
                 }
                $this->load->model('albo_model','am');
                $this->load->library('pdf','pdf');
	}



	function index($x) {


        switch($x)
        {


                case 'stampareg':
                      $dati['tot']=0;
                      $this->load->view('stampa_registro',$dati);
                      break;

                case 'certificato':
                      $id=$this->uri->segment(4);
                      $dati['certificato']=$this->am->getdati_certificazione($id);
                      $this->GenCertificato($dati);
                      break;
                case 'generastampareg':
                      $dal=$_POST['dal'];
                      $al=$_POST['al'];

                      $dati=$this->am->getdati_stampa($dal,$al);
                      //echo 'numero di registrazioni selezionate:='.$dati['tot'];
                      if ($dati['tot']==0)
                          $this->load->view('stampa_registro',$dati);
                      else $this->StampaRegistro($dati);
                      break;


        }
        

        }
  

function StampaRegistro1($data){

$this->pdf->SetFont('Arial','',11.5);
$attr = array('titleFontSize'=>18, 'titleText'=>'this would be the title');
$numfields=$data['numfields'];
$this->pdf->mysql_report($numfields,$data,false,$attr);
$this->pdf->Output();
}


function GenCertificato($data){

    $x=$data['certificato'];
    //require('application/plugins/fpdf-1.6/fpdf.php');
    $pwd = getcwd();
    
    //Imposto l'unità di misura in Centimetri
    $this->pdf->FPDF('P','cm');
    $this->pdf->SetFont('Arial','I',8);

    ///////////////////////////////////////////////////////////////////////
    // The page margins
    $this->pdf->setMargins(2, 2);

    $this->pdf->AliasNbPages();

    iconv("UTF-8", "ISO-8859-1", "à");
    iconv("UTF-8", "ISO-8859-1", "'");



    $this->pdf->addPage();
    $this->pdf->SetAutoPageBreak(false);
    $this->pdf->Header('Intestazione');
    $bg = sprintf('%s/images/report.jpg',$pwd);
    $this->pdf->Image($bg,0,0,20);

    $this->pdf->setFont('Arial','B',12);


    $date=date("d/m/Y H:i:s"); // 17:03:17 03/10/2001
    $cap1='Ortona li, '.$date;
    $this->pdf->SetY(4);
    $this->pdf->Cell($this->pdf->GetStringWidth($cap1),0,$cap1);
    $this->pdf->ln(2);


    $cap1='Si certifica l'."'".'avvenuta pubblicazione dell'."'".'atto di cui al protocollo sotto riportato.';
    $this->pdf->SetY(6);
    $this->pdf->Cell($this->pdf->GetStringWidth($cap1),0,$cap1);
    $this->pdf->ln(2);


    $cap2='Estremi dell'."'".'atto:';
    $this->pdf->SetY(9);
    $this->pdf->Cell($this->pdf->GetStringWidth($cap2),0,$cap2);
    $this->pdf->ln(2);


    //$this->pdf->Rect(5,10,10,2);

    //$this->pdf->Line(0,8,30,8);

    $this->pdf->setFont('Arial','B',8);
    $this->pdf->SetY(10);
    $this->pdf->SetX(3);
    $this->pdf->Cell($this->pdf->GetStringWidth('Richiedente:'),0,'Richiedente:');
    $this->pdf->setFont('Arial','B',12);
    $this->pdf->SetX($this->pdf->getX()+1);
    $this->pdf->Cell($this->pdf->GetStringWidth($x['ente']),0,$x['ente']);
    $this->pdf->ln(1);



    $this->pdf->SetX(3);
    $this->pdf->setFont('Arial','B',8);
    
    $this->pdf->Cell($this->pdf->GetStringWidth('Oggetto: '),0,'Oggetto:');
    //$this->pdf->SetX($this->pdf->getX()+1);
    $this->pdf->SetX(2);

    //$this->pdf->Cell($this->pdf->GetStringWidth($x['oggetto']),0,$x['oggetto']);
    $this->pdf->setFont('Arial','B',12);

    /*Basically the library fpdf  does not support utf-8,
     so the moment you need to insert anything extra into your document a
     conversion needs to be done (iconv).
     */

    $this->pdf->write(1,iconv("UTF-8", "ISO-8859-1",$x['oggetto']));

    //$this->pdf->write(1,$x['oggetto']);
    $this->pdf->ln(3);


    //$this->pdf->Line(0,8,30,8);
    //$this->pdf->SetLineWidth(0.4);

    $this->pdf->setFont('Arial','B',12);
    $cap1='Estremi della pubblicazione:';
    $this->pdf->SetY(18);
    $this->pdf->Cell($this->pdf->GetStringWidth($cap1),0,$cap1);
    $this->pdf->ln(2);

    $this->pdf->SetY(19);
    $this->pdf->SetX(3);
    $this->pdf->setFont('Arial','B',8);
    $this->pdf->Cell($this->pdf->GetStringWidth('Repertorio: '),0,'Repertorio: ');
    $this->pdf->setFont('Arial','B',12);
    $this->pdf->SetX($this->pdf->getX()+1);
    $this->pdf->Cell($this->pdf->GetStringWidth($x['codice']),0,$x['codice']);
    $this->pdf->ln(1);

    $this->pdf->SetY(20);
    $this->pdf->setFont('Arial','B',8);
    $this->pdf->SetX(3);
    $this->pdf->Cell($this->pdf->GetStringWidth('Data inizio pubblicazione: '),0,'Data inizio pubblicazione: ');
    $this->pdf->setFont('Arial','B',12);
    $this->pdf->SetX($this->pdf->getX()+1);
    $this->pdf->Cell($this->pdf->GetStringWidth($x['dal']),0,$x['dal']);
    $this->pdf->ln(1);


    $this->pdf->SetY(21);
    $this->pdf->setFont('Arial','B',8);
    $this->pdf->SetX(3);
    $this->pdf->Cell($this->pdf->GetStringWidth('Data termine pubblicazione:'),0,'Data termine pubblicazione: ');
    $this->pdf->setFont('Arial','B',12);
    $this->pdf->SetX($this->pdf->getX()+1);
    $this->pdf->Cell($this->pdf->GetStringWidth($x['al']),0,$x['al']);
    
    $this->pdf->ln(1);

    $this->pdf->SetY(25.5);
    $this->pdf->setFont('Arial','B',8);
    $this->pdf->SetX(12.1);

    $username=$this->session->userdata('username');
    $info_user=$this->am->get_infouser($username);
    $nome=$info_user['nome'];
    $cognome=$info_user['cognome'];

    $this->pdf->Cell($this->pdf->GetStringWidth($nome),0,'('.$nome.'  '.$cognome.')');
    

    /*#
    //%A - Nome completo del giorno della settimana in accordo con i parametri locali
    //%b - Nome del mese abbreviato in accordo con i parametri locali
    //%B - Nome completo del mese in accordo con i parametri local $data=date(strftime ("%A %b %Y"));
     *
     *
     */
    $this->pdf->Footer();
    $file_name='test';
    $this->pdf->Output($file_name.'.pdf','I');

}

function StampaRegistro($data) {



    //$x=$data['certificato'];
    //require('application/plugins/fpdf-1.6/fpdf.php');
    $pwd = getcwd();
    //Costruttore del documento pdf
    $this->pdf->FPDF('P','cm');
    ///////////////////////////////////////////////////////////////////////
    // The page margins
    $this->pdf->setMargins(1, 1);
    $this->pdf->AliasNbPages();

    $this->pdf->addPage();
    $this->pdf->SetAutoPageBreak(false);
    $this->pdf->Header('Intestazione');
    //$bg = sprintf('%s/images/report.jpg',$pwd);
    //$this->pdf->Image($bg,0,0,20);

    $this->pdf->setFont('Arial','B',8);

    $this->pdf->SetY(4);
    $testo='Codice              Ente                             Oggetto                                                                                      Responsabile                 dal                   al';
    $this->pdf->Cell($testo,0.5,$testo,1,'C');
    $this->pdf->setFont('Arial','B',8);
    $y=5;
    $x=1;

    foreach( $data['records'] as $rec){
        $cod=$rec['codice'];
        $ente=$rec['ente'];
        $oggetto=$rec['oggetto'];
        $responsabile=$rec['responsabile'];
        $dal=$rec['dal'];
        $al=$rec['al'];

        $this->pdf->SetXY($x,$y);
        
        //Cell(float w [, float h [, string txt [, mixed border [, int ln [, string align [, boolean fill [, mixed link]]]]]]])
        $this->pdf->Cell(1,0,$cod,0,'C');
        
        $x=$x+2;
        $this->pdf->SetXY($x,$y);
        $this->pdf->Cell(substr($ente,0,20),0,substr($ente,0,20),0,'C');

        $x=$x+3;
        //$this->pdf->SetX($x);
        //$this->pdf->write(0.2,iconv("UTF-8", "ISO-8859-1",$oggetto));
        $num_cell=intval(strlen($oggetto)/55);
        //echo $oggetto.'Lung='.strlen($oggetto).'numcel='.$num_cell;
        $ly=$y;
        $amp_car=55;
        for ($i = 0; $i < $num_cell; $i++) {

             //$this->pdf->Cell($oggetto,0,iconv("UTF-8", "ISO-8859-1",substr($oggetto,20*$i,20)),0,'C');
             
             $this->pdf->SetXY($x,$ly);
             $this->pdf->Cell(0,0,substr($oggetto,$amp_car*$i,$amp_car),0,'C');
             //echo 'i='.$i.'str='.substr($oggetto,20*$i,20);
             $ly=$ly+0.5;
             //$this->pdf->SetY($y);
        }
        //se multiplo di 50 devo stampare la parte finale
        $this->pdf->SetXY($x,$ly);
        $this->pdf->Cell(0,0,substr($oggetto,$amp_car*$i,strlen($oggetto)),0,'C');
        

        $x=$x+8;
        $this->pdf->SetXY($x,$y);
        $this->pdf->Cell($responsabile,0,$responsabile,0,'C');

        $x=$x+3;
        $this->pdf->SetXY($x,$y);
        $this->pdf->Cell($dal,0,$dal,0,'C');

        $x=$x+2;
        $this->pdf->SetXY($x,$y);
        $this->pdf->Cell($al,0,$al,0,'C');
  

        // New Record must reset variables x e keep going with y
        $x=1;
        $y=$y+3;

        //Controllo se sono arrivato a fondo pagina e nel caso aggiungo una pagina
        if ($y > 27) {
            //echo 'NUOVA PAGINA';
            $this->pdf->addPage();
            $y=5;
            
        }
        
    }
    
    #
    //%A - Nome completo del giorno della settimana in accordo con i parametri locali
    //%b - Nome del mese abbreviato in accordo con i parametri locali
    //%B - Nome completo del mese in accordo con i parametri locali
    $data=date(strftime ("%A %b %Y"));
    $file_name='test';
    $this->pdf->Output($file_name.'.pdf','I');


    
}
}
?>