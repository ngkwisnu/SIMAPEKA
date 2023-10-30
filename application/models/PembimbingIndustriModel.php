<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PembimbingIndustriModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_by_nik($nik)
    {
        $this->db->select('id, nik, nama, alamat, telepon, email, jabatan, industri_id');
        $this->db->from('pembimbing_industri');
        $this->db->where('nik', $nik);
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() == 1) {
            return $result->row();
        }
        return NULL;
    }
    public function get_by_id($id)
    {
        $this->db->select('id, nik, nama, alamat, telepon, email, jabatan, industri_id');
        $this->db->from('pembimbing_industri');
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
        $this->db->select('pembimbing_industri.id, pembimbing_industri.nik, nama, alamat, telepon, email, jabatan, industri_id');
        $this->db->from('pembimbing_industri');
        $this->db->join('user_pembimbing_industri', 'pembimbing_industri.nik = user_pembimbing_industri.nik');
        $this->db->where('user_pembimbing_industri.user_id', $uid);
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() == 1) {
            return $result->row();
        }
        return NULL;
    }

    public function insert($data)
    {
        $this->db->insert('pembimbing_industri', $data);
        return $this->db->insert_id();
    }
    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('pembimbing_industri', $data);
    }
};
