<?php
class del_temp extends Controller {
	function __construct(){
        parent::__construct();
        $this->load->library('globallib');
    }

    function index(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("all");
    	if($sign=="Y")
		{
	        $data['track'] = $mylib->print_track();
			$data['msg'] = "";
	        $this->load->view('setup/hapus_temp_sales', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }
	function doThis()
	{
		$mylib = new globallib();
                $nm    = $mylib->getUser();
                $this->db->delete('sales_temp',array('kasir' => $nm));
		$data['track'] = $mylib->print_track();
		$data['msg'] = "Temporary berhasil di Hapus";
		$this->load->view('setup/hapus_temp_sales', $data);
	}
}
?>