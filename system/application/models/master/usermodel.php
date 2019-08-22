<?php
class usermodel extends Model {

    function __construct() {
        parent::__construct();
    }

    function getuserList() {
        $this->db->order_by('user.AddDate', 'DESC');
        $this->db->select('Id,UserLevel,UserName,UserLevelName,Active');
        $this->db->from('user');
        $this->db->join('userlevels', 'userlevels.UserLevelID=user.UserLevel', 'left');
        $query = $this->db->get();
        if ($query->num_rows() > 0)
            return $query->result_array();
    }

    function getMaster($id) {
        $sql = "SELECT UserLevelID,UserLevelName from userlevels WHERE UserLevelID NOT IN ($id) order by UserLevelID";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function getDetail($id) {
        $sql = "SELECT Id,UserName,UserLevel,MainPage,active from user Where Id='$id'";
        $qry = $this->db->query($sql);
        $row = $qry->row();
        $qry->free_result();
        return $row;
    }

    function get_id($id, $name) {
        $sql = "SELECT Id FROM user Where Id='$id' or UserName='$name'";
        $query = $this->db->query($sql);
        $num = $query->num_rows();
        $query->free_result();
        return $num;
    }

    function getMenu() {
        $sql = "select distinct nama from menu where url!='' and nama!='Logout' and FlagAktif=99 order by nama";
        $qry = $this->db->query($sql);
        $row = $qry->result_array();
        $qry->free_result();
        return $row;
    }

    function getNewNo($tahun) {
        $this->db->where('Tahun', $tahun);
        $this->db->select('NoUser');
        $query = $this->db->get('setup_no');
        if ($query->num_rows() > 0)
            return $query->row();
    }

    //tambahan untuk module ganti password//
    function updatePassword($userid, $password) {
        //$mdpassword = md5($password);
        $this->db->where('Id', $userid);
        $this->db->update('user', array('Password' => $password, "Active" => "Y", "EditDate" => $this->getNow()));
    }

    function cekdata($namatable, $where) {
        $returnvalue = 0;
        $this->db->select('count(*) as jumlah');
        //$this->db->from($namatable);
        $this->db->where($where);
        $result = $this->db->get($namatable);
        foreach ($result->result() as $row) {
            $returnvalue = $row->jumlah;
        }
        return $returnvalue;
    }

    function getNow() {
        $sql = "SELECT now()";
        $query = $this->db->query($sql);
        $result = $query->result_array();
        foreach ($result as $v1) {
            foreach ($v1 as $v2) {
                return $v2;
            }
        }
    }

    function get_userid($userid) {
        return $this->db->query("SELECT Password FROM user WHERE Id ='$userid'");
    }
    
    function getDate() {
        $sql = "SELECT date_format(TglTrans,'%d-%m-%Y') as TglTrans,TglTrans as TglTrans2 from aplikasi ORDER BY Tahun DESC LIMIT 0,1";
        $query = $this->db->query($sql);
        return $query->row();
    }

    /////////////////////////////////////////////
}

?>