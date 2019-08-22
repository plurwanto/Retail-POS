<?php
class subkategorimodel extends Model {
	
    function __construct(){
        parent::Model();
    }

    function getSubKategoriList($num, $offset,$id,$with)
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
    	$sql = "select KdSubKategori,NamaSubKategori,NamaKategori from(
					SELECT KdSubKategori, NamaSubKategori,KdKategori 
					FROM subkategori $clause order by KdSubKategori  Limit $offset,$num
				) as sub
				left join
				(
					select KdKategori, NamaKategori from kategori
				) as dive
				on dive.KdKategori = sub.KdKategori";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_subkategori_row($id,$with){
     	$clause="";
     	if($id!=''){
			$clause = " where $with like '%$id%'";
		}
		$sql = "SELECT KdSubKategori FROM subkategori $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
	
	function getMaster(){
    	$sql = "SELECT KdKategori,NamaKategori from kategori order by KdKategori";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function getDetail($id){
    	$sql = "SELECT KdSubKategori,NamaSubKategori,KdKategori from subkategori Where KdSubKategori='$id'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }
   
    function get_id($id){
		$sql = "SELECT KdSubKategori FROM subkategori Where KdSubKategori='$id'";
		$query = $this->db->query($sql);
		$num = $query->num_rows();
		$query->free_result();
		return $num;
	}
}
?>