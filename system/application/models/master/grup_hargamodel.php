<?php
class grup_hargamodel extends Model {
	
    function __construct(){
        parent::Model();
    }

    function getgrup_hargaList($num, $offset,$id,$with)
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
        $sql = "SELECT KdGrupHarga, Keterangan FROM grup_hargaheader $clause order by KdGrupHarga Limit $offset,$num";
	$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_grup_harga_row($id,$with){
     	$clause="";
     	if($id!=''){
			$clause = " where $with like '%$id%'";
		}
		$sql = "SELECT KdGrupHarga FROM grup_hargaheader $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
    
    function getHeader($id){
    	$sql = "SELECT KdGrupHarga,Keterangan from grup_hargaheader Where KdGrupHarga='$id'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }
    function getDetail($id){
    	$sql = "select grup.PCode,grup.HargaJual,KdPLU,HargaMaster,NamaStruk from
			(
			SELECT * from grup_hargadetail Where KdGrupHarga='$id'
			) as grup
			left join
			(
			select NamaStruk,PCode,HargaJual as HargaMaster from masterbarang
			) as barang
			on barang.PCode=grup.PCode";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function get_id($id){
		$sql = "SELECT KdGrupHarga FROM grup_hargaheader Where KdGrupHarga='$id'";
		$query = $this->db->query($sql);
		$num = $query->num_rows();
		$query->free_result();
		return $num;
	}
	function getDetailItem($pcode)
	{
		$sql = "SELECT NamaStruk,HargaJual from masterbarang Where PCode='$pcode'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
	}
	function CheckItem($pcode)
	{
		$sql = "SELECT PCode FROM masterbarang Where PCode='$pcode'";
		$query = $this->db->query($sql);
		$num = $query->num_rows();
		$query->free_result();
		return $num;
	}
	function checkPCode($id,$pcode)
	{
		$sql = "SELECT KdGrupHarga FROM grup_hargadetail Where KdGrupHarga='$id' and PCode='$pcode'";
		$query = $this->db->query($sql);
		$num = $query->num_rows();
		$query->free_result();
		return $num;
	}
	
	function DetailItemForSales($PCode)
	{
            if(strlen($PCode)!=13){
		$sql = "SELECT PCode,NamaStruk, HargaJual from masterbarang Where PCode = '$PCode'";
            }else{
		$sql = "SELECT PCode,NamaStruk, HargaJual from masterbarang Where Barcode = '$PCode'";
            }
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
            if(empty($row)){
                echo "salah";
            }else{
		echo 'datajson = '.jsonEncode($row);
            }
	}
}
?>