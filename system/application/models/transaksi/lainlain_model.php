<?php
class Lainlain_model extends Model {

    var $table = "trans_lainlain_detail";

    function __construct() {
        parent::__construct();
    }

    function getList() {
        $this->db->order_by('trans_lainlain_header.AddDate', 'DESC');
        $this->db->select('*');
        $this->db->select('trans_lainlain_header.Keterangan AS Ket');
        $this->db->select('cndn.Keterangan AS CNDN');
        $this->db->where('FlagDelete', 'T');
        $this->db->join('cndn', 'trans_lainlain_header.kdCNDN=cndn.kdCNDN', 'left');
        $query = $this->db->get('trans_lainlain_header');
        // echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
    }

    function getBarangName($kode, $name) {
        $sql = "SELECT CONCAT(PCode,' ',NamaLengkap) AS PCode, PCode AS PCode1,NamaLengkap,HargaBeliAkhir 
                    FROM masterbarang
                    WHERE (PCode LIKE '$name%' OR NamaLengkap LIKE '$name%') LIMIT 0,10";
        // echo $sql;
        return $this->getArrayResult($sql);
    }

    function getBarangBarcode($kode, $name) {
        $sql = "SELECT CONCAT(PCode,' ',NamaLengkap) AS PCode, PCode AS PCode1,NamaLengkap,HargaBeliAkhir
                    FROM masterbarang
                    WHERE BarCode = '$name'";
        // echo $sql;
        return $this->getArrayResult($sql);
    }

    function get_datatables($no) {
        $sql = "
		select d.*,NamaLengkap,keterangan as NamaSatuanJual from(
		SELECT distinct NoDokumen,PCode,QtyPcs,Harga,Netto
		,KonversiBesarKecil,KonversiTengahKecil,KonversiJualKecil,KdSatuan,KdSatuanJual,TglStockSimpan,TglAplikasiStokSimpan from trans_lainlain_detail 
		Where NoDokumen='$no' order by TglAplikasiStokSimpan DESC
		) d
		left join
		(
			select PCode,NamaLengkap
			from masterbarang
		) masterb
		on masterb.PCode = d.PCode
		left join
		(
		select NamaSatuan,keterangan from satuan
		) satu
		on satu.NamaSatuan = d.KdSatuanJual";
        $query = $this->db->query($sql);
        //echo $this->db->last_query();
        if ($query->num_rows() > 0)
            return $query->result_array();
    }

    function get_detail_by_id($no, $pcode) {
        $this->db->where('NoDokumen', $no);
        $this->db->where('PCode', $pcode);
        $query = $this->db->get($this->table);
        return $query;
    }

    function get_lainlain_by_id($no) {
        $this->db->where('NoDokumen', $no);
        $this->db->where('QtyPcs !=', '0');
        $query = $this->db->get('trans_lainlain_detail');
        if ($query->num_rows() > 0)
            return $query->result_array();
    }

    function delete_by_id($no, $pcode) {
        $this->db->where('NoDokumen', $no);
        $this->db->where('PCode', $pcode);
        $this->db->delete($this->table);
    }

    function status_proses($no,$kdtransaksi) {
        $this->db->where('NoTransaksi',$no);
        $this->db->where('KdTransaksi',$kdtransaksi);
        $query = $this->db->get('mutasi');        
        return $query->num_rows();
    }

    function getHeader($id) {
        $sql = "SELECT NoDokumen,DATE_FORMAT(TglDokumen,'%d-%m-%Y') as TglDokumen,h.Keterangan,h.KdCNDN,h.KdCounter,z.Keterangan as NamaCNDN,
		Tipe,if(Tipe='M','PENERIMAAN','PENGELUARAN') as NamaKeterangan
		from trans_lainlain_header h, cndn z
		WHERE NoDokumen='$id' AND z.KdCNDN=h.KdCNDN";
        $query = $this->db->query($sql);
        return $query->row();
    }

    function getDate() {
        $sql = "SELECT date_format(TglTrans,'%d-%m-%Y') as TglTrans,TglTrans as TglTrans2 from aplikasi LIMIT 0,1";
        return $this->getRow($sql);
    }

