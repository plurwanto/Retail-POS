<?php
class grup_storemodel extends Model {
	
    function __construct(){
        parent::Model();
    }

    function getgrup_storeList($num, $offset,$id,$with)
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
    	$sql = "SELECT KdGrupStore, Keterangan,if(KeyOutletStatus='1','Ya','Tidak') as KeyStore FROM grup_store $clause order by KdGrupStore Limit $offset,$num";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_grup_store_row($id,$with){
     	$clause="";
     	if($id!=''){
			$clause = " where $with like '%$id%'";
		}
		$sql = "SELECT KdGrupStore FROM grup_store $clause ";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
    
    function getDetail($id){
    	$sql = "SELECT KdGrupStore,Keterangan,if(KeyOutletStatus='1','Ya','Tidak') as KeyStore from grup_store Where KdGrupStore='$id'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }
    
    function get_id($id){
		$sql = "SELECT KdGrupStore FROM grup_store Where KdGrupStore='$id'";
		$query = $this->db->query($sql);
		$num = $query->num_rows();
		$query->free_result();
		return $num;
	}
}
?>