<?php
class kelas_produkmodel extends Model {
	
    function __construct(){
        parent::Model();
    }

    function getKelasList($num, $offset,$id,$with)
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
    	$sql = "SELECT KdKelas, NamaKelas FROM kelasproduk $clause order by KdKelas Limit $offset,$num";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_kelas_produk_row($id,$with){
     	$clause="";
     	if($id!=''){
			$clause = " where $with like '%$id%'";
		}
		$sql = "SELECT KdKelas FROM kelasproduk $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
    
    function getDetail($id){
    	$sql = "SELECT KdKelas,NamaKelas from kelasproduk Where KdKelas='$id'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }
    
    function get_id($id){
		$sql = "SELECT KdKelas FROM kelasproduk Where KdKelas='$id'";
		$query = $this->db->query($sql);
		$num = $query->num_rows();
		$query->free_result();
		return $num;
	}
}
?>