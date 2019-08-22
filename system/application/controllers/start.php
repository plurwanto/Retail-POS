<?php
class start extends Controller {

	function Welcome()
	{
		parent::Controller();
		$this->load->library('session');
	}
	function index(){
		if($this->session->userdata('userlevel'))
		{
                    $data['content'] = "indexstart";
            //$data['konter'] = $arr;//"{label: 'bg juncti', data: 24.5, color: '#68BC31'}";
            $this->load->view("tampilan_home", $data);
		//	$this->load->view("indexstart");
		}
		else
		{
			$data['msg']="";
			$this->load->view('login.php',$data);
		}
	}
}
?>