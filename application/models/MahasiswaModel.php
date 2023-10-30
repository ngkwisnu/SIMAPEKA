<?php
defined('BASEPATH') or exit('No direct script access allowed');

class MahasiswaModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_by_nim($nim)
    {
        $this->db->select('id, nim, nama, email, nomor_hp, alamat, prodi_id, semester');
        $this->db->from('mahasiswa');
        $this->db->where('nim', $nim);
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() == 1) {
            return $result->row();
        }
        return NULL;
    }

    public function get_by_id($id)
    {
        $this->db->select('id, nim, nama, email, nomor_hp, alamat, prodi_id, semester');
        $this->db->from('mahasiswa');
        $this->db->where('id', $id);
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() == 1) {
            return $result->row();
        }
        return NULL;
    }

    public function get_by_uid($uid)
    {
        $this->db->select('mahasiswa.id, mahasiswa.nim, nama, email, nomor_hp, alamat, prodi_id, semester');
        $this->db->from('mahasiswa');
        $this->db->join('user_mahasiswa', 'mahasiswa.nim = user_mahasiswa.nim');
        $this->db->where('user_mahasiswa.user_id', $uid);
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() == 1) {
            return $result->row();
        }
        return NULL;
    }

    public function insert($data)
    {
        $this->db->insert('mahasiswa', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('mahasiswa', $data);
    }
};
