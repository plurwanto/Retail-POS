<?php
class kartumodel extends Model {
	
    function __construct(){
        parent::Model();
    }

    function getkartuList($num, $offset,$id,$with)
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
    	$sql = "SELECT KdKartu, Keterangan FROM kartu $clause order by KdKartu Limit $offset,$num";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_kartu_row($id,$with){
     	$clause="";
     	if($id!=''){
			$clause = " where $with like '%$id%'";
		}
		$sql = "SELECT KdKartu FROM kartu $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
    
    function getDetail($id){
    	$sql = "SELECT KdKartu,Keterangan from kartu Where KdKartu='$id'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }
    
    function get_id($id){
		$sql = "SELECT KdKartu FROM kartu Where KdKartu='$id'";
		$query = $this->db->query($sql);
		$num = $query->num_rows();
		$query->free_result();
		return $num;
	}
}
?>