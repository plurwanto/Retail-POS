<?php
class lokasimodel extends Model {
	
    function __construct(){
        parent::Model();
    }

    function getlokasiList($num, $offset,$id,$with)
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
    	$sql = "
			select lokasi.KdLokasi,Keterangan,Tingkat,if(isNULL(NamaParent),'No Parent',NamaParent) as NamaParent,concat(Panjang,' x ',Lebar,' x ',Tinggi)as Luas,NamaTipe
			from
			(
			select * from lokasi $clause order by KdLokasi Limit $offset,$num
			) as lokasi
			left join
			(
			select KdLokasi,Keterangan as NamaParent from lokasi
			) as parent
			on lokasi.ParentCode = parent.KdLokasi
			left join
			(
			select KdTipeLokasi,Keterangan as NamaTipe from tipe_lokasi
			)as tipe
			on tipe.KdTipeLokasi = lokasi.KdTipe";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_lokasi_row($id,$with){
     	$clause="";
     	if($id!=''){
			$clause = " where $with like '%$id%'";
		}
		$sql = "SELECT KdLokasi FROM lokasi $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
	
	function getParent(){
    	$sql = "SELECT KdLokasi,Keterangan from lokasi order by KdLokasi";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    function getMaster(){
    	$sql = "SELECT * from tipe_lokasi order by KdTipeLokasi";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function getDetail($id){
    	$sql = "SELECT * from lokasi Where KdLokasi='$id'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }
   
    function get_id($id){
		$sql = "SELECT KdLokasi FROM lokasi Where KdLokasi='$id'";
		$query = $this->db->query($sql);
		$num = $query->num_rows();
		$query->free_result();
		return $num;
	}
}
?>