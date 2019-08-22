<?php
class void_model extends Model {

    function __construct() {
        parent::__construct();
    }

    function getList($tahun) {
        $this->db->select('NoStruk,Tanggal,Waktu,TotalItem,TotalNilai,Kasir');
        $this->db->where('Status !=', '2');
        $this->db->where('YEAR(Tanggal)', $tahun);
        $this->db->order_by('Tanggal', 'DESC');
        $this->db->order_by('Waktu', 'DESC');
        $this->db->order_by('NoStruk', 'DESC');
        $sql = $this->db->get('transaksi_header');
        // echo $this->db->last_query();
        return $sql->result_array();
//        $sql = "
//			select NoStruk,DATE_FORMAT(Tanggal,'%d-%m-%Y') as Tanggal
//			,Kasir,TotalItem,TotalNilai
//			from transaksi_header where Status <> 2
//			order by DATE_FORMAT(Tanggal,'%Y-%m-%d') DESC, NoStruk*1 DESC
//			Limit $offset,$num
//			";
//        return $this->getArrayResult($sql);
    }

    function getDate() {
        $sql = "SELECT date_format(TglTrans,'%d-%m-%Y') as TglTrans,TglTrans as TglTrans2,DATE_ADD(TglTrans,INTERVAL -2 DAY) AS TglTrans3 from aplikasi LIMIT 0,1";
        return $this->getRow($sql);
    }

    function getAlmPerusahaan($id) {
        $sql = "SELECT DISTINCT Nama,Alamat1,Alamat2,Kota FROM perusahaan WHERE KdPerusahaan='$id'";
        return $this->getRow($sql);
    }

    function num_row($id, $with) {
        $clause = "";
        if ($id != '') {
            if ($with == "NoStruk") {
                $clause = " and $with like '%$id%'";
            } else {
                $clause = " and $with = '$id'";
            }
        }
        $sql = "SELECT NoStruk FROM transaksi_header where Status <> 2  $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
    }

    function aplikasi() {
        $sql = "select * from aplikasi";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function NamaPrinter($id) {
        $sql = "SELECT * from kassa where ip='$id'";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function getnoorder($no) {
        $sql = "select * from req_lainlain_header where NoDokumen = '$no'";
        return $this->getArrayResult($sql);
    }

    function getdetOrder($pcode, $noorder) {
        $sql = "SELECT IdD_Req FROM req_lainlain_detail WHERE NoDokumen='$noorder' AND PCode='$pcode'";
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
				SELECT PCode,NamaLengkap as NamaInitial,KonversiJualKecil,KonversiBesarKecil,KonversiTengahKecil,KdSatuanJual 
				FROM masterbarang Where PCode='$kode'
				) b
				left join
				(
				select NamaSatuan,Keterangan from satuan
				) s 
				on s.NamaSatuan=b.KdSatuanJual";
        return $this->getRow($sql);
    }

    function getLocationStockLeft($pcode, $no, $tahun, $field, $kontak) {
        $sql = "select lo.*, ifnull($field,0) as Terpakai,Qty from(
				select s.KdLokasi,Keterangan,volLokasi,volBarang from(
					select KdLokasi from(
					select KdKetentuan,KdLokasi from ketentuan_simpan
					where Pilihan='L'
					)h
					inner join
					(
					select KdKetentuan from ketentuan_simpan_detail 
					where KdContact='$kontak' and KdKetentuan in(select KdKetentuan from ketentuan_simpan_detail where PCode='$pcode')
					)d2
					on d2.KdKetentuan=h.KdKetentuan
					
					union
					select KdLokasi from ketentuan_simpan head,ketentuan_simpan_detail det
					where Pilihan='L' and PCode='$pcode' and ShareContact='' and det.KdKetentuan=head.KdKetentuan
					
					union 	
					select KdLokasi from ketentuan_simpan head,ketentuan_simpan_detail det
					where Pilihan='L' and det.KdContact='$kontak' and SharePCode='' and det.KdKetentuan=head.KdKetentuan
					
					union
					select KdLokasi from ketentuan_simpan where Pilihan='L' and 
					((SharePCode='Y' and ShareContact='Y') or (ShareContact='Y' and SharePCode='') or (ShareContact='' and SharePCode='Y'))

					union

					select KdLokasi from lokasi
					where KdLokasi not in(select KdLokasi from ketentuan_simpan where Pilihan='L')  and StatusLokasiKecil='Y'
					order by KdLokasi
				)s
				left join
				(
				select KdLokasi,Keterangan,(l.Panjang * l.Lebar * l.Tinggi)*100 as volLokasi,(b.Panjang * b.Lebar * b.Tinggi) as volBarang 
				from lokasi l,masterbarang b
				where PCode='$pcode' and StatusLokasiKecil='Y'
				)lo2
				on s.KdLokasi=lo2.KdLokasi
			) lo
			left join
			(
				select sum($field) as $field,KdLokasi from(
					select sum($field) *(b.Panjang * b.Lebar * b.Tinggi) as $field,KdLokasi,b.PCode
					from stock_simpan,masterbarang b
					where b.PCode=stock_simpan.PCode and Tahun='$tahun'
					group by KdLokasi,PCode
				) k group by KdLokasi
			) stock
			on stock.KdLokasi = lo.KdLokasi
			left join
			(
				select distinct QtyPcs as Qty,KdLokasi from trans_lainlain_detail
				where PCode='$pcode' and NoDokumen='$no'
			) simpan
			on simpan.KdLokasi = lo.KdLokasi";
        return $this->getArrayResult($sql);
    }

