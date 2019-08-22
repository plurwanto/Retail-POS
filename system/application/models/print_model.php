<?php
class Print_model extends Model {
	
    function __construct(){
        parent::__construct();
    }
	function getPT()
	{
		$sql = "Select NamaPT as Nama,Alamat1PT as Alamat1,Alamat2PT as Alamat2,'' as Kota from aplikasi ORDER BY Tahun DESC LIMIT 0,1";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
		return $row;
	}
	function getPTDO($kdperusahaan)
	{
		$sql = "Select * from perusahaan where KdPerusahaan='$kdperusahaan'";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
		return $row;
	}
	function getAttribute($pcode,$counter,$noterima,$asaldata)
	{
		$sql = "
		select a.KdAttribute,NamaAttribute,TipeAttr,NilAttr,KonversiBesarKecil,KonversiTengahKecil from(
		select KdAttribute,NilAttr,KonversiBesarKecil,KonversiTengahKecil from stock_simpan_detail where AsalData='$asaldata'
		and NoPenerimaan='$noterima' and Counter='$counter' and PCode='$pcode'
		)he
		right join
		(
		select KdAttribute,NamaAttribute,TipeAttr from attribute_barang
		)a
		on a.KdAttribute=he.KdAttribute";
		$qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
	}
	function getSatuan($pcode)
	{
		$sql = "
				select NamaSatuanBesar,NamaSatuanTengah,NamaSatuanKecil from (
				select KdSatuanBesar,KdSatuanTengah,KdSatuanKecil 
				from masterbarang where PCode='$pcode'
				)b
				left join
				(
					select NamaSatuan,keterangan as NamaSatuanBesar from satuan
				)sb
				on sb.NamaSatuan=b.KdSatuanBesar
				left join
				(
					select NamaSatuan,keterangan as NamaSatuanTengah from satuan
				)st
				on st.NamaSatuan=b.KdSatuanTengah
				left join
				(
					select NamaSatuan,keterangan as NamaSatuanKecil from satuan
				)sk
				on sk.NamaSatuan=b.KdSatuanKecil";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
		return $row;
	}
}
?>