    function num_row($id, $with) {
        $clause = "";
        if ($id != '') {
            if ($with == "NoDokumen") {
                $clause = " and $with like '%$id%'";
            } else {
                $clause = " and $with = '$id'";
            }
        }
        $sql = "SELECT NoDokumen FROM trans_lainlain_header where FlagDelete = 'T' $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
    }

    function getnoorder($no) {
        $sql = "select * from req_lainlain_header where NoDokumen = '$no'";
        return $this->getArrayResult($sql);
    }

    function getdetOrder($pcode, $noorder) {
        $sql = "SELECT IdD_Req FROM req_lainlain_detail WHERE NoDokumen='$noorder' AND PCode='$pcode'";
        return $this->getArrayResult($sql);
    }

    function getCounter() {
        $sql = "SELECT KdCounter,Keterangan as Nama from counter order by KdCounter";
        return $this->getArrayResult($sql);
    }

    function getCNDN() {
        $sql = "SELECT KdCNDN,Keterangan as Nama from cndn order by KdCNDN";
        return $this->getArrayResult($sql);
    }

    function getKontak() {
        $sql = "select KdContact,Nama from contact order by KdContact";
        return $this->getArrayResult($sql);
    }

    function cekShare($kode, $kontak) {
        $sql = "select h.KdKetentuan
				from ketentuan_simpan_detail d,ketentuan_simpan h
				where PCode='$kode' and h.KdKetentuan=d.KdKetentuan and Pilihan='K' and h.KdContact<>'$kontak'
				and SharePCode='N';";
        return $this->NumResult($sql);
    }

    function getPCodeDet($kode) {
        $sql = "
				select b.*,keterangan as NamaSatuan from(
				SELECT PCode,NamaLengkap as NamaInitial,KdSatuan,HargaJual
				FROM masterbarang Where PCode='$kode'
				) b
				left join
				(
				select NamaSatuan,Keterangan from satuan
				) s 
				on s.NamaSatuan=b.KdSatuan";
        return $this->getRow($sql);
    }

    function getStok($pcode, $tahun, $field, $order, $join) {
        $sql = "
		select PCode,Nama,NoPenerimaan,KonversiBesarKecil,KonversiTengahKecil,$field,AsalData from(
			select head.* from(
			select distinct PCode,KonversiBesarKecil,KonversiTengahKecil,NoPenerimaan,AddDate,AddDateAplikasi,Urutan,AsalData,$field
			from stock_simpan_detail
			where PCode='$pcode'
			and Tahun='$tahun' and (AsalData='O' or AsalData='L')
			) head
			$join
			order by $order
		) stock
		
		where $field>0
		";
        //echo "<pre>$sql</pre>";die;
        return $this->getArrayResult($sql);
    }

    function getNewNo($tahun) {
        $sql = "select NoLainLain from setup_no where Tahun='$tahun'";
        return $this->getRow($sql);
    }

    function getdatalama($no) {
        $sql = "select PCode,QtyPcs from trans_lainlain_detail
		where NoDokumen='$no'";
        return $this->getRow($sql);
    }

    function getPastLocation($no, $pcode) {
        $sql = "select QtyPcs from trans_lainlain_detail
		where NoDokumen='$no' and PCode='$pcode'";
        return $this->getRow($sql);
    }

    function CekStock($fieldmasuk, $fieldakhir, $pcode, $tahun) {
        $sql = "select $fieldmasuk,$fieldakhir from stock 
		where Tahun='$tahun'
		and KodeBarang='$pcode'";
//                print $sql;
        return $this->getRow($sql);
    }

    function cekmutasi($tabel, $no, $pcode, $kdtransaksi) {
        $sql = "select IdMutasi,Qty from $tabel
		where NoTransaksi='$no' and PCode='$pcode' and KdTransaksi='$kdtransaksi'
		";
        return $this->getRow($sql);
    }

