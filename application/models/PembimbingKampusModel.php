<?php
defined('BASEPATH') or exit('No direct script access allowed');

class PembimbingKampusModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_by_nip($nip)
    {
        $this->db->select('id, nip, nama, alamat, telepon, email, bidang_ilmu');
        $this->db->from('pembimbing_kampus');
        $this->db->where('nip', $nip);
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() == 1) {
            return $result->row();
        }
        return NULL;
    }

    public function get_by_id($id)
    {
        $this->db->select('id, nip, nama, alamat, telepon, email, bidang_ilmu');
        $this->db->from('pembimbing_kampus');
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
        $this->db->select('pembimbing_kampus.id, pembimbing_kampus.nip, nama, alamat, telepon, email, bidang_ilmu');
        $this->db->from('pembimbing_kampus');
        $this->db->join('user_pembimbing_kampus', 'pembimbing_kampus.nip = user_pembimbing_kampus.nip');
        $this->db->where('user_pembimbing_kampus.user_id', $uid);
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() == 1) {
            return $result->row();
        }
        return NULL;
    }

    public function insert($data)
    {
        $this->db->insert('pembimbing_kampus', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('pembimbing_kampus', $data);
    }
};
