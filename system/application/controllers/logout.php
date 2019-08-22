<?php

class logout extends Controller {

	function Welcome()
	{
		parent::Controller();		
	}
	function index()
	{
	 	$session_id = $this->session->userdata('userid');
	 	$this->db->update('user', array('Active' =>'T'), array('Id' => $session_id));
	 	$this->session->sess_destroy();
		redirect("welcome");
	}
}