<?php
class userlevel extends Controller {

    function __construct() {
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/userlevelmodel');
    }

    function index() {
        $session_level = $this->session->userdata('userlevel');
        $mylib = new globallib();
        $sign = $mylib->getAllowList("all");
        if ($sign == "Y") {
            $segs = $this->uri->segment_array();
            $arr = "index.php/" . $segs[1] . "/" . $segs[2] . "/";
            $data['link'] = $mylib->restrictLink($arr);
            $data['permit'] = $this->userlevelmodel->getUserEditPermission($session_level);
            
            $data['data'] = $this->userlevelmodel->getuserlevelList();
            $data['content'] = 'master/userlevel/viewuserlevellist';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function add_new() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("add");
        if ($sign == "Y") {
            $data['msg'] = "";
            $data['id'] = "";
            $data['nama'] = "";
            $data['content'] = 'master/userlevel/adduserlevel';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function view_userlevel($id) {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("view");
        if ($sign == "Y") {
            $id = $this->uri->segment(4);
            $data['viewuserlevel'] = $this->userlevelmodel->getDetail($id);
            $data['edit'] = false;
            $this->load->view('master/userlevel/viewedituserlevel', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function delete_userlevel($id) {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("del");
        if ($sign == "Y") {
            $id = $this->uri->segment(4);
            $data['viewuserlevel'] = $this->userlevelmodel->getDetail($id);
            $this->load->view('master/userlevel/deleteuserlevel', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function delete_This() {
        $id = $this->input->post('kode');
        $this->db->delete('userlevelpermissions', array('userlevelid' => $id));
        $this->db->delete('userlevels', array('UserLevelID' => $id));
        $this->db->delete('menu', array('UserLevelID' => $id));
        $this->db->delete('user', array('UserLevel' => $id));
        redirect('/master/userlevel/');
    }

    function edit_userlevel($id) {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("edit");
        if ($sign == "Y") {
            $id = $this->uri->segment(4);
            $data['viewuserlevel'] = $this->userlevelmodel->getDetail($id);
            $data['edit'] = true;
            $data['content'] = 'master/userlevel/viewedituserlevel';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function save_userlevel() {
        $id = $this->input->post('kode');
        $nama = strtoupper(trim($this->input->post('nama')));
        $data = array(
            'UserLevelName' => $nama,
            'EditDate' => date("Y-m-d")
        );
        $this->db->update('userlevels', $data, array('UserLevelID' => $id));
        redirect('/master/userlevel/');
    }

    function save_new_userlevel() {
        $id = strtoupper(trim($this->input->post('kode')));
        $nama = strtoupper(trim($this->input->post('nama')));
        $num = $this->userlevelmodel->get_id($id);
        if ($num != 0) {
            $data['id'] = $this->input->post('kode');
            $data['nama'] = $this->input->post('nama');
            $data['msg'] = "<font color='red'><b>Error : Data Dengan Kode $id Sudah Ada</b></font>";
            $this->load->view('master/userlevel/adduserlevel', $data);
        } else {
            $data = array(
                'UserLevelID' => $id,
                'UserLevelName' => $nama,
                'AddDate' => date("Y-m-d")
            );
            $this->db->insert('userlevels', $data);
            $menu = $this->userlevelmodel->getMenu();
            for ($a = 0; $a < count($menu); $a++) {
                unset($dataMenu);
                $dataMenu = array(
                    'userlevelid' => $id,
                    'tablename' => $menu[$a]['nama']
                );
                $this->db->insert('userlevelpermissions', $dataMenu);
            }
            unset($dataMenu);
            $dataMenu = array(
                'userlevelid' => $id,
                'tablename' => "User Permissions"
            );
            $this->db->insert('userlevelpermissions', $dataMenu);

            $Allmenu = $this->userlevelmodel->getAllMenu();
            for ($a = 0; $a < count($Allmenu); $a++) {
                unset($dataMenu);
                $dataMenu = array(
                    'nama' => $Allmenu[$a]['nama'],
                    'ulid' => $Allmenu[$a]['ulid'],
                    'root' => $Allmenu[$a]['root'],
                    'url' => $Allmenu[$a]['url'],
                    'urutan' => $Allmenu[$a]['urutan'],
                    'UserLevelID' => $id,
                    'FlagAktif' => $Allmenu[$a]['FlagAktif'],
                    'Jenis' => $Allmenu[$a]['Jenis']
                );
                $this->db->insert('menu', $dataMenu);
            }
            redirect('/master/userlevel/');
        }
    }

}

?>