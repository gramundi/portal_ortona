<?php
/*
 * Sw di Gestione Missioni
 *
 * Author:
 *	Jhonny Ramundi <http://www.jhonnynext.it>
 */
class Main extends CI_Controller {

	function Main()
	{
		parent::__construct();
                //LOAD MODEL LOGIN
                $this->load->model('login_model','lm');
                 
		// LOAD HELPERS
		$this->load->helper(array('form'));

	}

	function index()
	{
              

                
                // SET VALIDATION RULES
		$this->form_validation->set_rules('user_name', 'username', 'required');
		$this->form_validation->set_rules('user_pass', 'password', 'required');
		$this->form_validation->set_error_delimiters('<em>','</em>');

		// Control if the Login Text Area is filled

                //echo $_POST['user_name'];
		if( isset($_POST['user_name']))
		{

                        //$_POST['user_name'].'--'.$_POST['user_pass'];
                        //echo 'form validation:'.$this->form_validation->run();
                        //echo $_POST['user_name'];
			if($this->form_validation->run())
			{

                               
				//catch data from username e password textarea
                                $user_name = $this->input->post('user_name');
				$user_pass = $this->input->post('user_pass');
                                if($this->lm->check_user($user_name))
				{

                                    //echo $user_name;
                                    if ($user_name=='SUPER'){
                                        //echo $user_name;

                                        
                                        $sess_data = array(
                                            'username'  => $user_name,
                                            'id_user'=>$this->lm->get_id($user_name),
                                            'nome'=>$this->lm->get_user($user_name)
                                            );

                                        $this->session->set_userdata($sess_data);
                                        $session_id=$this->session->userdata('session_id');
                                        $this->lm->log_user('login',$session_id);
                                        redirect('welcome_portal');

                                      
                                        }
                                     if( $this->lm->check_pass($user_name,$user_pass ))
					{
                                        
                                        //Set Custom Session  Data

                                        $sess_data = array(
                                            'username'  => $user_name,
                                            'id_user'=>$this->lm->get_id($user_name),
                                             'nome'=>$this->lm->get_user($user_name));

                                        $this->session->set_userdata($sess_data);
                                        $session_id=$this->session->userdata('session_id');
                                        $this->lm->log_user('login',$session_id);
                                        
                                        //$this->lm->log_user('login',$session_id);
                                        redirect('welcome_portal');
					}
					else
					{
						//$this->session->set_flashdata('message', 'PASSWORD NON CORRETTA');
						redirect('main/index/');
					}
				}
				else
				{
					//$this->session->set_flashdata('message', 'UTENTE INESISTENTE CONTATTARE AMMINISTRATORE');
					redirect('main/index/');
				}
			}
		}
		else {
                    
                    $this->load->view('login');
                }
                                
	}

}
