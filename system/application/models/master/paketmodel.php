<?php
class paketmodel extends Model {
	
    function __construct(){
        parent::Model();
    }

    function getbarangList($num, $offset,$id,$with)
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
                SELECT id,MPCode,nl,bMaster,DPcode,nmdet,bDetail FROM
                (SELECT id,MPCode,DPcode FROM `barang_paket`) bp
                INNER JOIN
                (SELECT PCode,BarCode as bMaster,NamaLengkap AS nl FROM masterbarang)mb ON mb.PCode= bp.MPCode
                INNER JOIN
                (SELECT PCode,Barcode as bDetail,NamaLengkap AS nmdet FROM masterbarang)mbd ON mbd.PCode= bp.DPCode;";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_barang_row($id,$with){
     	$clause="";
     	if($id!=''){
			$clause = " where $with like '%$id%'";
		}
		$sql = "SELECT PCode FROM masterbarang where PCode like '99%' $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}
	
    function getDivisi(){
    	$sql = "select KdDivisi,NamaDivisi from divisi order by KdDivisi";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function getSubDivBy($divisi)
    {
		$sql = "select KdSubDivisi,NamaSubDivisi from subdivisi where KdDivisi='$divisi' order by KdSubDivisi";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
    function getKategori()
    {
		$sql = "select KdKategori,NamaKategori from kategori order by KdKategori";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
	
	function getSubKatBy($kategori)
    {
		$sql = "select KdSubKategori,NamaSubKategori from subkategori where KdKategori='$kategori' order by KdSubKategori";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}

	function getBrand()
    {
		$sql = "select KdBrand,NamaBrand from brand order by KdBrand";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
	
	function getSubBrandBy($brand)
    {
		$sql = "select KdSubBrand,NamaSubBrand from subbrand where KdBrand='$brand' order by KdSubBrand";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}

	function getSize()
    {
		$sql = "select KdSize,NamaSize from size order by KdSize";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
	
	function getSubSizeBy($size)
    {
		$sql = "select KdSubSize,Ukuran from subsize where KdSize='$size' order by KdSubSize";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}

	function getDept()
	{
		$sql = "select KdDepartemen,Keterangan from departemen order by KdDepartemen";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
	function getKelas()
	{
		$sql = "select KdKelas,NamaKelas from kelasproduk order by KdKelas";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
	function getTipe()
	{
		$sql = "select KdType,NamaType from tipeproduk order by KdType";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
	
	function getKemasan()
	{
		$sql = "select KdKemasan,NamaKemasan from kemasan order by KdKemasan";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
	function getSupplier()
	{
		$sql = "select KdSupplier,Keterangan from supplier order by KdSupplier";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
	function getPrincipal()
	{
		$sql = "select KdPrincipal,Keterangan from principal order by KdPrincipal";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
	function getSatuan()
	{
		$sql = "select NamaSatuan,keterangan from satuan order by NamaSatuan";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();        
        $qry->free_result();
        return $row;
	}
	function getGrup()
	{
		$sql = "select KdGrupHarga,Keterangan from grup_hargaheader order by KdGrupHarga";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $row2= array("KdGrupHarga"=>"","Keterangan"=>"No Grup");
        array_unshift($row,$row2);
        $qry->free_result();
        return $row;
	}
	function getParent()
	{
		$sql = "select PCode,NamaStruk from masterbarang order by PCode";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $row2= array("PCode"=>"","NamaStruk"=>"No Parent");
        array_unshift($row,$row2);
        $qry->free_result();
        return $row;
	}
    function getDetail($id){
    	$sql = "SELECT * from masterbarang Where PCode='$id'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }   
    function get_id($id){
		$sql = "SELECT PCode FROM masterbarang Where PCode='$id'";
		$query = $this->db->query($sql);
		$num = $query->num_rows();
		$query->free_result();
		return $num;
	}
}
?>