<?php
class sales_model extends Model {

    function __construct() {
        parent::Model();
    }

    function aplikasi() {
        $sql = "select * from aplikasi";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function masterbarang() {
        $sql = "SELECT PCode, NamaLengkap from masterbarang order by NamaLengkap ASC";
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

    function sales_temp($user) {
        $sql = "select st.NoUrut, st.KodeBarang, mb.NamaStruk, st.Qty, st.Harga, st.Netto, st.NoStruk,st.Disc,mb.KdGrupBarang
                    from sales_temp st, masterbarang mb
                    where st.KodeBarang = mb.PCode and st.Qty<>'0' and Kasir='$user'
                    order by st.NoUrut asc";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function sales_temp_count($user) {
        $sql = "SELECT COUNT(KodeBarang) as total FROM sales_temp WHERE Qty<>'0' and Kasir='$user'";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();

        return $row;
    }

    function TotalNetto($user) {
        $sql = "select sum(Netto) as TotalNetto from sales_temp where Kasir='$user'";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();

        return $row[0]['TotalNetto'];
    }

    function TotalQty($user) {
        $sql = "select sum(Qty) as TotalQty from sales_temp where Kasir='$user'";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row[0]['TotalQty'];
    }

    function TotalItem($user) {
        $this->db->where('Kasir', $user);
        $query = $this->db->get('sales_temp');
        if ($query->num_rows() > 0)
            return $query->num_rows();
    }
    
    function SubTotal($user) {
        $query = $this->db->query('SELECT SUM(Qty*Harga) AS SubTotal from sales_temp WHERE Kasir="'.$user.'"');
        $row = $query->result_array();
        return $row[0]['SubTotal'];
    }
    
    function TotalDisc($user) {
        $query = $this->db->query('SELECT SUM(Disc) AS TotalDisc from sales_temp WHERE Kasir="'.$user.'"');
        $row = $query->result_array();
        return $row[0]['TotalDisc'];
    }

    function DeleteRecord($kd, $kasir) {
//		if($NoUrut == '')
//		{
//			$LastRecord = $this->LastRecord(0);
//			$AutoID     = $LastRecord[0]['AutoID'];
//
//			$sql = "delete from sales_temp where AutoID = '$AutoID'";
//			$qry = $this->db->query($sql);
//		}
//		else
//		{
//			$sql = "delete from sales_temp where NoUrut = '$NoUrut'";
        $sql = "delete from sales_temp where Kasir='$kasir' AND KodeBarang='$kd'";
        $qry = $this->db->query($sql);
//		}
    }

    function LastRecord($echoFlg, $nm) {
        $sql = "select st.AutoID, st.NoUrut, st.KodeBarang, mb.NamaStruk, st.Qty, st.Harga, st.Netto
				from sales_temp st, masterbarang mb
				where st.KodeBarang = mb.PCode and Kasir='$nm'
				order by st.AutoID desc limit 1";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();

        if ($echoFlg == 1) {
            echo 'datajson = ' . jsonEncode($row);
        } else {
            return $row;
        }
    }

    function EditRecord($kd) {
        $sql = "select st.AutoID, st.NoUrut, st.KodeBarang, mb.NamaStruk, st.Qty, st.Harga, st.Netto
				from sales_temp st, masterbarang mb
				where st.KodeBarang = mb.PCode and st.KodeBarang = '$kd'";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();

        echo 'datajson = ' . jsonEncode($row);
    }

    function do_hitung_bonus($no) {
        $sql = "SELECT SUM(Disc+Disc1+Disc2) as ttl FROM transaksi_detail WHERE NoStruk='$no'";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row[0]['ttl'];
    }

    function sales_temp_cek($kdbrg, $kasir) {
        $sql = "select KodeBarang from sales_temp where KodeBarang='$kdbrg' and Kasir='$kasir'";
        $qry = $this->db->query($sql);
        $row = $qry->num_rows();
        return $row;
    }

    function ambilDataTemp($struk, $kasir) {
        $sql = "SELECT a.Tanggal,a.NoUrut,a.NoStruk,a.KodeBarang,a.Qty,a.Harga,TTL FROM 
            (SELECT * FROM `sales_temp` WHERE NoStruk='$struk' AND Kasir='$kasir')a
            LEFT JOIN 
            (SELECT NoStruk,SUM(Qty * Harga) AS TTL FROM `sales_temp` 
            	WHERE NoStruk='$struk' AND Kasir='$kasir' Group By NoStruk)b 
            ON b.NoStruk=a.NoStruk ";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function CekBonus($PCode, $tgl) {
        $sql = "SELECT * FROM `discount_header` b, discount_detail d
            WHERE Period1 <= '$tgl' AND '$tgl' <=Period2 AND
            b.`KodeDisc`=d.`KodeDisc` AND d.list='$PCode'";
        $qry = $this->db->query($sql);
        return $row = $qry->result_array();
    }
    
    function cekBonus_by_grupbrg($struk, $kasir){
        $sql = "SELECT a.NoUrut,a.Tanggal,a.KodeBarang,a.Qty,a.Netto,b.KdGrupBarang, a.Disc, SUM(a.Qty) AS TotalQty,COUNT(NoUrut) AS totalbaris FROM sales_temp a LEFT JOIN masterbarang b
                    ON a.KodeBarang = b.PCode WHERE NoStruk='$struk' AND Kasir='$kasir' AND KdGrupBarang != '' GROUP BY KdGrupBarang";
        return $this->db->query($sql);
    }
    
    function CekDiskonAktif($tgl) {
        $sql = "SELECT * FROM `discount_header`
            WHERE Period1 <= '$tgl' AND '$tgl' <=Period2";
        $qry = $this->db->query($sql);
        return $row = $qry->result_array();
    }
    
     function CekDiskonDetailAktif($id) {
        $this->db->select('Nilai1');
        $this->db->where('KodeDisc',$id);
        $query = $this->db->get('discount_detail');
        return $query->result_array();
    }

    function sales_temp_add($jumlah, $kdbrg, $Struk, $EditFlg, $kasir, $dis_potongan) {
        if ($EditFlg != 1) {
            $sql = "UPDATE sales_temp SET Qty = Qty + $jumlah, Netto = ((Qty * Harga) - (Qty * Harga * $dis_potongan)) WHERE NoStruk='$Struk' and KodeBarang='$kdbrg' and Kasir='$kasir'";
        } else {
            $sql = "UPDATE sales_temp SET Qty = $jumlah, Netto = Qty * Harga WHERE NoStruk='$Struk' and KodeBarang='$kdbrg' and Kasir='$kasir'";
        }
        $qry = $this->db->query($sql);
    }

    function do_simpan_detail($no, $id, $gdg) {
        $sql = "INSERT INTO transaksi_detail(`NoKassa`,Gudang,`NoStruk`,`Tanggal`,`Waktu`,`Kasir`,`PCode`,`Qty`,`Harga`,`Netto`,`KdStore`,`Status`,Disc1,Keterangan,Ketentuan1)
				SELECT NoKassa,'$gdg','$no',Tanggal,Waktu,Kasir,KodeBarang,Qty,Harga,Netto,KdStore,Status,Disc,RupBar,KetPromo FROM sales_temp where kasir='$id' and Qty<>'0'"; //tambahin field baru disini
        $qry = $this->db->query($sql);
    }

    function do_simpan_mutasi($no, $id, $gdg) {
        $sql = "INSERT INTO mutasi( `NoKassa`,Gudang,`NoTransaksi`,`Jenis`,`KdTransaksi` ,`Tanggal`,`KodeBarang`,`Qty` ,`Nilai`,Kasir)
                        SELECT NoKassa,'$gdg','$no','O','R',Tanggal,KodeBarang,Qty,Harga,Kasir FROM sales_temp where kasir='$id' and Qty <>'0'"; //tambahin field baru disini
        $qry = $this->db->query($sql);
    }

    function do_simpan_header($no, $id, $gdg) {
        $sql = "INSERT INTO transaksi_header(`NoKassa`,Gudang,`NoStruk`,`Tanggal`,`Waktu`,`Kasir`,`TotalItem`,`TotalNilai`,`KdStore`,`Status`,Discount)
			SELECT MIN(NoKassa),'$gdg','$no',MIN(Tanggal),MIN(Waktu),MIN(Kasir),COUNT(KodeBarang),SUM(Netto),MIN(KdStore),MIN(Status),SUM(Disc) FROM sales_temp where kasir='$id' and Qty<>'0'";
        $qry = $this->db->query($sql);
    }

    function clear_kasir($id) {
        $sql = "delete from sales_temp where kasir = '$id'";
        $qry = $this->db->query($sql);
    }

    function clear_trans($id) {
        $sql = "delete from sales_temp where kasir = '$id'";
        $qry = $this->db->query($sql);
    }

    function bayar_trans($nostruk, $cash, $kredit, $debet, $voucher, $total_bayar, $id_customer, $nama_customer, $gender_customer, $tgl_customer) {
        if (!empty($cash)) {
            $param1 = ", Tunai='$cash'";
        } else {
            $param1 = "";
        }

        if (!empty($kredit)) {
            $param2 = ", KKredit='$kredit'";
        } else {
            $param2 = "";
        }

        if (!empty($debet)) {
            $param3 = ", KDebit='$debet'";
        } else {
            $param3 = "";
        }

        if (!empty($voucher)) {
            $param4 = ", Voucher='$voucher'";
        } else {
            $param4 = "";
        }

        if (!empty($id_customer)) {
            $param5 = ", KdCustomer='$id_customer', NamaCustomer='$nama_customer', Gender='$gender_customer', TglLahir='$tgl_customer'";
        } else {
            $param5 = "";
        }

        $sql = "update transaksi_header set TotalBayar='$total_bayar', Kembali= $total_bayar-TotalNilai, Status='0' $param1 $param2 $param3 $param4 $param5 where NoStruk = '$nostruk'";
        $qry = $this->db->query($sql);

        $sql2 = "update transaksi_detail set Status='0' where NoStruk = '$nostruk'";
        $qry2 = $this->db->query($sql2);
    }

    function save_trans_bayar($nostruk, $nokassa, $nama_customer, $id_kredit, $kredit, $id_debet, $debet, $id_voucher, $voucher, $cash, $gdg) {
        if (!empty($nama_customer)) {
            $nama = $nama_customer;
        } else {
            $nama = "";
        }

        if (!empty($cash)) {
            $jenis1 = "T";
            $nilai1 = $cash;
        } else {
            $jenis1 = "";
            $nilai1 = 0;
        }

        if (!empty($id_kredit) and ! empty($kredit)) {
            $nomor2 = $id_kredit;
            $jenis2 = "K";
            $nilai2 = $kredit;
        } else {
            $nomor2 = "";
            $jenis2 = "";
            $nilai2 = 0;
        }

        if (!empty($id_debet) and ! empty($debet)) {
            $nomor3 = $id_debet;
            $jenis3 = "D";
            $nilai3 = $debet;
        } else {
            $nomor3 = "";
            $jenis3 = "";
            $nilai3 = 0;
        }

        if (!empty($id_voucher) and ! empty($voucher)) {
            $nomor4 = $id_voucher;
            $jenis4 = "V";
            $nilai4 = $voucher;
        } else {
            $nomor4 = "";
            $jenis4 = "";
            $nilai4 = 0;
        }

        $jenis = $jenis1 . $jenis2 . $jenis3 . $jenis4;

        $sql = "insert into transaksi_detail_bayar (Gudang,`NoKassa`,`NoStruk`,`Jenis`,`Kode`,`NomorKKredit`,`NomorKDebet`,`NomorVoucher`,`Keterangan`,`ExpDate`,`NilaiTunai`,`NilaiKredit`,`NilaiDebet`,`NilaiVoucher`)
			 	VALUES ('$gdg','$nokassa','$nostruk','$jenis','','$nomor2','$nomor3','$nomor4','$nama','0000-00-00','$nilai1','$nilai2','$nilai3','$nilai4')";
        $qry = $this->db->query($sql);
    }

    function no_struk() {
        $sql = "select max(NoStruk) as NoStruk from transaksi_header";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();

        return $row;
    }

    function no_struk_temp() {
        $sql = "SELECT MAX(NoStruk) as NoStruk FROM sales_temp";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function customer($pelanggan) {
        $sql = "select * from customer where KdCustomer='$pelanggan'";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();

        echo 'datajson = ' . jsonEncode($row);
    }
    
    function getCustomer() {
        $this->db->order_by('Nama');
        $query = $this->db->get('customer');
        if ($query->num_rows() > 0)
            return $query->result_array();
    }

    function voucher($id_voucher) {
        $sql = "select * from voucher where KdVoucher='$id_voucher'";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();

        echo 'datajson = ' . jsonEncode($row);
    }

    function trans_header() {
        $sql = "select * from transaksi_header where Status = '0' order by NoStruk DESC LIMIT 1";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function cekPCode($kd) {
        $sql = "select * from masterbarang where PCode='$kd'";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function cekStock($kd, $thn) {
        $sql = "select * from stock where KodeBarang='$kd' and tahun='$thn'";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function cekidkassa($ip) {
        $sql = "SELECT id_kassa FROM kassa WHERE ip='$ip'";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
//                echo $sql;die();
        return $row[0]['id_kassa'];
    }

    function ambil_No($tahun) {
        $sql = "SELECT no_struk FROM setup_no where tahun='$tahun'";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row[0]['no_struk'];
    }

    function cekBarcode($kd) {
        $sql = "select * from masterbarang where Barcode='$kd'";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function all_trans($id) {
        $sql = "select * from transaksi_header where NoStruk = '$id' order by NoStruk DESC LIMIT 1";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        return $row;
    }

    function det_trans($id) {
        $sql = "select a.PCode,b.NamaStruk,a.Qty,a.Harga,a.Netto,Disc1,Ketentuan1 
			from transaksi_detail a, masterbarang b where a.NoStruk='$id' 
			and a.PCode=b.Pcode order by Waktu ASC";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();

        return $row;
    }

}

?>