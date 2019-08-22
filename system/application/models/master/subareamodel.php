<?php
class subareamodel extends Model {
	
    function __construct(){
        parent::Model();
    }

    function getSubareaList($num, $offset,$id,$with)
	{
	 	if($offset !=''){
			$offset = $offset;
		}            
        else{
        	$offset = 0;
        }
		$clause="";
		if($id!=""){
			$clause = " where $with like '%$id%'";
		}
    	$sql = "select KdSubArea,sub.Keterangan as NamaSub,dive.Keterangan as NamaArea from(
					SELECT KdSubArea, Keterangan,KdArea 
					FROM subarea $clause order by KdSubArea  Limit $offset,$num
				) as sub
				left join
				(
					select KdArea, Keterangan from area
				) as dive
				on dive.KdArea = sub.KdArea";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_subarea_row($id,$with){
     	$clause="";
     	if($id!=''){
			$clause = " where $with like '%$id%'";
		}
		$sql = "SELECT KdSubArea FROM subarea $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
	
	function getMaster(){
    	$sql = "SELECT KdArea,Keterangan from area order by KdArea";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function getDetail($id){
    	$sql = "SELECT KdSubArea,Keterangan,KdArea from subarea Where KdSubArea='$id'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }
   
    function get_id($id){
		$sql = "SELECT KdSubArea FROM subarea Where KdSubArea='$id'";
		$query = $this->db->query($sql);
		$num = $query->num_rows();
		$query->free_result();
		return $num;
	}
}
?>