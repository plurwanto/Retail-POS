<?php

class backup extends Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('globallib');
    }

    function index() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("all");
        if ($sign == "Y") {
            $data['track'] = $mylib->print_track();
            $data['content'] = "setup/backup";
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function doThis() {
        //$data['track'] = $mylib->print_track();
        ini_set('memory_limit', '1024M');
        $mylib = new globallib();
        $sql = $this->db->query("select PathAplikasi from aplikasi limit 0,1");
        $path_backup = $sql->row();
        $sql->free_result();
        // print_r($path_backup);//die();
        $this->load->dbutil();
        $this->load->helper('file');
        $name = "backupOmah-" . date("Y-m-d~H-i-s");
        $prefs = array(
            'format' => 'zip', // gzip, zip, txt
            'filename' => $name . ".sql", // File name - NEEDED ONLY WITH ZIP FILES
        );
        $backup = & $this->dbutil->backup($prefs);
        if ($backup) {
            write_file($path_backup->PathAplikasi . $name . ".zip", $backup);
            $this->session->set_flashdata('msg', '<div class="alert alert-success"><button class="close" data-dismiss="alert" type="button"><i class="ace-icon fa fa-times"></i></button>'
                    . '<i class="glyphicon glyphicon-ok"></i> Backup berhasil </div>');
        } else {
            $this->session->set_flashdata('msg', '<div class="alert alert-danger"><button class="close" data-dismiss="alert" type="button"><i class="ace-icon fa fa-times"></i></button>'
                    . '<i class="glyphicon glyphicon-stop"></i> Gagal Backup, Hubungi IT </div>');
        }
        redirect('setup/backup');
    }

}
?>
