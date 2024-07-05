<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Pricelist_Model extends CI_Model
{
    private $tableName;

    function __construct()
    {
        parent::__construct();

        $this->tableName = 'pricelist';
    }

    public function get_pricelists()
    {
        return $this->db->get($this->tableName)->result_array();
    }
    public function get_pricelist_where($whereData = array())
    {
        return $this->db->get_where($this->tableName, $whereData)->result_array();
    }
    public function input_pricelist($data)
    {
        $this->db->insert($this->tableName, $data);
        $input_id = $this->db->insert_id();
        return $input_id ? $input_id : 0;
    }
    public function update_pricelist($updateData, $whereData)
    {
        $this->db->update($this->tableName, $updateData, $whereData);
        return $this->db->affected_rows();
    }
    public function delete_pricelist($whereData)
    {
        if (count($this->db->get_where($this->tableName, $whereData)->result_array()) > 0) {
            return $this->db->delete($this->tableName, $whereData);
        }
        return 0;
    }
    public function delete_all()
    {
        $ALL_Del = $this->db->empty_table($this->tableName);
        $query = $this->db->query("ALTER TABLE " . $this->tableName . " AUTO_INCREMENT = 1");
        return $ALL_Del;
    }
}