<?php
defined('BASEPATH') or exit('No direct script access allowed');

class IndustriModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_by_id($id)
    {
        $this->db->select('id, nama, alamat, telepon, penanggung_jawab, google_maps as lokasi, bidang_industri');
        $this->db->from('industri');
        $this->db->where('id', $id);
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() == 1) {
            return $result->row();
        }
        return NULL;
    }

    public function insert($data)
    {
        $this->db->insert('industri', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('industri', $data);
    }
};
