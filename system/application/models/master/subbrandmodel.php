<?php
class subbrandmodel extends Model {
	
    function __construct(){
        parent::Model();
    }

    function getSubBrandList($num, $offset,$id,$with)
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
    	$sql = "select KdSubBrand,NamaSubBrand,NamaBrand from(
					SELECT KdSubBrand, NamaSubBrand,KdBrand 
					FROM subbrand $clause order by KdSubBrand  Limit $offset,$num
				) as sub
				left join
				(
					select KdBrand, NamaBrand from brand
				) as dive
				on dive.KdBrand = sub.KdBrand";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_subbrand_row($id,$with){
     	$clause="";
     	if($id!=''){
			$clause = " where $with like '%$id%'";
		}
		$sql = "SELECT KdSubBrand FROM subbrand $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
	
	function getMaster(){
    	$sql = "SELECT KdBrand,NamaBrand from brand order by KdBrand";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function getDetail($id){
    	$sql = "SELECT KdSubBrand,NamaSubBrand,KdBrand from subbrand Where KdSubBrand='$id'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }
   
    function get_id($id){
		$sql = "SELECT KdSubBrand FROM subbrand Where KdSubBrand='$id'";
		$query = $this->db->query($sql);
		$num = $query->num_rows();
		$query->free_result();
		return $num;
	}
}
?>