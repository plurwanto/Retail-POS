<?php
class subdivisimodel extends Model {
	
    function __construct(){
        parent::Model();
    }

    function getSubDivisiList($num, $offset,$id,$with)
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
    	$sql = "select KdSubDivisi,NamaSubDivisi,NamaDivisi from(
					SELECT KdSubDivisi, NamaSubDivisi,KdDivisi 
					FROM subdivisi $clause order by KdSubDivisi  Limit $offset,$num
				) as sub
				left join
				(
					select KdDivisi, NamaDivisi from divisi
				) as dive
				on dive.KdDivisi = sub.KdDivisi";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_divisi_row($id,$with){
     	$clause="";
     	if($id!=''){
			$clause = " where $with like '%$id%'";
		}
		$sql = "SELECT KdSubDivisi FROM subdivisi $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
	
	function getMaster(){
    	$sql = "SELECT KdDivisi,NamaDivisi from divisi order by KdDivisi";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function getDetail($id){
    	$sql = "SELECT KdSubDivisi,NamaSubDivisi,KdDivisi from subdivisi Where KdSubDivisi='$id'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }
   
    function get_id($id){
		$sql = "SELECT KdSubDivisi FROM subdivisi Where KdSubDivisi='$id'";
		$query = $this->db->query($sql);
		$num = $query->num_rows();
		$query->free_result();
		return $num;
	}
}
?>