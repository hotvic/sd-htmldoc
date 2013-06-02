<?php

class Show extends CI_Controller {
    function __construct() {
        parent::__construct();

        $this->config->load('site');
        $this->load->model('show_model');
        $this->lang->load('show');
    }

    function index($page = 0) {
        $data['title'] = ucfirst($this->config->item('site_title'));
        $data['tutos'] = $this->show_model->get_tutos($page);
        $data['npage'] = $page + 1;
        $data['url'] = $this->config->site_url();
        // Language
        $data['lang_tutorial'] = $this->lang->line('tutorial');
        $data['lang_sources'] = $this->lang->line('sources');
        $data['lang_date'] = $this->lang->line('date');
        $data['lang_author'] = $this->lang->line('author');
        $data['lang_next_link'] = $this->lang->line('next_link');

        $this->load->view('templates/header', $data);
        $this->load->view('show/list', $data);
        $this->load->view('templates/footer', $data);
    }

    function by($sort = NULL, $page = 0) {
        if (!$sort) {
            $this->index();
            return;
        }

        $data['title'] = ucfirst($this->config->item('site_title'));
        $data['tutos'] = $this->show_model->get_sorted($sort, $page);
        $data['npage'] = $page + 1;
        $data['url'] = $this->config->site_url();
        // Language
        $data['lang_tutorial'] = $this->lang->line('tutorial');
        $data['lang_sources'] = $this->lang->line('sources');
        $data['lang_date'] = $this->lang->line('date');
        $data['lang_author'] = $this->lang->line('author');
        $data['lang_next_link'] = $this->lang->line('next_link');

        $this->load->view('templates/header', $data);
        $this->load->view('show/list', $data);
        $this->load->view('templates/footer', $data);
    }
}
