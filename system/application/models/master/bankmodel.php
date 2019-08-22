<?php
class bankmodel extends Model {
	
    function __construct(){
        parent::Model();
    }

    function getBankList($num, $offset,$id,$with)
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
    	$sql = "SELECT KdBank, NamaBank FROM bank $clause order by KdBank Limit $offset,$num";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_bank_row($id,$with){
     	$clause="";
     	if($id!=''){
			$clause = " where $with like '%$id%'";
		}
		$sql = "SELECT KdBank FROM bank $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
    
    function getDetail($id){
    	$sql = "SELECT KdBank,NamaBank from bank Where KdBank='$id'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }
    
    function get_id($id){
		$sql = "SELECT KdBank FROM bank Where KdBank='$id'";
		$query = $this->db->query($sql);
		$num = $query->num_rows();
		$query->free_result();
		return $num;
	}
}
?>