<?php
class departemenmodel extends Model {
	
    function __construct(){
        parent::Model();
    }

    function getDepartemenList($num, $offset,$id,$with)
	{
	 	if($offset !='')
            $offset = $offset;
        else
		{
        	$offset = 0;
        }
        $clause="";
		if($id!=""){
			$clause = " where $with like '%$id%'";
		}
    	$sql = "SELECT KdDepartemen, Keterangan FROM departemen $clause order by KdDepartemen Limit $offset,$num";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_departemen_row($id,$with){
     	$clause="";
     	if($id!=''){
			$clause = " where $with like '%$id%'";
		}
		$sql = "SELECT KdDepartemen FROM departemen $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
    
    function getDetail($id){
    	$sql = "SELECT KdDepartemen,Keterangan from departemen Where KdDepartemen='$id'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }
    
    function get_id($id){
		$sql = "SELECT KdDepartemen FROM departemen Where KdDepartemen='$id'";
		$query = $this->db->query($sql);
		$num = $query->num_rows();
		$query->free_result();
		return $num;
	}
}
?>