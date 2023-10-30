<?php
defined('BASEPATH') or exit('No direct script access allowed');

class NilaiModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('nilai_mahasiswa');
        $this->db->where('id', $id);
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() == 1) {
            return $result->row();
        }
        return NULL;
    }

    public function get_nilai($id)
    {
        $this->db->select('*');
        $this->db->from('nilai_kampus');
        $this->db->join('nilai_mahasiswa', 'nilai_mahasiswa.id = nilai_kampus.id_nilai');
        $this->db->where('nilai_kampus.id_nilai', $id);
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() == 1) {
            return $result->row();
        }
        return NULL;
    }

    public function insert($data)
    {
        $this->db->insert('nilai_mahasiswa', $data);
        return $this->db->insert_id();
    }

    public function insert_nilai_kampus($data)
    {
        $this->db->insert('nilai_kampus', $data);
        return $this->db->insert_id();
    }

    public function insert_nilai_pkl($data)
    {
        $this->db->insert('nilai_pkl', $data);
        return $this->db->insert_id();
    }

    public function update_nilai_kampus($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('nilai_kampus', $data);
    }

    public function update_nilai_pkl($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('nilai_pkl', $data);
    }

    public function delete($id)
    {
    }
};
