<?php
class Baranglainlain extends Controller
{
	function __construct()
	{
        parent::__construct();
		$this->load->library('globallib');
        $this->load->model('pop/baranglain_model');
    }

    function index()
	{
     	$id   = $this->input->post('stSearchingKey');
        $with = $this->input->post('searchby');
        $this->load->library('pagination');
//echo "ok";
        $config['full_tag_open']  = '<div class="pagination">';
        $config['full_tag_close'] = '</div>';
        $config['cur_tag_open']   = '<span class="current">';
        $config['cur_tag_close']  = '</span>';
        $config['per_page']       = '14';
        $config['first_link'] 	  = 'First';
        $config['last_link'] 	  = 'Last';
        $config['num_links']  	  = 2;
		$tipe                     = $this->uri->segment(4); // masuk / keluar
		$baris                    = $this->uri->segment(4);
		$page                     = $this->uri->segment(5);
		$config['base_url']       = base_url()."index.php/pop/baranglainlain/index/".$baris."/";
		$config['uri_segment']    = 7;
		$flag1					  = "";
		if($with!=""){//echo "ok3";
                        if($id!=""&&$with!=""){
				$config['base_url']     = base_url().'index.php/pop/baranglainlain/index/'.$baris."/".$with."/".$id."/";
				$page 			= $this->uri->segment(7);
				$config['uri_segment']  = 7;
			}
		 	else{
				$page ="";
			}
		}
		else{//echo $this->uri->segment(4);
			if($this->uri->segment(6)!=""){
				$with 					= $this->uri->segment(5);
			 	$id 					= $this->uri->segment(6);
			 	$config['base_url']     = base_url().'index.php/pop/baranglainlain/index/'.$baris."/".$with."/".$id."/";
				$page 			= $this->uri->segment(7);
				$config['uri_segment']  = 7;
			}
		}
//echo $with;
        $config['total_rows']	= $this->baranglain_model->num_barang_row(addslashes($id),$with);
        $data['barangdata'] 	= $this->baranglain_model->getbarangList($config['per_page'],$page,addslashes($id),$with);
        $data['row_no']		= $baris;
        $this->pagination->initialize($config);

        $this->load->view('pop/baranglist', $data);
    }
}
?>