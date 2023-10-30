<?php
defined('BASEPATH') or exit('No direct script access allowed');

class UserModel extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login_by_nim($nim, $password)
    {
        $this->db->select('user.id, user.username, user.password, user.verified, user.status');
        $this->db->from('user');
        $this->db->join('user_mahasiswa', 'user_mahasiswa.user_id = user.id');
        $this->db->where('user_mahasiswa.nim', $nim);
        $this->db->where('user.password', md5($password));
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() == 1) {
            return $result->row();
        }
        return NULL;
    }

    public function login_by_email($email, $password) {
        $this->db->select('id, username, password, verified, status');
        $this->db->from('user');
        $this->db->where('username', $email);
        $this->db->where('password', md5($password));
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() == 1) {
            return $result->row();
        }
        return NULL;
    }

    public function register($nim, $email)
    {
        if (!$this->db->insert('user', [
            'username' => $email,
            'role' => 'mahasiswa',
            'verified' => true,
            'status' => 'active',
            'created_at' => date('Y-m-d H:i:s'),
        ])) {
            return false;
        }

        $user_id = $this->db->insert_id();

        if (!$this->db->insert('user_mahasiswa', [
            'user_id' => $user_id,
            'nim' => $nim
        ])) {
            return false;
        }

        return $user_id;
    }

    public function get_by_id($id)
    {
        $this->db->select('id, username, password, role, verified, status, picture');
        $this->db->from('user');
        $this->db->where('id', $id);
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() == 1) {
            return $result->row();
        }
        return NULL;
    }

    public function get_by_nim($nim) {
        $this->db->select('user.id, user.username, user.password, user.verified, user.status');
        $this->db->from('user');
        $this->db->join('user_mahasiswa', 'user_mahasiswa.user_id = user.id');
        $this->db->where('user_mahasiswa.nim', $nim);
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() == 1) {
            return $result->row();
        }
        return NULL;
    }

    public function get_by_username($username) {
        $this->db->select('id, username, password, role, status, picture');
        $this->db->from('user');
        $this->db->where('username', $username);
        $this->db->limit(1);
        $result = $this->db->get();
        if ($result->num_rows() == 1) {
            return $result->row();
        }
        return NULL;
    }

    public function insert($data){ 
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->db->insert('user', $data);
        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');
        $this->db->where('id', $id);
        $this->db->update('user', $data);
    }

    public function delete($id)
    {
        $this->db->where('user_id', $id);
        $this->db->delete('user_mahasiswa');

        $this->db->where('id', $id);
        $this->db->delete('user');
    }
};