    function StockKeluarAwal($fieldkeluar, $fieldakhir, $pcode, $tahun) {
        $sql = "select $fieldkeluar,$fieldakhir from stock
		where Tahun='$tahun'
		and KodeBarang='$pcode'";
        //print $sql;
        return $this->getRow($sql);
    }

    function StockKeluarDetailAwal($no, $pcode, $lokasi, $tahun, $fieldkeluar, $fieldakhir) {
        $sql = "select $fieldkeluar,$fieldakhir from stock_simpan_detail
		where Tahun='$tahun' 
		and NoPenerimaan='$no'
		and PCode='$pcode'";
//                    print $sql;
        return $this->getRow($sql);
    }

    function getDetail($id) {
        $sql = "
		select d.*,NamaInitial,keterangan as NamaSatuanJual from(
		SELECT distinct NoDokumen,PCode,QtyPcs,Harga,Netto
		,KonversiBesarKecil,KonversiTengahKecil,KonversiJualKecil,KdSatuan,KdSatuanJual,TglStockSimpan,TglAplikasiStokSimpan from trans_lainlain_detail 
		Where NoDokumen='$id' order by PCode
		) d
		left join
		(
			select PCode,NamaLengkap as NamaInitial
			from masterbarang
		) masterb
		on masterb.PCode = d.PCode
		left join
		(
		select NamaSatuan,keterangan from satuan
		) satu
		on satu.NamaSatuan = d.KdSatuanJual";
        return $this->getArrayResult($sql);
    }

    function getDetailForPrint($id) {
        $sql = "
		select d.PCode,QtyPcs,NamaInitial,d.Harga from(
		SELECT distinct PCode,QtyPcs,Harga from trans_lainlain_detail Where NoDokumen='$id' order by PCode
		) d
		left join
		(
			select PCode,NamaLengkap as NamaInitial from masterbarang
		) masterb
		on masterb.PCode = d.PCode
		";
        return $this->getArrayResult($sql);
    }

    function getCountDetail($id) {
        $sql = "SELECT NoDokumen FROM trans_lainlain_detail where NoDokumen='$id'";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
    }

    function getQtyCetak($id) {
        $sql = "SELECT QtyCetak from trans_simpan_cetak where nodokumen='$id' ORDER BY PCode";
        return $this->getArrayResult($sql);
    }

    function getBasedBarcode($id) {

        $sql = "select distinct PCode,h.NoDokumen,QtyPcs,QtyKonversi,KonversiBesarKecil,KonversiTengahKecil 
			from trans_lainlain_detail d,trans_lainlain_header h
			where h.NoDokumen=d.NoDokumen and h.NoDokumen='$id'";

        return $this->getArrayResult($sql);
    }

    function getPCodeName($pcode) {
        $sql = "
		select NamaLengkap,SatuanBesar,SatuanTengah,SatuanKecil from
		(
			select NamaLengkap,KdSatuanBesar,KdSatuanTengah,KdSatuanKecil from masterbarang where PCode='$pcode'
		)m
		left join
		(
		select NamaSatuan,Keterangan as SatuanBesar from satuan
		) sb
		on sb.NamaSatuan=m.KdSatuanBesar
		left join
		(
		select NamaSatuan,Keterangan as SatuanTengah from satuan
		) st
		on st.NamaSatuan=m.KdSatuanTengah
		left join
		(
		select NamaSatuan,Keterangan as SatuanKecil from satuan
		) sk
		on sk.NamaSatuan=m.KdSatuanKecil";
        return $this->getRow($sql);
    }

    function locktables($table) {
        $this->db->simple_query("LOCK TABLES $table");
    }

    function unlocktables() {
        $this->db->simple_query("UNLOCK TABLES");
    }

    function getRow($sql) {
        $qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }

    function getArrayResult($sql) {
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function NumResult($sql) {
        $qry = $this->db->query($sql);
        $row = $qry->num_rows();
        $qry->free_result();
        return $row;
    }

    function getno_dok() {
        $sql = "SELECT NoDokumen FROM trans_lainlain_header ORDER BY NoDokumen DESC LIMIT 1";
        return $this->getRow($sql);
    }

}

?>