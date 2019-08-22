<?php
class menumodel extends Model {

    function __construct(){
        parent::Model();
    }
    
    function get_root($level){
		$sql = "select * from menu where root='1' and UserLevelId='$level' order by urutan";
		$qry = $this->db->query($sql);
		$row = $qry->result_array();
		$qry->free_result();
		return $row;
    }
	
    function get_drop_down($level)
    {
	 	$sql = "select distinct ulid from menu where ulid!='' and UserLevelId='$level' order by ulid";
		$qry = $this->db->query($sql);
		$row = $qry->result_array();
		$qry->free_result();
		return $row;
				
    }

    function get_sub_menu($root,$level)
    {
		$sql = "select * from menu where root='$root' and UserLevelId='$level' order by urutan;";
		$qry = $this->db->query($sql);
		$row = $qry->result_array();
		$qry->free_result();
		return $row;	
    }
  
}
?>