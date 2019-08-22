<?php
class user extends Controller {

    function __construct() {
        parent::Controller();
        $this->load->library('globallib');
        $this->load->model('master/usermodel');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
    }

    function index() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("all");
        if ($sign == "Y") {
            $segs = $this->uri->segment_array();
            $arr = "index.php/" . $segs[1] . "/" . $segs[2] . "/";
            $data['link'] = $mylib->restrictLink($arr);
            $data['track'] = $mylib->print_track();
            $data['data'] = $this->usermodel->getuserList();
            $data['content'] = 'master/user/viewuserlist';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function add_new() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("add");
        if ($sign == "Y") {
            $tgl = $this->usermodel->getDate();
            $tahun = substr($tgl->TglTrans, 6, 4);
            $data['kode'] = $this->autoNumber('NoUser', 'setup_no', $tahun);
            $userid = $this->session->userdata('userlevel');
            $id = ($userid == "-1" ? $id = "''" : $id = "-1");
            $data['master1'] = $this->input->post('master');
            $data['page1'] = $this->input->post('mainpage');
            $data['master'] = $this->usermodel->getMaster($id);
            $data['page'] = $this->usermodel->getMenu();
            $data['content'] = 'master/user/adduser';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function delete_user($id) {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("del");
        if ($sign == "Y") {
            $id = $this->uri->segment(4);
            $data['viewuser'] = $this->usermodel->getDetail($id);
            $this->load->view('master/user/deleteuser', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function delete_This() {
        $id = $this->input->post('kode');
        $this->db->delete('user', array('Id' => $id));
        redirect('/master/user/');
    }

    function edit_user($id) {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("edit");
        if ($sign == "Y") {
            $id = $this->uri->segment(4);
            $userid = $this->session->userdata('userlevel');
            $user = ($userid == "-1" ? $user = "''" : $user = "-1");
            $data['viewuser'] = $this->usermodel->getDetail($id);
            $data['master'] = $this->usermodel->getMaster($user);
            $data['page'] = $this->usermodel->getMenu();
            $data['content'] = 'master/user/viewedituser';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function save_user() {
        $id = $this->input->post('kode');
        $nama = trim($this->input->post('nama'));
        $page1 = $this->input->post('mainpage');
        $master1 = strtoupper(trim($this->input->post('master')));

        $this->form_validation->set_rules('nama', 'Nama user', 'trim|required|callback_customAlpha');
        $this->form_validation->set_rules('master', 'userlevel', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="text-danger" role="alert">', '</div>');
        $this->form_validation->set_message('required', '* Harus isi');
        $this->form_validation->set_message('min_length', '* Minimal 6 karakter');
        $this->form_validation->set_message('customAlpha', '* Tidak Boleh Ada Spasi / Koma / Titik');
        
        $arr_val = array('success' => false, 'messages' => array());

        if ($this->form_validation->run()) {
            $data = array(
                'UserName' => $nama,
                'UserLevel' => $master1,
                'MainPage' => $page1,
                'EditDate' => date("Y-m-d")
            );
            $this->db->update('user', $data, array('Id' => $id));
            $arr_val = array('success' => true);
            $this->session->set_flashdata('msg', '<div class="alert alert-success"><button class="close" data-dismiss="alert" type="button"><i class="ace-icon fa fa-times"></i></button>'
                    . '<i class="glyphicon glyphicon-ok"></i> Berhasil di Update </div>');
        } else {
            foreach ($_POST as $key => $value) {
                $arr_val['messages'][$key] = form_error($key);
            }
        }
        echo json_encode($arr_val);
    }

    function save_new_user() {
        $tgl = $this->usermodel->getDate();
        $id = trim($this->input->post('kode'));
        $nama = trim($this->input->post('nama'));
        $master1 = strtoupper(trim($this->input->post('master')));
        $passw = md5(trim($this->input->post('passw')));
        $page1 = $this->input->post('mainpage');

        $this->_validate();
        $arr_val = array('success' => false, 'messages' => array());

        if ($this->form_validation->run()) {
            $new_no = $this->usermodel->getNewNo(substr($tgl->TglTrans, 6, 4));
            $no = $new_no->NoUser;
            $this->db->update('setup_no', array("NoUser" => (int) $no + 1), array("Tahun" => substr($tgl->TglTrans, 6, 4)));
            
            $data = array(
                'Id' => $id,
                'UserLevel' => $master1,
                'UserName' => $nama,
                'Password' => $passw,
                'AddDate' => date('Y-m-d'),
                'MainPage' => $page1,
                'ip' => '127.0.0.1'
            );
            $this->db->insert('user', $data);

            $arr_val = array('success' => true);
            $this->session->set_flashdata('msg', '<div class="alert alert-success"><button class="close" data-dismiss="alert" type="button"><i class="ace-icon fa fa-times"></i></button>'
                    . '<i class="glyphicon glyphicon-ok"></i> Berhasil di Simpan </div>');
        } else {
            foreach ($_POST as $key => $value) {
                $arr_val['messages'][$key] = form_error($key);
            }
        }

        echo json_encode($arr_val);
    }

    function autoNumber($id, $table, $tahun) {
        $query = 'SELECT MAX(RIGHT(' . $id . ', 4)) as max_id FROM ' . $table . ' WHERE Tahun="' . $tahun . '" ORDER BY ' . $id;
        $result = $this->db->query($query);
        $data = $result->result_array();
        $id_max = $data[0]['max_id'];
        $sort_num = substr($id_max, 0, 4);
        $sort_num++;
        $new_code = sprintf("%04s", $sort_num);
        return $new_code;
    }

    function _validate() {
        $this->form_validation->set_rules('nama', 'Nama user', 'trim|required|callback_customAlpha');
        $this->form_validation->set_rules('passw', 'Pass user', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('master', 'userlevel', 'trim|required');
        $this->form_validation->set_error_delimiters('<div class="text-danger" role="alert">', '</div>');
        $this->form_validation->set_message('required', '* Harus isi');
        $this->form_validation->set_message('min_length', '* Minimal 6 karakter');
        $this->form_validation->set_message('customAlpha', '* Tidak Boleh Ada Spasi / Koma / Titik');
    }

    function customAlpha($str) {
        return (!preg_match("/^([-a-z0-9_-\s])+$/i", $str)) ? FALSE : TRUE;
    }

}

?>