<?php
class satuanmodel extends Model {
	
    function __construct(){
        parent::Model();
    }

    function getsatuanList($num, $offset,$id,$with)
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
    	$sql = "SELECT NamaSatuan, keterangan FROM satuan $clause order by NamaSatuan Limit $offset,$num";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_satuan_row($id,$with){
     	$clause="";
     	if($id!=''){
			$clause = " where $with like '%$id%'";
		}
		$sql = "SELECT NamaSatuan FROM satuan $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
    
    function getDetail($id){
    	$sql = "SELECT NamaSatuan,keterangan from satuan Where NamaSatuan='$id'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }
    
    function get_id($id){
		$sql = "SELECT NamaSatuan FROM satuan Where NamaSatuan='$id'";
		$query = $this->db->query($sql);
		$num = $query->num_rows();
		$query->free_result();
		return $num;
	}
}
?>