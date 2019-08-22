<?php
class sub_tipe_storemodel extends Model {
	
    function __construct(){
        parent::Model();
    }

    function getsub_tipe_storeList($num, $offset,$id,$with)
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
    	$sql = "select KdSubTipeStore,sub.Keterangan  as NamaSub,dive.Keterangan as NamaStore,NamaGrupHarga from(
					SELECT KdSubTipeStore, Keterangan,KdTipeStore,KdGrupHarga
					FROM sub_tipe_store $clause order by KdSubTipeStore  Limit $offset,$num
				) as sub
				left join
				(
					select KdTipeStore, Keterangan from tipe_store
				) as dive
				on dive.KdTipeStore = sub.KdTipeStore
				left join 
				(
					select KdGrupHarga,Keterangan as NamaGrupHarga from grup_hargaheader
				)as grup
				on grup.KdGrupHarga = sub.KdGrupHarga";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_sub_tipe_store_row($id,$with){
     	$clause="";
     	if($id!=''){
			$clause = " where $with like '%$id%'";
		}
		$sql = "SELECT KdSubTipeStore FROM sub_tipe_store $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
	
	function getMaster(){
    	$sql = "SELECT KdTipeStore,Keterangan from tipe_store order by KdTipeStore";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function getDetail($id){
    	$sql = "SELECT * from sub_tipe_store Where KdSubTipeStore='$id'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }
   
    function get_id($id){
		$sql = "SELECT KdSubTipeStore FROM sub_tipe_store Where KdSubTipeStore='$id'";
		$query = $this->db->query($sql);
		$num = $query->num_rows();
		$query->free_result();
		return $num;
	}
	function getGrupHarga()
	{
		$sql = "SELECT KdGrupHarga,Keterangan from grup_hargaheader order by KdGrupHarga";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
}
?>