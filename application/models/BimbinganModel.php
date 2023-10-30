<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BimbinganModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_by_id($id)
    {
        $this->db->select('id, tanggal, deskripsi, status, uraian, file, id_pkl, nim, nip');
        $this->db->from('bimbingan_pkl');
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
        $this->db->insert('bimbingan_pkl', $data);
        return $this->db->insert_id();
    }

    public function insert_ttd_pempus($data)
    {
        $this->db->insert('ttd_pempus', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('bimbingan_pkl', $data);
    }

    public function delete($id)
    {
    }
};
