<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PKLModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    function get_by_nim($nim)
    {
        $this->db->select('pkl.id, industri_id, tahap, created_at, updated_at');
        $this->db->from('pkl');
        $this->db->join('anggota_pkl', 'anggota_pkl.pkl_id = pkl.id');
        $this->db->join('mahasiswa', 'mahasiswa.nim = anggota_pkl.nim');
        $this->db->where('mahasiswa.nim', $nim);
        $this->db->where('mahasiswa.nim IN (SELECT nim FROM anggota_pkl WHERE pkl_id IN (SELECT id FROM pkl WHERE tahap <= 99))');
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() == 1) {
            return $result->row();
        }
        return NULL;
    }

    function tambah_anggota($pkl_id, $nim)
    {
        $this->db->insert('anggota_pkl', [
            'pkl_id' => $pkl_id,
            'nim' => $nim
        ]);
        return $this->db->insert_id();
    }

    function get_id_anggota($id)
    {
        $this->db->select('pkl_id as id, mahasiswa.nama as nama_mahasiswa, anggota_pkl.nim, program_studi.nama as prodi, nip as status');
        $this->db->from('anggota_pkl');
        $this->db->join('mahasiswa', 'mahasiswa.nim = anggota_pkl.nim');
        $this->db->join('program_studi', 'program_studi.id = mahasiswa.prodi_id');
        $this->db->where('pkl_id', $id);
        $this->db->where('nip IS NULL');
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() == 1) {
            return $result->row();
        }
        return NULL;
    }

    function insert($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('pkl', $data);
        return $this->db->insert_id();
    }

    function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        $this->db->update('pkl', $data);
    }

    function update_anggota($nim, $data)
    {
        $this->db->where('nim', $nim);
        $this->db->update('anggota_pkl', $data);
    }

    public function get_by_id($id)
    {
        $this->db->select('pkl.id as id, mahasiswa.nama as nama, mahasiswa.email, mahasiswa.nomor_hp, industri.nama as nama_industri, industri.alamat as alamat_industri, industri.telepon as telepon');
        $this->db->from('pkl');
        $this->db->join('industri', 'industri.id = pkl.industri_id');
        $this->db->join('anggota_pkl', 'anggota_pkl.pkl_id = pkl.id');
        $this->db->join('mahasiswa', 'mahasiswa.nim = anggota_pkl.nim');
        $this->db->where('pkl.id', $id);
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() == 1) {
            return $result->row();
        }
        return NULL;
    }

    public function cek_anggota($id)
    {
        $this->db->select('mahasiswa.nama as nama');
        $this->db->from('anggota_pkl');
        $this->db->join('mahasiswa', 'mahasiswa.nim = anggota_pkl.nim');
        $this->db->where('pkl_id', $id);
        $result = $this->db->get();

        if ($result->num_rows()) {
            return $result->result_array(); // Menggunakan result_array() untuk mengembalikan semua data sebagai array
        }

        return array(); // Mengembalikan array kosong jika tidak ada data yang ditemukan
    }


    public function get_by_industri_id($id)
    {
        $this->db->select('nama, alamat, telepon, penanggung_jawab, google_maps, bidang_industri');
        $this->db->from('pkl');
        $this->db->join('industri', 'industri.id = pkl.industri_id');
        $this->db->where('pkl.id', $id);
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() == 1) {
            return $result->row();
        }
        return NULL;
    }
};
