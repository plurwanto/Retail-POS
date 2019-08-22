<?php
class storemodel extends Model {
	
    function __construct(){
        parent::Model();
    }

    function getstoreList($num, $offset,$id,$with)
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
    	$sql = "select KdStore,NamaStore,if(isNULL(NamaGrupHarga),'No Grup',NamaGrupHarga) as NamaGrupHarga ,NamaDC,NamaTipe,NamaSubTipe,if(IsNULL(NamaGrupStore),'No Grup',NamaGrupStore) as NamaGrupStore,NamaKlasifikasi,
				NamaChannel,KodePos,PIC,concat(PanjangStore,' x ',LebarStore) as LuasStore,concat(PanjangAll,' x ',LebarAll) as LuasAll
				from
				(
				select * from masterstore $clause order by KdStore Limit $offset,$num
				) as store
				left join
				(
				select KdGrupHarga,Keterangan as NamaGrupHarga from grup_hargaheader
				) as grupharga
				on grupharga.KdGrupHarga = store.KdGrupHarga
				left join
				(
				select KdDC,Keterangan as NamaDC from dc
				) as dc
				on dc.KdDC = store.KdDC
				left join
				(
				select KdTipeStore,Keterangan as NamaTipe from tipe_store
				) as tipestore
				on tipestore.KdTipeStore=store.KdTipeStore
				left join
				(
				select KdSubTipeStore,Keterangan as NamaSubTipe from sub_tipe_store
				)as subtipe
				on store.KdSubTipeStore = subtipe.KdSubTipeStore
				left join
				(
				select KdGrupStore,Keterangan as NamaGrupStore from grup_store
				)as grupstore
				on grupstore.KdGrupStore=store.KdGrupStore
				left join
				(
				select KdKlasifikasi,Keterangan as NamaKlasifikasi from klasifikasi_store
				) as klasifikasi
				on klasifikasi.KdKlasifikasi = store.KdKlasifikasi
				left join
				(
				select KdChannel, Keterangan as NamaChannel from channel
				) as channel
				on channel.KdChannel = store.KdChannel";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }
    
    function num_store_row($id,$with){
     	$clause="";
     	if($id!=''){
			$clause = " where $with like '%$id%'";
		}
		$sql = "SELECT KdStore FROM masterstore $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
	}	
    
	function getDC()
    {
		$sql = "select KdDC,Keterangan from dc order by KdDC";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
    function getArea()
    {
		$sql = "select KdArea,Keterangan from area order by KdArea";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
    function getTipe()
    {
		$sql = "select KdTipeStore,Keterangan from tipe_store order by KdTipeStore";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
	
	function getSubTipeBy($tipe)
    {
		$sql = "select KdSubTipeStore,Keterangan from sub_tipe_store where KdTipeStore='$tipe' order by KdSubTipeStore";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
	function getGrupHargaBy($subtipe){
		$sql = "select KdGrupHarga,Keterangan from grup_hargaheader where KdGrupHarga in
(select KdGrupHarga from sub_tipe_store where KdSubTipeStore='$subtipe') order by KdGrupHarga";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $row2= array("KdGrupHarga"=>"","Keterangan"=>"No Grup");
        array_unshift($row,$row2);
        $qry->free_result();
        return $row;	
    }
	function getSubAreaBy($area)
    {
		$sql = "select KdSubArea,Keterangan from subarea where KdArea='$area' order by KdSubArea";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
	function getKodePosBy($subarea)
    {
		$sql = "select KodePos from kodepos where KdSubArea='$subarea' order by KodePos";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}

	function getGrupStore()
	{
		$sql = "select KdGrupStore,Keterangan from grup_store order by KdGrupStore";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $row2= array("KdGrupStore"=>"","Keterangan"=>"No Grup");
        array_unshift($row,$row2);
        $qry->free_result();
        return $row;
	}
	function getKlasifikasi()
	{
		$sql = "select KdKlasifikasi,Keterangan from klasifikasi_store order by KdKlasifikasi";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
	function getChannel()
	{
		$sql = "select KdChannel,Keterangan from channel order by KdChannel";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
    function getDetail($id){
    	$sql = "SELECT * from masterstore Where KdStore='$id'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }   
    function get_id($id){
		$sql = "SELECT KdStore FROM masterstore Where KdStore='$id'";
		$query = $this->db->query($sql);
		$num = $query->num_rows();
		$query->free_result();
		return $num;
	}
}
?>