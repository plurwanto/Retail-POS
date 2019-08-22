<?php
class addmenumodel extends Model {
	
    function __construct()
	{
        parent::Model();
    }
    function getRoot()
	{
	 	$sql = "select distinct nama,urutan from menu where root='1' order by urutan";
	 	$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
	function getCompliment($newmenu)
	{
		$sql = "select (
			select count(distinct nama) from menu where root='1') as JmlRoot,
			(
			select count(distinct ulid) from menu where ulid!=''
			) as JmlUl,
			(
			select distinct urutan from menu where nama='$newmenu'
			) as urutan";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
	}
	function cekDobel($nama)
	{
		$sql = "select distinct nama from menu where nama='$nama'";
		$qry = $this->db->query($sql);
        $row = $qry->num_rows();
        $qry->free_result();
        return $row;
	}
	function getLevel()
	{
		$sql = "select UserLevelID from userlevels";
		$qry = $this->db->query($sql);
		$row = $qry->result_array($sql);
		$qry->free_result();
		return $row;
	}
	
	function UrutanMenu($menunya)
	{
		$sql = "select distinct urutan from menu where nama='$menunya'";
		$qry = $this->db->query($sql);
		$row = $qry->row();
		$qry->free_result();
		return $row;
	}
	function getEmptyUrl()
	{
		$sql = "select distinct nama,urutan,ulid,root from menu where url='' order by nama";
		$qry = $this->db->query($sql);
		$row = $qry->result_array($sql);
		$qry->free_result();
		return $row;
	}
	function FindSibling($menu)
	{
		$sql = "select (
		select count(distinct nama) from menu where root in (select root from menu where nama='$menu') 
		)
		as jmlSibling,
		( select distinct urutan from menu where nama='$menu') as urutan;";
		$qry = $this->db->query($sql);
		$row = $qry->row();
		$qry->free_result();
		return $row;
	}
	function SiblingName($menu,$urutan)
	{
		$sql = "select distinct nama,urutan from menu 
				where root in (select root from menu where nama='$menu')
				and urutan > $urutan
				order by urutan";
		$qry = $this->db->query($sql);
		$row = $qry->result_array();
		$qry->free_result();
		return $row;
	}
	function FindChild($field,$menu)
	{
		$sql = "select distinct nama,urutan from menu where $field='$menu'";
		$qry = $this->db->query($sql);
		$row = $qry->num_rows();
		$qry->free_result();
		return $row;
	}
	function getSibling($field,$menu)
	{
		$sql = "select distinct nama,urutan from menu where $field='$menu' 
				order by urutan";
		$qry = $this->db->query($sql);
		$row = $qry->result_array();
		$qry->free_result();
		return $row;
	}
	function getRequirement($menu)
	{
		$sql = "select distinct root,ulid from menu where nama='$menu'";
		$qry = $this->db->query($sql);
		$row = $qry->row();
		$qry->free_result();
		return $row;
	}
	function getMenuItem()
	{
		$sql = "select distinct nama from menu where url!='' and nama!='Home' and nama!='Logout' order by nama";
		$qry = $this->db->query($sql);
		$row = $qry->result_array();
		$qry->free_result();
		return $row;
	}
	function editAllmenu()
	{
		$sql = "select distinct nama  from menu order by nama";
		$qry = $this->db->query($sql);
		$row = $qry->result_array();
		$qry->free_result();
		return $row;
	}
	function find_address($menu)
	{
		$sql = "select distinct url from menu where nama='$menu'";
		$qry = $this->db->query($sql);
		$row = $qry->row();
		$qry->free_result();
		return $row;
	}
}
?>