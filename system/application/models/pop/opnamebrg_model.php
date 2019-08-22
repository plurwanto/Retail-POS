<?php
class Opnamebrg_model extends Model
{
	function __construct(){
        parent::__construct();
    }

    function getbarangList($num, $offset,$id,$with,$field,$tahun,$pcode)
	{
	 	if($offset !=''){
			$offset = $offset;
		}
        else{
        	$offset = 0;
        }
		$clause="";
		if($id!=""){
			$clause = " and $with like '%$id%'";
		}
    	$sql = "
		select $field as QtyStok,st.KodeBarang as PCode,NamaLengkap from stock st, masterbarang b
			where st.KodeBarang=b.PCode and Tahun='$tahun' $clause
			Limit $offset,$num
		";
		//echo "<pre>$sql</pre>";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_barang_row($id,$with,$field,$tahun,$pcode){
     	$clause="";
     	if($id!=''){
			$clause = " and $with like '%$id%'";
		}
		$sql = "select st.KodeBarang AS PCode from stock st,masterbarang b
		where st.KodeBarang = b.PCode  and Tahun='$tahun'  $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
	
	function getSatuan($pcode)
	{
		$sql = "SELECT KdSatuanBesar,(select keterangan from satuan where NamaSatuan=KdSatuanBesar) as NamaBesar,
KdSatuanTengah,(select keterangan from satuan where NamaSatuan=KdSatuanTengah) as NamaTengah,
KdSatuanKecil ,(select keterangan from satuan where NamaSatuan=KdSatuanKecil) as NamaKecil 
		from masterbarang where PCode='$pcode'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
	}
}
?>