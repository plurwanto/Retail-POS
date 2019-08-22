<?php
class loginmodel extends Model {
	
    function __construct(){
        parent::Model();
    }

	function loginquery($id,$passw){
		$sql = "select count(Id) as counter,Id,UserLevel,UserName,MainPage,ip,Active from user where UserName='$id' and Password='$passw' group by Id";
		$qry = $this->db->query($sql);
		$num = $qry->row();
		$qry->free_result();
		return $num;
	}
	  function get_tanggal_aplikasi()
    {
		$sql = "select DATE_FORMAT(TglTrans,'%d-%m-%Y') as Tanggal,TglTrans from aplikasi";
		$qry = $this->db->query($sql);
		$num = $qry->row();
		$qry->free_result();
		return $num;
    }
//	function num_user($id,$passw){
//		$sql = "select Id from user where UserName='$id' and Password='$passw' group by Id";
//		$qry = $this->db->query($sql);
//		$num = $qry->num_rows();
//		$qry->free_result();
//		return $num;
//	}
	function findAddress($name)
	{
		$sql = "select url from menu where nama = '$name'";
		$qry = $this->db->query($sql);
		$num = $qry->row();
		$qry->free_result();
		return $num;
	}
}
?>