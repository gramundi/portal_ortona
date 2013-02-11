<?php

class logout extends CI_Controller
 {

	function logout()
	{
		parent::__construct();
                $this->load->model('login_model','lm');
	}

	function index()
	{
            $session_id = $this->session->userdata('session_id');
            $this->lm->log_user('logout',$session_id);
            $this->session->sess_destroy();
            redirect('main');
	
        }
}
