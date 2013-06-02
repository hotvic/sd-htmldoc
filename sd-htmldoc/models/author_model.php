<?php
class Author_model extends CI_Model {
    function __construct() {
        parent::__construct();

        $this->load->database();
        $this->config->load('site');
    }

    function get_where($where) {
        $query = $this->db->get_where($this->config->item('site_users_table'), $where);

        return $query->row_array();
    }
}
