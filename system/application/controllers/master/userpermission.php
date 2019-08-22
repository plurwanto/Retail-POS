<?php
class userpermission extends Controller {
    
    function __construct(){
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/userpermissionmodel');
    }  
  
    function permission_list()
	{
	 	$session_level 	= $this->session->userdata('userlevel');
	 	$permit			= $this->userpermissionmodel->getUserEditPermission($session_level,"all");
	 	if($permit->view=="Y"||$permit->edit=="Y"||$permit->delete=="Y"||$permit->add=="Y")
		{
		 	$id 						= $this->uri->segment(4);
			$data['userpermissiondata'] = $this->userpermissionmodel->getuserpermissionList($id);
			$num 						= $this->userpermissionmodel->findNum($id);
			$data['cekaddAll'] 	= "";
			$data['cekeditAll'] = "";
			$data['cekdelAll']	= "";
			$data['cekviewAll']	= "";
			if($num->yesAdd==$num->AllYes)	{ $data['cekaddAll']="checked";  }
			if($num->yesEdit==$num->AllEdit){ $data['cekeditAll']="checked"; }
			if($num->yesDel==$num->AllDel)	{ $data['cekdelAll']="checked";  }
			if($num->yesView==$num->AllView){ $data['cekviewAll']="checked"; }
			$data['id']	 =  $id;
	        $this->load->view('master/userpermission/viewuserpermission', $data);
        }
    	else
    	{
			$this->load->view('denied');
		}
    }

    function save_permission(){
    	$add 	= $this->input->post('addhidden');
    	$edit 	= $this->input->post('edithidden');
    	$del 	= $this->input->post('delhidden');
    	$view 	= $this->input->post('viewhidden');
    	$id 	= $this->input->post('id');
    	$nama 	= explode("|",$this->input->post('namahidden'));
		for($a=0;$a<strlen($add);$a++){
		 	unset($data);
		 	$data = array(
    		  'add'		=> $add{$a},
    		  'edit'	=> $edit{$a},
    		  'delete'	=> $del{$a},
    		  'view'	=> $view{$a}
			);
			$this->db->update('userlevelpermissions', $data, array('tablename' => $nama[$a+1],'userlevelid'=>$id));
			if ($add{$a}=="T"&&$edit{$a}=="T"&&$del{$a}=="T"&&$view{$a}=="T")
			{
				$flagaktif = "-99";
			}
			else
			{
				$flagaktif = "99";
			}
			$this->db->update('menu', array('FlagAktif' =>$flagaktif), array('nama' => $nama[$a+1],'UserLevelID'=>$id));			
		}
		$this->scanMenu($id);
		redirect("/master/userlevel/");
    }
    
    function scanMenu($id){
		$menu = $this->userpermissionmodel->GetMenu($id);
		for($a=0;$a<count($menu);$a++)
		{
			if($menu[$a]['root']!='1')
			{
				$submenu = $this->userpermissionmodel->getSubMenu($menu[$a]['nama'],$id);			
				if($submenu->aktif==$submenu->total&&$submenu->flagmenu=='-99'||($submenu->aktif!=$submenu->total&&$submenu->flagmenu=='-99'&&$submenu->aktif!=0))
				{
					$this->db->update('menu', array('FlagAktif' =>'99'), array('nama' => $menu[$a]['nama'],'UserLevelID'=>$id));
				}
				else if($submenu->aktif==0&&$submenu->flagmenu=='99')
				{
					$this->db->update('menu', array('FlagAktif' =>'-99'), array('nama' => $menu[$a]['nama'],'UserLevelID'=>$id));
				}
			}
			else
			{
				$submenu = $this->userpermissionmodel->getSubMenu2($menu[$a]['ulid'],$id);
				if($submenu->aktif==$submenu->total&&$submenu->flagmenu=='-99'||($submenu->aktif!=$submenu->total&&$submenu->flagmenu=='-99'&&$submenu->aktif!=0))
				{
					$this->db->update('menu', array('FlagAktif' =>'99'), array('ulid' => $menu[$a]['ulid'],'UserLevelID'=>$id));
				}
				else if($submenu->aktif==0&&$submenu->flagmenu=='99')
				{
					$this->db->update('menu', array('FlagAktif' =>'-99'), array('ulid' => $menu[$a]['ulid'],'UserLevelID'=>$id));
				}
			}		
		}
	}
}
?>