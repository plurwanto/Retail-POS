<?php

class globalmodel extends Model {	
    function __construct(){
        parent::Model();
    }

    function getPermission($str,$id)
    {
		$sql="select * from userlevelpermissions where tablename=(select nama from menu where url='$str' and UserLevelID='$id');";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }
    function getkassa($ip){
        $sql = "SELECT * FROM kassa WHERE ip='$ip'";
        $qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }

    function getkd($id){
        $sql = "SELECT PCode FROM masterbarang WHERE BarCode='$id'";
        $qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }

    function getName($url)
    {
		$sql="select distinct nama,root,ulid from menu where url='$url';";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }

    function getName2($root)
    {
		$sql="select distinct nama,root,ulid from menu where nama='$root';";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }

    function getRoot($ulid)
    {
		$sql="select distinct nama,root,ulid from menu where ulid='$ulid';";
		$qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }

     function updatePPN($id,$tblh,$thn)
    {
            $sql = "UPDATE $tblh SET PPN=(SELECT PPN FROM aplikasi WHERE tahun=$thn) WHERE NoDokumen='$id'";
            $qry = $this->db->query($sql);
//        $qry = $this->db->query($sql);
//        return $row;
    }

    function getField($sql)
    {
		$qry = $this->db->query($sql);
                $row = $qry->row();
                $qry->free_result();
             return $row;
    }

    function editData($parameter,$data,$where){
            $this->db->where($where);
            $this->db->update($parameter, $data);
    }
    function getQuery($sql){
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    
    function updateTTLHeader($id,$tblH,$tbld,$fielqty,$fielharga)
    {
            $sql = "UPDATE $tblH SET Netto=(SELECT SUM($fielqty * $fielharga)
                    FROM $tbld WHERE NoDokumen='$id'),Bruto= Netto + (Netto * ppn / 100),
                    CountQty = (SELECT COUNT(PCode) FROM  $tbld WHERE NoDokumen='$id')
                    WHERE NoDokumen='$id'";

            $qry = $this->db->query($sql);
//        $row = $qry->row();
//        $qry->free_result();
//        return $row;
    }



}
?>