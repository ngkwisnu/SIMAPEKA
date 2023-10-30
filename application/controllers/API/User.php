<?php
defined('BASEPATH') or exit('No direct script access allowed');

include 'BaseAPI.php';

class User extends BaseAPI
{
	public function __construct()
	{
		parent::__construct();

		$this->user = $this->m_user->get_by_id($this->session->userdata('user_id'));
		if ($this->user->role == 'mahasiswa') {
			$this->mhs = $this->m_mhs->get_by_uid($this->user->id);
		}
	}

	public function index()
	{
		$start = $this->input->get('start');
		$length = $this->input->get('length');
		$order = $this->input->get('order');
		if ($start === null || $length === null || $order == null) {
			die(json_encode(['success' => false, 'message' => 'Bad request.']));
		}
		$search = $this->input->get('search')['value'];
		$regex = $this->input->get('search')['regex'];

		$order_column = $this->input->get('columns')[$order[0]['column']]['data'];
		$order_dir = $order[0]['dir'];

		$users = $this->db
			->select('(@n := @n + 1) as no, id, username, role, verified, status')
			->from('user')
			->from('(SELECT @n := 0) as t')
			->like('id', $search, $regex ? 'both' : 'after')
			->or_like('username', $search, $regex ? 'both' : 'after')
			->or_like('role', $search, $regex ? 'both' : 'after')
			->order_by($order_column, $order_dir)
			->get()
			->result();

		$count = count($users);
		$users = array_slice($users, $start, $length);

		foreach ($users as $usr) {
			if ($usr->role == 'pembimbing_industri') {
				$usr->role = 'Pembimbing Industri';
			} elseif ($usr->role == 'pembimbing_kampus') {
				$usr->role = 'Pembimbing Kampus';
			} else {
				$usr->role = ucfirst($usr->role);
			}

			$status = $usr->status;
			$usr->status = '<div class="d-flex justify-content-center">';
			if ($status == 'disabled') {
				$usr->status .= '<span class="badge badge-danger ml-1 mr-1">' . ucfirst($status) . '</span>';
			} elseif ($status == 'inactive') {
				$usr->status .= '<span class="badge badge-warning ml-1 mr-1">' . ucfirst($status) . '</span>';
			} else {
				$usr->status .= '<span class="badge badge-info ml-1 mr-1">' . ucfirst($status) . '</span>';
			}
			$usr->status .= '</div></center>';
			$usr->action = '<button type="button" class="btn btn-sm btn-outline-warning btn-block mr-2" onclick="showEditModal(' . $usr->id . ')"><i class="fa fa-edit"></i> Edit</button>';
		}

		die(
			json_encode([
				'success' => true,
				'data' => $users,
				'recordsTotal' => $this->db->count_all('user'),
				'recordsFiltered' => $count,
			])
		);
	}

	public function get($id)
	{
		$user = $this->m_user->get_by_id($id);
		if (!$user) {
			die(
				json_encode([
					'success' => false,
					'message' => 'Pengguna tidak ditemukan!',
				])
			);
		}
		$user->password = '';

		die(
			json_encode([
				'success' => true,
				'data' => $user,
			])
		);
	}

	public function add()
	{
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$role = $this->input->post('role');
			$status = $this->input->post('status');
			$verified = $this->input->post('verified');
			if (!$username || !$password || !$role || !$status || !$status || !$verified) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Bad request.',
					])
				);
			}

			$user = $this->m_user->get_by_username($username);
			if ($user) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Username sudah digunakan!',
					])
				);
			}

			$this->m_user->insert([
				'username' => $username,
				'password' => md5($password),
				'role' => $role,
				'status' => $status,
				'verified' => $verified,
			]);

			die(
				json_encode([
					'success' => true,
					'message' => 'Pengguna berhasil ditambahkan!',
				])
			);
		} else {
			die(
				json_encode([
					'success' => false,
					'message' => 'Method not allowed!',
				])
			);
		}
	}

	public function edit($id)
	{
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$role = $this->input->post('role');
			$status = $this->input->post('status');
			$verified = $this->input->post('verified');
			if (!$id || !$username || !$role || !$status || !$status) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Bad request.',
					])
				);
			}

			if ($id == $this->user->id) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Anda tidak dapat mengubah data anda sendiri!',
					])
				);
			}

			$user = $this->m_user->get_by_id($id);
			if (!$user) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Pengguna tidak ditemukan!',
					])
				);
			}

			$this->m_user_model->update($id, [
				'username' => $username,
				'password' => strlen($user->password) > 0 ? md5($password) : $user->password,
				'role' => $role,
				'status' => $status,
				'verified' => $verified,
			]);

			die(
				json_encode([
					'success' => true,
					'message' => 'Pengguna berhasil diubah!',
				])
			);
		} else {
			die(
				json_encode([
					'success' => false,
					'message' => 'Method not allowed!',
				])
			);
		}
	}
}
