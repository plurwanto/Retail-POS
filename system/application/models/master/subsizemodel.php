<?php
class subsizemodel extends Model {
	
    function __construct(){
        parent::Model();
    }

    function getSubsizeList($num, $offset,$id,$with)
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
    	$sql = "select KdSubSize,Ukuran,NumericSize,NamaSize from(
					SELECT KdSubSize,Ukuran,NumericSize,KdSize 
					FROM subsize $clause order by KdSubSize  Limit $offset,$num
				) as sub
				left join
				(
					select KdSize, NamaSize from size
				) as dive
				on dive.KdSize = sub.KdSize";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_subsize_row($id,$with){
     	$clause="";
     	if($id!=''){
			$clause = " where $with like '%$id%'";
		}
		$sql = "SELECT KdSubSize FROM subsize $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
	
	function getMaster(){
    	$sql = "SELECT KdSize,NamaSize from size order by KdSize";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function getDetail($id){
    	$sql = "SELECT KdSubSize,Ukuran,NumericSize,KdSize from subsize Where KdSubSize='$id'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }
   
    function get_id($id){
		$sql = "SELECT KdSubSize FROM subsize Where KdSubSize='$id'";
		$query = $this->db->query($sql);
		$num = $query->num_rows();
		$query->free_result();
		return $num;
	}
}
?>