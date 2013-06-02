<?php
class Show_model extends CI_Model {
    function __construct() {
        parent::__construct();

        $this->load->database();
        $this->config->load('site');
        $this->load->model('author_model');
        $this->load->helper('url');
    }

    function get_tutos($page) {
        $this->db->select('*')
                  ->from($this->config->item('site_tutos_table'))
                  ->order_by('mdate', 'DESC');
        $query = $this->db->get();

        $result = $query->result_array();
        foreach($result as $key => $value) {
            $result[$key] += array(
                'date' => date('d-m-Y G:i', $value['mdate']),
                'author' => $this->author_model->get_where(array('uid' => $value['uid']))['username'],
                'folder' => basename(dirname($value['url'])),
                'ufolder' => base_url() . dirname($value['url'])
            );
        }
        return $result;
    }

    function get_sorted($sort, $page = 0) {
        $query = $this->db->get($this->config->item('site_tutos_table'));
        $result = $query->result_array();
        $newarr = array();

        foreach ($result as $key => $value) {
            switch ($sort) {
            case "name":
                $newarr += array("${value['name']}.${value['mdate']}" => $value);
                break;
            case "author":
                $author = $this->author_model->get_where(array('uid' => $value['uid']))['username'];
                $newarr += array("$author.${value['mdate']}" => $value);
                break;
            }
        }
        ksort($newarr, SORT_NATURAL | SORT_FLAG_CASE);

        foreach($newarr as $key => $value) {
            $newarr[$key] += array(
                'date' => date('d-m-Y G:i', $value['mdate']),
                'author' => $this->author_model->get_where(array('uid' => $value['uid']))['username'],
                'folder' => basename(dirname($value['url'])),
                'ufolder' => base_url() . dirname($value['url'])
            );
        }

        if (count($newarr) > $page * 50)
            return array_slice($newarr, $page * 50, 50);
        else
            return false;
    }
}
