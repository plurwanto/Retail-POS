<?php

class Welcome extends Controller {

	function Welcome()
	{
		parent::Controller();
		$this->load->model("loginmodel");
		$this->load->library('session');
	}
	function index()
	{
	 	$data['msg']="";
		$this->load->view('login.php',$data);
	}
	function verified(){
		$id     = trim($this->input->post('kode'));
                $passw  = md5(trim($this->input->post('nama')));
                $ip     = $_SERVER['REMOTE_ADDR'];
                $result = $this->loginmodel->loginquery($id,$passw);
                $tgl    = $this->loginmodel->get_tanggal_aplikasi();
                $sts    = "";
    	if(!empty($result)){ // buat cek ip dan aktif
                
                if($result->ip==$ip){
                    $sts ="Ok";
                }else{ // jika tidak sama, cek user masih aktif atau tidak
                    $sts = ($result->Active =="T")?"Ok":"Tidak";
                }
//echo $sts;
                if($sts=="Ok" or $id=="retail"){
    	 	$this->db->update('user', array('Active' =>'Y', 'ip'=>$ip), array('Id' => $result->Id)); //update status aktif dan ip aktif
    	 	$sessiondata = array(
                   'username'  => $id,
                   'userlevel' => $result->UserLevel,
                   'userid'    => $result->Id,
                   'Tanggal_Trans'    => $tgl->Tanggal
               );
			$this->session->set_userdata($sessiondata);
			$main = $this->loginmodel->findAddress($result->MainPage);
			
			$address = explode("/",$main->url);
			$str = "";
			for($s =1;$s<count($address);$s++)
			{
				$str = $str."/".$address[$s];
			}
			$date = date("Y-m-d H:i:s");
			$data = array
			(
				"IDUser"    => $result->Id,
				"DateLogin" => $date
			);
			$this->db->insert("log_user",$data); // insert to log login user;
			redirect($str);
		}else{
                        $data['id']="";
	 		$data['nama']="";
			$data['msg'] =  "<b>User Sedang Digunakan Atau hubungi Admin</b>";
			$this->load->view("login",$data);
                }
            }else{
		 	$data['id']="";
	 		$data['nama']="";
			$data['msg'] =  "<b>User Name Atau Password Salah</b>";
			$this->load->view("login",$data);
		}
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
