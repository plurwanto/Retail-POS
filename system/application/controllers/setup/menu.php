<?php
class menu extends Controller {
    
    function __construct(){
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('setup/addmenumodel');
    }
    
    function index(){
     	$mylib = new globallib();
    	$sign  = $mylib->getAllowList("all");
    	if($sign=="Y")
		{
		 	$segs = $this->uri->segment_array();
  		    $arr  = "index.php/".$segs[1]."/".$segs[2]."/";
		 	$data['link'] = $sign = $mylib->restrictLink($arr);  
	        $this->load->view('setup/menu/main_menu', $data);
	    }
		else{
			$this->load->view('denied');
		}
    }
    function addroot()
	{
		$mylib = new globallib();
    	$sign  = $mylib->getAllowList("add");
    	if($sign=="Y")
		{
		 	$data['nama']	= "";
			$data['url'] 	= "";
			$data['menu']	= "";
			$data['msg'] 	= "";
		 	$data['root']	= $this->addmenumodel->getRoot();
		 	$this->load->view('setup/menu/add_new_root', $data);
		}
		else{
			$this->load->view('denied');
		}
	}
	function save_new_root()
	{
		$nama 	  = trim($this->input->post("nama"));
		$url 	  = trim($this->input->post("url"));
		$after	  = $this->input->post("menu");		
		$numDobel = $this->addmenumodel->cekDobel($nama);
		if($numDobel==0)
		{
		 	$compliment  = $this->addmenumodel->getCompliment($after);
		 	$getAllLevel = $this->addmenumodel->getLevel();
		 	$newUL = "";
			if($url=='')
			{
			 	$newUl = (int)$compliment->JmlUl + 1;
				$newUL = "ddsubmenu".$newUl;
			}
			if($compliment->JmlRoot==$compliment->urutan)  //menu baru berada di urutan paling bawah
			{
			 	$urutanMenu = (int)$compliment->urutan + 1;
			}
			else
			{
			 	$menuLain 	= $this->addmenumodel->getRoot();
				if($after=="") //menu baru berada di urutan paling atas
				{					
					for($a=0;$a<count($menuLain);$a++)
					{
					 	$urutanMenu = 1;
					 	$UrutanBaru = (int)$menuLain[$a]['urutan'] + 1;
					 	unset($data);
					 	$data = array(
					 		'urutan' =>$UrutanBaru
					 	);
						$this->db->update('menu', $data, array('nama' => $menuLain[$a]['nama']));
					}
				}
				else	//menu disisipkan di antara menu lama
				{
					$getUrutan  = $this->addmenumodel->UrutanMenu($after);
					$urutanMenu = $getUrutan->urutan + 1;					
					for($a=0;$a<count($menuLain);$a++)
					{
					 	if($menuLain[$a]['urutan']>$getUrutan->urutan)
					 	{
					 	 	$UrutanBaru = (int)$menuLain[$a]['urutan'] +1;
							unset($data);
						 	$data = array(
						 		'urutan' =>$UrutanBaru
						 	);
							$this->db->update('menu', $data, array('nama' => $menuLain[$a]['nama']));
						}
					}
				}
			}
			for($a=0;$a<count($getAllLevel);$a++)
			{
				unset($data);
				$data = array(
	               'nama' => $nama ,
	               'ulid' => $newUL ,
	               'root' => '1',
	               'url' => $url,
	               'urutan'=> $urutanMenu,
	               'UserLevelID' => $getAllLevel[$a]['UserLevelID'],
	               'FlagAktif' => '99',
	               'Jenis' =>''
	            );
				$this->db->insert('menu', $data);
			}
			redirect("/setup/menu/");
		}
		else
		{
			$data['nama'] = $nama;
			$data['url']  = $url;
			$data['menu'] = $after;
			$data['root'] = $this->addmenumodel->getRoot();
			$data['msg']  = "Nama Menu Sudah Ada, Gunakan Nama Lain";
			$this->load->view("setup/menu/add_new_root",$data);
		}
	}
	function delroot()
	{
		$mylib = new globallib();
    	$sign  = $mylib->getAllowList("del");
    	if($sign=="Y")
		{	
			$data['menu'] = "";
			$data['msg']  = "";
		 	$data['root'] = $this->addmenumodel->getEmptyUrl();
		 	$this->load->view('setup/menu/delete_root', $data);
		}
		else{
			$this->load->view('denied');
		}
	}
	function delete_this_root()
	{
		$menu   = $this->input->post("menu");
		$key	= $this->addmenumodel->getRequirement($menu);
		if($key->root=='1')
		{
			$number = $this->addmenumodel->FindChild("ulid",$menu);
		}
		else
		{
			$number = $this->addmenumodel->FindChild("root",$menu);
		}
		if($number==0)
		{
			$data = $this->addmenumodel->FindSibling($menu);			
			if($data->jmlSibling==$data->urutan) //urutan paling akhir
			{
				$this->db->delete('menu',array("nama" => $menu));
			}
			else
			{
				$menulain = $this->addmenumodel->SiblingName($menu,$data->urutan);
				for($a=0;$a<count($menulain);$a++)
				{
					$UrutanBaru = (int)$menulain[$a]['urutan']- 1;
					unset($data);
				 	$data = array(
				 		'urutan' =>$UrutanBaru
				 	);
					$this->db->update('menu', $data, array('nama' => $menulain[$a]['nama']));					
				}
				$this->db->delete('menu',array("nama" => $menu));
			}
			redirect("/setup/menu/");
		}
		else
		{
			$data['menu'] = $menu;
			$data['root'] = $this->addmenumodel->getEmptyUrl();
			$data['msg']  = "Menu / Root Tidak Bisa Didelete Karena Masih Memiliki Anak Menu";
			$this->load->view("setup/menu/delete_root",$data);
		}
	}
	function addsubs()
	{
		$mylib 	= new globallib();
    	$sign 	= $mylib->getAllowList("add");
    	if($sign=="Y")
		{	
		 	$data['nama'] 		= "";
			$data['url'] 		= "";
			$data['menu'] 		= "";
			$data['msg'] 		= "";
			$data['menuafter'] 	= "";
			$data['jenis1']		= "";
			$data['jenis']		= array("M"=>"Master","T"=>"Transaksi",""=>"Lainnya");
		 	$data['root']		= $this->addmenumodel->getEmptyUrl();
		 	$this->load->view('setup/menu/add_new_sub', $data);
		}
		else{
			$this->load->view('denied');
		}
	}
	function getSubMenuSibling()
	{
	 	$menu 	= $this->input->post('menu');
	 	$key	= $this->addmenumodel->getRequirement($menu);
		if($key->root=='1')
		{
			$data = $this->addmenumodel->getSibling("root",$key->ulid);
		}
		else
		{
			$data = $this->addmenumodel->getSibling("root",$menu);
		}
		for($a=0;$a<count($data);$a++)
		{
			echo "<option value='".$data[$a]['nama']."'>".$data[$a]['nama']."</option>";
		}
	}	
	function save_new_sub()
	{
		$nama 	  = trim($this->input->post("nama"));
		$url 	  = trim($this->input->post("url"));
		$rootmenu = $this->input->post("rootmenu");
		$menu 	  = $this->input->post("menu");
		$jenis 	  = $this->input->post("jenis");
		$numDobel = $this->addmenumodel->cekDobel($nama);
		if($numDobel==0)
		{
		 	$data 		 = $this->addmenumodel->FindSibling($menu);
			$realRoot 	 = $this->addmenumodel->getRequirement($rootmenu);
		 	$getAllLevel = $this->addmenumodel->getLevel();
			if($realRoot->root==1)
			{
				$rootmenu = $realRoot->ulid;
			}
		 	if($data->jmlSibling==$data->urutan) //menu ditaro di paling belakang
		 	{				
				$urutanMenu= (int)$data->urutan + 1;
			}
			else
			{
			 	$key	= $this->addmenumodel->getRequirement($rootmenu);
			 	if($key->root=='1')
				{
					$menuLain = $this->addmenumodel->getSibling("root",$key->ulid);
				}
				else
				{
					$menuLain = $this->addmenumodel->getSibling("root",$rootmenu);				
				}
				if($menu=="") //menu baru berada di urutan paling atas
				{				 	
					for($a=0;$a<count($menuLain);$a++)
					{
					 	$urutanMenu = 1;
					 	$UrutanBaru = (int)$menuLain[$a]['urutan'] + 1;
					 	unset($data);
					 	$data = array(
					 		'urutan' =>$UrutanBaru
					 	);					 	
						$this->db->update('menu', $data, array('nama' => $menuLain[$a]['nama']));
					}
				}
				else	//menu disisipkan di antara menu lama
				{
					$getUrutan  = $this->addmenumodel->UrutanMenu($menu);
					$urutanMenu = $getUrutan->urutan + 1;					
					for($a=0;$a<count($menuLain);$a++)
					{
					 	if($menuLain[$a]['urutan']>$getUrutan->urutan)
					 	{
					 	 	$UrutanBaru = (int)$menuLain[$a]['urutan'] +1;
							unset($data);
						 	$data = array(
						 		'urutan' =>$UrutanBaru
						 	);						
							$this->db->update('menu', $data, array('nama' => $menuLain[$a]['nama']));
						}
					}
				}
			}
			
			for($a=0;$a<count($getAllLevel);$a++)
			{
				unset($data);
				unset($data1);
				$data = array(
	               'nama' => $nama ,
	               'ulid' => "" ,
	               'root' => $rootmenu,
	               'url' => $url,
	               'urutan'=> $urutanMenu,
	               'UserLevelID' => $getAllLevel[$a]['UserLevelID'],
	               'FlagAktif' => '99',
	               'Jenis' => $jenis
	            );
	            $data1 = array(
	               'userlevelid' => $getAllLevel[$a]['UserLevelID'] ,
	               'tablename' => $nama ,
	               'add' => 'Y',
	               'edit' => 'Y',
	               'delete'=> 'Y',
	               'view' =>'Y'
	            );
				$this->db->insert('menu', $data);
				$this->db->insert('userlevelpermissions', $data1);
			}
			redirect("/setup/menu/");
		}
		else
		{
			$data['nama'] 		= $nama;
			$data['url'] 		= $url;
			$data['menu'] 		= $rootmenu;
			$data['msg'] 		= "Nama Menu Sudah Ada, Gunakan Nama Lain";
			$data['menuafter'] 	= $menu;
			$data['jenis']		= array("M"=>"Master","T"=>"Transaksi",""=>"Lainnya");
			$data['jenis1']		= $jenis;
		 	$data['root']		= $this->addmenumodel->getEmptyUrl();
		 	$key	= $this->addmenumodel->getRequirement($rootmenu);
			if($key->root=='1')
			{
				$data['anak'] = $this->addmenumodel->getSibling("root",$key->ulid);
			}
			else
			{
				$data['anak'] = $this->addmenumodel->getSibling("root",$rootmenu);				
			}
		 	$this->load->view('setup/menu/add_new_sub', $data);
		}
	}
	function delsubs()
	{
		$mylib = new globallib();
    	$sign  = $mylib->getAllowList("del");
    	if($sign=="Y")
		{	
			$data['menu'] = "";
		 	$data['root'] = $this->addmenumodel->getMenuItem();
		 	$this->load->view('setup/menu/delete_sub', $data);
		}
		else{
			$this->load->view('denied');
		}
	}
	function delete_this_subs()
	{
		$menu   = $this->input->post("menu");
		$data = $this->addmenumodel->FindSibling($menu);			
		if($data->jmlSibling==$data->urutan) //urutan paling akhir
		{
			$this->db->delete('menu',array("nama" => $menu));
		}
		else
		{
			$menulain = $this->addmenumodel->SiblingName($menu,$data->urutan);
			for($a=0;$a<count($menulain);$a++)
			{
				$UrutanBaru = (int)$menulain[$a]['urutan']- 1;
				unset($data);
			 	$data = array(
			 		'urutan' => $UrutanBaru
			 	);
				$this->db->update('menu', $data, array('nama' => $menulain[$a]['nama']));					
			}
			$this->db->delete('menu',array("nama" => $menu));
			$this->db->delete('userlevelpermissions',array("tablename" => $menu));
		}
		redirect("/setup/menu/");		
	}
	function editmenu()
	{
		$mylib = new globallib();
    	$sign  = $mylib->getAllowList("edit");
    	if($sign=="Y")
		{
		 	$data['allmenu']   = $this->addmenumodel->editAllmenu();
		 	$data['allselect'] = "";
		 	$data['nama']	   = "";
			$data['url'] 	   = "";
			$data['menu']	   = "";
			$data['msg'] 	   = "";
			$data["aftermenu"] = "";
		 	$this->load->view('setup/menu/editmenu', $data);		 	
		}
		else{
			$this->load->view('denied');
		}
	}
	function find_url()
	{
		$menu = $this->input->post("menu");
		$data = $this->addmenumodel->find_address($menu);
		echo $data->url;
	}
	
