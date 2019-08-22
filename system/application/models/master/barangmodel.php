<?php

class barangmodel extends Model {

    function __construct() {
        parent::Model();
    }

    var $tabel = 'masterbarang';
    
    function getbarangList() {
        $sql = "
		    select b.PCode,Barcode,NamaStruk,NamaLengkap,NamaInitial,HargaJual,
			if(isNULL(NamaGrupHarga),'No Grup',NamaGrupHarga) as NamaGrupHarga,if(FlagHarga='HJ','HargaJual','Grup Harga') as FlagHarga,
			Satuan,if(isNULL(Parent),'No Parent',Parent) as Parent,Konversi,NamaSupplier,Principal,Status,MinOrder 
			,NamaDivisi,NamaSubDivisi,NamaKategori,NamaSubKategori,NamaBrand,NamaSubBrand,Departemen,NamaKelas,NamaType
			,NamaKemasan,NamaSize,Ukuran
			from
			(
			select * from masterbarang order by PCode
			) as b
			left join
			(
			select KdGrupHarga,Keterangan as NamaGrupHarga from grup_hargaheader
			) as grup
			on grup.KdGrupHarga=b.KdGrupHarga
			left join
			(
			select NamaSatuan,keterangan as Satuan from satuan
			) as satuan
			on satuan.NamaSatuan = b.KdSatuan
			left join
			(
			select PCode,NamaStruk as Parent from masterbarang
			) as parent
			on parent.PCode = b.ParentCode
			left join
			(
			select KdSupplier,Keterangan as NamaSupplier from supplier
			) as supplier
			on supplier.KdSupplier  = b.KdSupplier
			left join
			(
			select KdPrincipal,Keterangan as Principal from principal
			) as princ
			on princ.KdPrincipal = b.KdPrincipal
			left join
			(
			select KdDivisi,NamaDivisi from divisi 
			) as divisi
			on divisi.KdDivisi = b.KdDivisi
			left join
			(
			select KdSubDivisi,NamaSubDivisi from subdivisi 
			)as subdivisi
			on subdivisi.KdSubDivisi = b.KdSubDivisi
			left join
			(
			select KdKategori,NamaKategori from kategori 
			) as kategori
			on kategori.KdKategori = b.KdKategori
			left join
			(
			select KdSubKategori,NamaSubKategori from subkategori 
			)as subkategori
			on subkategori.KdSubKategori = b.KdSubKategori
			left join
			(
			select KdBrand,NamaBrand from brand 
			) as brand
			on brand.KdBrand = b.KdBrand
			left join
			(
			select KdSubBrand,NamaSubBrand from subbrand 
			)as subbrand
			on subbrand.KdSubBrand = b.KdSubBrand
			left join
			(
			select KdDepartemen,Keterangan as Departemen from departemen 
			)as departemen
			on departemen.KdDepartemen = b.KdDepartemen
			left join
			(
			select KdKelas,NamaKelas from kelasproduk 
			)as kelasproduk
			on kelasproduk.KdKelas = b.KdKelas
			left join
			(
			select KdType,NamaType from tipeproduk 
			)as tipeproduk
			on tipeproduk.KdType = b.KdType
			left join
			(
			select KdKemasan,NamaKemasan from kemasan 
			)as kemasan
			on kemasan.KdKemasan = b.KdKemasan
			left join
			(
			select KdSize,NamaSize from size 
			)as size
			on size.KdSize = b.KdSize
			left join
			(
			select KdSubSize,Ukuran from subsize 
			)as subsize
			on subsize.KdSubSize = b.KdSubSize ORDER BY b.AddDate DESC";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function num_barang_row($id, $with) {
        $clause = "";
        if ($id != '') {
            $clause = " where $with like '%$id%'";
        }
        $sql = "SELECT PCode FROM masterbarang $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
    }

    function getDivisi() {
        $sql = "select KdDivisi,NamaDivisi from divisi order by KdDivisi";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function getSubDivBy($divisi) {
        $sql = "select KdSubDivisi,NamaSubDivisi from subdivisi where KdDivisi='$divisi' order by KdSubDivisi";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function getKategori() {
        $sql = "select KdKategori,NamaKategori from kategori order by KdKategori";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function getSubKatBy($kategori) {
        $sql = "select KdSubKategori,NamaSubKategori from subkategori where KdKategori='$kategori' order by KdSubKategori";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function getBrand() {
        $sql = "select KdBrand,NamaBrand from brand order by KdBrand";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function getSubBrandBy($brand) {
        $sql = "select KdSubBrand,NamaSubBrand from subbrand where KdBrand='$brand' order by KdSubBrand";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function getSize() {
        $sql = "select KdSize,NamaSize from size order by KdSize";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function getSubSizeBy($size) {
        $sql = "select KdSubSize,Ukuran from subsize where KdSize='$size' order by KdSubSize";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function getDept() {
        $sql = "select KdDepartemen,Keterangan from departemen order by KdDepartemen";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function getKelas() {
        $sql = "select KdKelas,NamaKelas from kelasproduk order by KdKelas";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function getTipe() {
        $sql = "select KdType,NamaType from tipeproduk order by KdType";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function getKemasan() {
        $sql = "select KdKemasan,NamaKemasan from kemasan order by KdKemasan";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function getSupplier() {
        $sql = "select KdSupplier,Keterangan from supplier order by KdSupplier";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function getPrincipal() {
        $sql = "select KdPrincipal,Keterangan from principal order by KdPrincipal";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function getSatuan() {
        $sql = "select NamaSatuan,keterangan from satuan order by NamaSatuan";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function getGrup() {
        $sql = "select KdGrupHarga,Keterangan from grup_hargaheader order by KdGrupHarga";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $row2 = array("KdGrupHarga" => "", "Keterangan" => "No Grup");
        array_unshift($row, $row2);
        $qry->free_result();
        return $row;
    }

    function getParent() {
        $sql = "select PCode,NamaStruk from masterbarang order by PCode";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $row2 = array("PCode" => "", "NamaStruk" => "No Parent");
        array_unshift($row, $row2);
        $qry->free_result();
        return $row;
    }

    function getDetail($id) {
        $sql = "SELECT * from masterbarang Where PCode='$id'";
        $qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }

    function get_id($id) {
        $this->db->where('PCode', $id);
        $this->db->select('PCode');
        $query = $this->db->get($this->tabel);
        return $query->result();
    }

}

?>