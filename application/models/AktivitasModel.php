<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AktivitasModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_by_id($id)
    {
        $this->db->select('id, tanggal, jenis_kegiatan, uraian_kegiatan, jam, nik, validasi, nim, pkl_id');
        $this->db->from('aktivitas_pkl');
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
        $this->db->insert('aktivitas_pkl', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('aktivitas_pkl', $data);
    }
    public function insert_ttd_pemdus($data)
    {
        $this->db->insert('ttd_pemdus', $data);
        return $this->db->insert_id();
    }
};