    function getStok($pcode, $tahun, $field, $order, $join) {
        $sql = "
		select PCode,stock.KdLokasi,Nama,NoPenerimaan,KonversiBesarKecil,KonversiTengahKecil,$field,AsalData from(
			select head.* from(
			select distinct PCode,KdLokasi,KonversiBesarKecil,KonversiTengahKecil,NoPenerimaan,AddDate,AddDateAplikasi,Urutan,AsalData,$field
			from stock_simpan_detail
			where PCode='$pcode'
			and Tahun='$tahun' and (AsalData='O' or AsalData='L')
			) head
			$join
			order by $order
		) stock
		left join
		(
			select KdLokasi,Keterangan as Nama
			from lokasi
		)b
		on b.KdLokasi=stock.KdLokasi
		where $field>0
		";
        //echo "<pre>$sql</pre>";die;
        return $this->getArrayResult($sql);
    }

    function getNewNo($tahun) {
        $sql = "select NoLainLain from aplikasi where Tahun='$tahun'";
        return $this->getRow($sql);
    }

    function update_total_beli($no) {
        $nilai = "SELECT SUM(Netto) AS a,COUNT(PCode) AS item FROM transaksi_detail WHERE nostruk='$no'";
        $b = $this->getRow($nilai);
//                print($b->a);die();
        $sql = "UPDATE transaksi_header SET TotalNilai=$b->a,TotalBayar=$b->a,Kembali=0,Tunai=$b->a,TotalItem=$b->item WHERE nostruk='$no'";
        $qry = $this->db->query($sql);
        $sql2 = "UPDATE transaksi_detail_bayar SET NilaiTunai=$b->a WHERE nostruk='$no'";
        $qry2 = $this->db->query($sql2);
    }

    function getdatalama($no) {
        $sql = "select PCode,Qty from transaksi_detail
		where NoStruk='$no'"; //echo $sql;
        return $this->getArrayResult($sql);
    }

    function getPastLocation($no, $pcode) {
        $sql = "select KdLokasi,QtyPcs from trans_lainlain_detail
		where NoDokumen='$no' and PCode='$pcode'";
        return $this->getRow($sql);
    }

    function CekStock($fieldmasuk, $fieldakhir, $pcode, $tahun, $lokasi) {
        $sql = "select $fieldmasuk,$fieldakhir from stock 
		where Tahun='$tahun' and KdLokasi='$lokasi'
		and PCode='$pcode'";
//                print $sql;
        return $this->getRow($sql);
    }

    function CekKeluar($fieldkeluar, $fieldakhir, $pcode, $tahun) {
        $sql = "select $fieldkeluar,$fieldakhir from stock
		where Tahun='$tahun' and KodeBarang='$pcode'";
//                print $sql;
//        return $this->getRow($sql);
        return $this->getArrayResult($sql);
    }

    function cekmutasi($tabel, $no, $pcode, $lokasi, $kdtransaksi) {
        $sql = "select IdMutasi,Qty from $tabel
		where NoTransaksi='$no' and PCode='$pcode' and KdTransaksi='$kdtransaksi'
		and KdLokasi='$lokasi'";
        return $this->getRow($sql);
    }

    function StockKeluarDetailAwal($no, $pcode, $lokasi, $tahun, $fieldkeluar, $fieldakhir) {
        $sql = "select $fieldkeluar,$fieldakhir from stock_simpan_detail
		where Tahun='$tahun' and KdLokasi='$lokasi'
		and NoPenerimaan='$no'
		and PCode='$pcode'";
//                    print $sql;
        return $this->getRow($sql);
    }

    function getHeader($id) {
        $sql = "SELECT NoStruk,DATE_FORMAT(Tanggal,'%d-%m-%Y') as TglDokumen,Kasir
		from transaksi_header Where NoStruk='$id'";
        return $this->getRow($sql);
    }

    function getHeader2($id) {
        $sql = "SELECT * FROM transaksi_header WHERE NoStruk='$id' ORDER BY NoStruk DESC LIMIT 1";
        $query = $this->db->query($sql);
        return $query->result_array();
    }
    
    function getHeader3($id) {
        $sql = "SELECT NoStruk,DATE_FORMAT(Tanggal,'%d-%m-%Y') as TglDokumen,Waktu,Kasir from transaksi_header Where NoStruk='$id'";
        $query = $this->db->query($sql);
        return $query->row();
    }

    function getDetail($id) {
        $sql = "
		SELECT a.*,NamaLengkap FROM
                (SELECT NoKassa,NoStruk,Tanggal,Waktu,PCode,Qty,Harga,STATUS,Keterangan
                FROM transaksi_detail WHERE STATUS='1' AND NoStruk='$id')a
                INNER JOIN
                (SELECT PCode,NamaLengkap FROM masterbarang)b ON a.PCode=b.PCode
                INNER JOIN
                (SELECT NoStruk, Kasir FROM transaksi_header)c ON c.NoStruk=a.NoStruk";
        return $this->getArrayResult($sql);
    }

    function getDetailForPrint($id) {
        $sql = "select a.PCode,b.NamaStruk,a.Qty,a.Harga,a.Netto,Disc1,Ketentuan1 
			from transaksi_detail a, masterbarang b where a.NoStruk='$id' 
			and a.PCode=b.Pcode order by Waktu ASC";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();

        return $row;
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

    function StockKeluarAwal($fieldkeluar, $fieldakhir, $pcode, $tahun) {
        $sql = "select $fieldkeluar,$fieldakhir from stock
		where Tahun='$tahun'
		and KodeBarang='$pcode'";
//                print $sql;
        return $this->getRow($sql);
    }

    function getBasedBarcode($id) {

        $sql = "select distinct PCode,h.NoDokumen,QtyPcs,QtyKonversi,KdLokasi,KonversiBesarKecil,KonversiTengahKecil 
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