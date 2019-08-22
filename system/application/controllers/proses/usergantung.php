<?php
class Usergantung extends Controller
{
    function __construct()
	{
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('proses/Usergantung_model');
    }
    
    function index()
	{
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("all");
		
		if($sign == "Y")
		{	
			$id = $this->session->userdata('userid');
			$data['detail']			= $this->Usergantung_model->getuserList($id);
//			$data['transaksi']		= $this->Usergantung_model->transaksi();
					
		 	$this->load->view('proses/list_gantung',$data);
	    }
		else
		{
			$this->load->view('denied');
		}
    }
    
   function hapus()
   {

       $id			= $this->uri->segment(4);
       $this->db->update('user',array("Active"=>"T"),array("Id"=>$id));
       redirect('/proses/usergantung/');
   }
    
	
}
?>