<?php
class userpermissionmodel extends Model {
	
    function __construct(){
        parent::Model();
    }

    function getuserpermissionList($id)
	{
    	$sql = "SELECT * FROM userlevelpermissions where userlevelid='$id' order by tablename";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    function findNum($id)
    {
		$sql = "select (select count(`add`) from userlevelpermissions where userlevelid='-1' and `add`='Y') as yesAdd,
(select count(`add`) from userlevelpermissions where userlevelid='-1') as AllYes,
(select count(`edit`) from userlevelpermissions where userlevelid='-1' and `edit`='Y') as yesEdit,
(select count(`edit`) from userlevelpermissions where userlevelid='-1') as AllEdit,
(select count(`delete`) from userlevelpermissions where userlevelid='-1' and `delete`='Y') as yesDel,
(select count(`delete`) from userlevelpermissions where userlevelid='-1') as AllDel,
(select count(`view`) from userlevelpermissions where userlevelid='-1' and `view`='Y') as yesView,
(select count(`view`) from userlevelpermissions where userlevelid='-1') as AllView";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
	}
	function GetMenu($id)
	{
		$sql = "select * from menu where url='' and UserLevelId='$id' order by ulid;";
		$qry = $this->db->query($sql);
		$row = $qry->result_array();
		$qry->free_result();
		return $row;
	}
	function getSubMenu($root,$id)
	{
		$sql = "select (select count(flagaktif) from menu where root='$root' and UserLevelId='$id' and flagaktif='99') as aktif, (select count(flagaktif) from menu where root='$root' and UserLevelId='$id') as total,(select flagaktif from menu where nama='$root' and UserLevelId='$id') as flagmenu;;";
		$qry = $this->db->query($sql);
		$row = $qry->row();
		$qry->free_result();
		return $row;
	}
	function getSubMenu2($root,$id)
	{
		$sql = "select (select count(flagaktif) from menu where root='$root' and UserLevelId='$id' and flagaktif='99') as aktif, (select count(flagaktif) from menu where root='$root' and UserLevelId='$id') as total,(select flagaktif from menu where ulid='$root' and UserLevelId='$id') as flagmenu;;";
		$qry = $this->db->query($sql);
		$row = $qry->row();
		$qry->free_result();
		return $row;
	}
	function getUserEditPermission($id)
	{
		$sql="select * from userlevelpermissions where tablename= 'User Permissions' and userlevelid='$id';";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
	}
}
?>