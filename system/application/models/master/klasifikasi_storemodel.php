<?php
class klasifikasi_storemodel extends Model {
	
    function __construct(){
        parent::Model();
    }

    function getklasifikasi_storeList($num, $offset,$id,$with)
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
    	$sql = "SELECT KdKlasifikasi, Keterangan FROM klasifikasi_store $clause order by KdKlasifikasi Limit $offset,$num";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_klasifikasi_store_row($id,$with){
     	$clause="";
     	if($id!=''){
			$clause = " where $with like '%$id%'";
		}
		$sql = "SELECT KdKlasifikasi FROM klasifikasi_store $clause ";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
    
    function getDetail($id){
    	$sql = "SELECT KdKlasifikasi,Keterangan from klasifikasi_store Where KdKlasifikasi='$id'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }
    
    function get_id($id){
		$sql = "SELECT KdKlasifikasi FROM klasifikasi_store Where KdKlasifikasi='$id'";
		$query = $this->db->query($sql);
		$num = $query->num_rows();
		$query->free_result();
		return $num;
	}
}
?>