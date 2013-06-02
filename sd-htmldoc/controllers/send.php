<?php
class Send extends CI_Controller {
    function __construct() {
        parent::__construct();

        $this->load->helper(array('form', 'html'));
        $this->load->library('form_validation');
        $this->lang->load('send');
        $this->config->load('site');
    }

    function index() {
        $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
        $this->form_validation->set_rules('tuto', $this->lang->line('label_tutorial'),
            'required|is_unique[' . $this->config->item('site_tutos_table') . '.name]');
        $this->form_validation->set_rules('user', $this->lang->line('label_user'),
            'required|min_length[6]|is_unique[' . $this->config->item('site_user_table') . '.username]');
        $this->form_validation->set_rules('pwd', $this->lang->line('label_pwd'),
            'required');
        $this->form_validation->set_rules('file', $this->lang->line('label_file'),
            'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('send/index');
        }
        else {
            // do something
        }
    }
}
