<?php
class gantipassword extends Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('globallib');
        $this->load->model('master/usermodel');
        $userid = $this->session->userdata('Id');
        $this->load->library('form_validation');
        $this->load->helper('form');
        $this->load->helper('url');
    }

    function index() {
        $mylib = new globallib();
        $sign = $mylib->getAllowList("all");
        if ($sign == "Y") {
            //$data['track'] = $mylib->print_track();
            $data['userid'] = $this->session->userdata('Id'); //--------
            $data['username'] = $this->session->userdata('UserName'); //--------
            $data['pesan'] = "";
            $data['content'] = 'master/user/formgantipassword';
            $this->load->view('tampilan_home', $data);
        } else {
            $this->load->view('denied');
        }
    }

    function changePassword() {
        $userid = $this->input->post('userid');
        $passwordlama = md5($this->input->post('passwordlama'));
        $newpassword = md5($this->input->post('newpassword'));

        $arr_val = array('success' => false, 'messages' => array());
        $this->_set_rules();

        if ($this->form_validation->run()) {
            $this->usermodel->updatePassword($userid, $newpassword);
            $data['userid'] = $this->session->userdata('Id'); //--------
            $data['username'] = $this->session->userdata('UserName'); //--------

            $this->session->set_flashdata('msg', '<div class="alert alert-success"><button class="close" data-dismiss="alert" type="button"><i class="ace-icon fa fa-times"></i></button>'
                    . '<i class="glyphicon glyphicon-ok"></i> Update Password Berhasil </div>');
           // $data['pesan'] = "Update Password Berhasil";

            $arr_val['success'] = true;
            
        } else {
            foreach ($_POST as $key => $value) {
                $arr_val['messages'][$key] = form_error($key);
            }
        }
        echo json_encode($arr_val);
    }

    function _set_rules() {
        $this->form_validation->set_rules('passwordlama', 'passwordlama', 'trim|required|callback_pwdlama_check');
        $this->form_validation->set_rules('newpassword', 'passwordbaru', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('konfpassword', 'konfpassword', 'trim|required|min_length[6]|matches[newpassword]|md5');

        $this->form_validation->set_message('min_length', '* Minimal 6 karakter');
        $this->form_validation->set_message('matches', '* Konfirmasi Password tidak sama');
        $this->form_validation->set_message('required', '* Harus Isi');
        $this->form_validation->set_error_delimiters('<div class="text-danger" rode="alert">', '</div>');
    }

    function pwdlama_check($passwordlama) {
        $userid = $this->input->post('userid');
        $passwordlama = md5($this->input->post('passwordlama'));
        $dtpwdlama = $this->usermodel->get_userid($userid);
        foreach ($dtpwdlama->result() as $value) {
            $pwd = $value->Password;
            if ($pwd != $passwordlama) {
                $this->form_validation->set_message('pwdlama_check', '* Password Lama Anda Salah');
                return FALSE;
            } else {
                return TRUE;
                $passwordlama = "";
            }
        }
//        echo $this->db->last_query();
    }

}

?>