	function GetRootSibling()
	{
		$menu 	= $this->input->post('menu');
	 	$key	= $this->addmenumodel->getRequirement($menu);
		if($key->root=='1')
		{
			$data = $this->addmenumodel->getSibling("root",'1');
		}
		else
		{
			$data = $this->addmenumodel->getSibling("root",$key->root);
		}
		
		for($a=0;$a<count($data);$a++)
		{
		 	if($data[$a]['nama']!=$menu)
		 	{
				echo "<option value='".$data[$a]['nama']."'>".$data[$a]['nama']."</option>";
			}
			
		}
	}
	
	function save_menu()
	{
		$nama 	  = trim($this->input->post("nama"));
		$url 	  = trim($this->input->post("url"));
		$rootmenu = $this->input->post("allmenu");
		$menu 	  = $this->input->post("menu");
		$numDobel = $this->addmenumodel->cekDobel($nama);
		if($numDobel==0||$nama==$rootmenu)
		{
		 	$data 		 = $this->addmenumodel->FindSibling($rootmenu);		 	
		 	$realRoot 	 = $this->addmenumodel->getRequirement($rootmenu);
		 	$getAllLevel = $this->addmenumodel->getLevel();
		 	if($realRoot->root=='1')
			{
				$aftermenu = $this->addmenumodel->getSibling("root",'1');
			}
			else
			{
				$aftermenu = $this->addmenumodel->getSibling("root",$realRoot->root);
			}
			if($menu!="tetap"){
				if($menu=="")  //pindah ke baris pertama
				{
				 	$UrutanMenu = 1;
					for($a=0;$a<count($aftermenu);$a++)
					{
					 	if($aftermenu[$a]['nama']!=$rootmenu)
						{
							unset($str);
							$urutanBaru = (int)$aftermenu[$a]['urutan'];
						 	if($aftermenu[$a]['urutan'] < $data->urutan)
						 	{
								$urutanBaru = (int)$aftermenu[$a]['urutan']+ 1;
							}							
						//	$this->db->update('menu', array('urutan' => $urutanBaru ), array('nama' => $aftermenu[$a]['nama'] ));
							print_r($str);
						}
					}
				}
				else
				{
					$UrutanPindah = $this->addmenumodel->UrutanMenu($menu);
					$UrutanMenu   = (int)$UrutanPindah->urutan + 1;					
					if($UrutanMenu>$data->urutan)
					{
						$batasAtas  = $data->urutan;
						$batasBawah = $UrutanMenu;
					}
					else
					{
						$batasAtas  = $UrutanMenu;
						$batasBawah = $data->urutan;
					}
					for($a=0;$a<count($aftermenu);$a++)
					{
					 	if($aftermenu[$a]['nama']!=$rootmenu)
						{
						 	if($a>=$batasAtas&&$a<$batasBawah)
							{						 	
								unset($str);								
						 	 	$urutanBaru = (int)$aftermenu[$a]['urutan'] - 1;
								$str = array(
									'urutan' => $urutanBaru,
									'nama'   => $aftermenu[$a]['nama']
								);
							//	$this->db->update('menu', $str, array('nama' => $aftermenu[$a]['nama'] ));
								print_r($str);
							}
						}
					}				
				}
			}		
			else
			{
				$UrutanMenu = $data->urutan;
			}
			$str = array(
		 		'urutan' => $UrutanMenu,
		 		'nama'   => $nama
		 	);
		 	print_r($str);
		//	$this->db->update('menu', $str, array('nama' => $rootmenu));
		}
		else
		{
			$data['nama'] 		= $nama;
			$data['url'] 		= $url;
			$data['msg'] 		= "Nama Menu Sudah Ada, Gunakan Nama Lain";
		 	$data['allmenu']	= $this->addmenumodel->editAllmenu();
		 	$data['allselect']	= $rootmenu;
		 	$key				= $this->addmenumodel->getRequirement($rootmenu);
		 	$str				= "<option value=''>Baris Baru</option>";
			if($key->root=='1')
			{
				$aftermenu = $this->addmenumodel->getSibling("root",'1');
			}
			else
			{
				$aftermenu = $this->addmenumodel->getSibling("root",$key->root);
			}
			
			for($a=0;$a<count($aftermenu);$a++)
			{
			 	$select = "";
			 	if($aftermenu[$a]['nama']==$menu)
			 	{
					$select = "selected";
				}
				$str .= "<option ".$select." value='".$aftermenu[$a]['nama']."'>".$aftermenu[$a]['nama']."</option>";
			}
			$data["aftermenu"] = $str;
		 	$this->load->view('setup/menu/editmenu', $data);
		}
	}
}
?>