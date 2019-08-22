<?php
class discountmodel extends Model {

    function __construct() {
        parent::Model();
    }

    function getdiscountList() {
//        $sql = "select KodeDisc,NamaDisc,if(Jenis='P','Promosi','Regular') as Jenis,if(`RupBar`='B','Barang',if(`RupBar`='P','Persentase','Rupiah')) as Rupiah,
//                    if(Perhitungan='B','Bertingkat',if(Perhitungan='S','Sejajar',if(Perhitungan='K','Kelipatan','Tidak'))) as Perhitungan,
//                    (select date_format(Period1,'%d-%m-%Y')) as Periode1,(select date_format(Period2,'%d-%m-%Y')) as Periode2,
//                    if(Beban='M','Marketing',if(Beban='S','Sales',if(Beban='L','Lain - Lain',''))) as Beban1,NoRekening as Rek1,
//                    Persen1 as Persen1, if(Beban2='M','Marketing',if(Beban2='S','Sales',if(Beban2='L','Lain - Lain',''))) as Beban2,NoRekening2 as Rek2,
//                    Persen2 as Persen2, if(Beban3='M','Marketing',if(Beban3='S','Sales',if(Beban3='L','Lain - Lain',''))) as Beban3,NoRekening3 as Rek3,
//                    Persen3 as Persen3,Nilai from discount_header order by NamaDisc";
//        $this->db->order_by('discount_header.AddDate', 'DESC');
//        $this->db->select('discount_header.KodeDisc,discount_header.NamaDisc,discount_header.Jenis,discount_header.RupBar,discount_header.Perhitungan,discount_header.Period1,discount_header.Period2,discount_header.Nilai,discount_detail.Nilai1');
//        $this->db->from('discount_header');
//        $this->db->join('discount_detail', 'discount_header.KodeDisc=discount_detail.KodeDisc', 'left');
        $this->db->order_by('discount_header.AddDate', 'DESC');
       // $this->db->join('customer', 'customer.KdCustomer = discount_header.KdCustomer', 'left');
        $query = $this->db->get('discount_header');
        if ($query->num_rows() > 0)
            return $query->result_array();
    }

    function getItemBarang() {
        $this->db->order_by('PCode', 'ASC');
        $this->db->select('PCode,NamaStruk');
        $query = $this->db->get('masterbarang');
        if ($query->num_rows() > 0)
            return $query->result_array();
    }

    function getCustomer() {
        $this->db->order_by('Nama');
        $query = $this->db->get('customer');
        if ($query->num_rows() > 0)
            return $query->result_array();
    }
    
    function getCustomer_by_id($id) {
        $this->db->select('KdCustomer,Nama');
        $this->db->where('KdCustomer',$id);
        $query = $this->db->get('customer');
        if ($query->num_rows() > 0)
            return $query->result_array();
    }
    
    function getDetail_by_id($id) {
        $this->db->select('Nilai1');
        $this->db->where('KodeDisc',$id);
        $query = $this->db->get('discount_detail');
        return $query->result_array();
    }

    function num_discount_row($id, $with) {
        $clause = "";
        if ($id != '') {
            $clause = " where $with like '%$id%'";
        }
        $sql = "SELECT NamaDisc FROM discount_header $clause";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
    }

    function getRekening() {
        $sql = "select KdRekening,concat(KdRekening,' - ',NamaRekening) as Keterangan from generalledger.rekening order by KdRekening";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function getDetail($id) {
        $sql = "SELECT KdArea,Keterangan from area Where KdArea='$id'";
        $qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }

    function get_id($id) {
        $sql = "SELECT NamaDisc FROM discount_header Where NamaDisc='$id'";
        $query = $this->db->query($sql);
        $num = $query->num_rows();
        $query->free_result();
        return $num;
    }

    function HadiahExist($id) {
        $sql = "SELECT PCode from masterbarang Where PCode='$id'";
        $qry = $this->db->query($sql);
        $num = $qry->num_rows();
        $qry->free_result();
        return $num;
    }

    function getList($field1, $field2, $tabel) {
        $sql = "select $field1,$field2 from $tabel order by $field1";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

}

?>