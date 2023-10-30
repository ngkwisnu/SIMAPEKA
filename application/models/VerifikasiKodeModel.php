<?php
defined('BASEPATH') or exit('No direct script access allowed');

class VerifikasiKodeModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_by_id($id)
    {
        $this->db->select('id, user_id, kode, expiry, used');
        $this->db->from('verifikasi_kode');
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
        $this->db->select('id, user_id, kode, expiry, used');
        $this->db->from('verifikasi_kode');
        $this->db->where('user_id', $uid);
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() == 1) {
            return $result->row();
        }
        return NULL;
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update('verifikasi_kode', $data);
    }

    public function insert($user_id, $kode)
    {
        return $this->db->insert('verifikasi_kode', [
            'user_id' => $user_id,
            'kode' => $kode,
            'expiry' => date('Y-m-d H:i:s', strtotime('+5 minutes')),
            'used' => false
        ]);
    }
};
