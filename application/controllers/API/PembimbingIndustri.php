<?php
defined('BASEPATH') or exit('No direct script access allowed');

include 'BaseAPI.php';

class PembimbingIndustri extends BaseAPI
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
		if ($this->input->server('REQUEST_METHOD') === 'GET') {
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

			$pemdus = $this->db
				->select('(@n := @n + 1) as no, pembimbing_industri.id, nik, pembimbing_industri.nama, pembimbing_industri.alamat, pembimbing_industri.telepon, pembimbing_industri.email, pembimbing_industri.jabatan, industri.nama as industri')
				->from('pembimbing_industri')
				->from('(SELECT @n := 0) as t')
				->join('industri', 'industri.id = pembimbing_industri.industri_id')
				->like('nik', $search, $regex ? 'both' : 'after')
				->or_like('pembimbing_industri.nama', $search, $regex ? 'both' : 'after')
				->or_like('pembimbing_industri.alamat', $search, $regex ? 'both' : 'after')
				->or_like('pembimbing_industri.telepon', $search, $regex ? 'both' : 'after')
				->or_like('pembimbing_industri.email', $search, $regex ? 'both' : 'after')
				->or_like('pembimbing_industri.jabatan', $search, $regex ? 'both' : 'after')
				->or_like('industri.nama', $search, $regex ? 'both' : 'after')
				->order_by($order_column, $order_dir)
				->get()
				->result();

			$count = count($pemdus);
			$pemdus = array_slice($pemdus, $start, $length);

			foreach ($pemdus as $ids) {
				$ids->action = '<button type="button" class="btn btn-sm btn-outline-warning btn-block mr-2" onclick="showEditModal(' . $ids->id . ')"><i class="fa fa-edit"></i> Edit</button>';
			}

			die(
				json_encode([
					'success' => true,
					'data' => $pemdus,
					'recordsTotal' => $this->db->count_all('pembimbing_industri'),
					'recordsFiltered' => $count,
				])
			);
		} else {
			die(json_encode(['success' => false, 'message' => 'Method not allowed!']));
		}
	}

	public function get($id)
	{
		$ids = $this->m_pemdus->get_by_id($id);
		if (!$ids) {
			die(
				json_encode([
					'success' => false,
					'message' => 'Pembimbing Industri tidak ditemukan!',
				])
			);
		}

		die(
			json_encode([
				'success' => true,
				'data' => $ids,
			])
		);
	}
	public function add()
	{
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$nik = $this->input->post('nik');
			$nama = $this->input->post('nama');
			$alamat = $this->input->post('alamat');
			$telepon = $this->input->post('telepon');
			$email = $this->input->post('email');
			$jabatan = $this->input->post('jabatan');
			$industri_id = $this->input->post('industri_id');
			$pengguna_id = $this->input->post('pengguna_id');
			if (!$nik || !$nama || !$alamat || !$telepon || !$email || !$jabatan || !$industri_id || $pengguna_id == -1) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Bad request.',
					])
				);
			}

			$pemdus = $this->m_pemdus->get_by_nik($nik);
			if ($pemdus) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Pembimbing Industri Sudah Terdaftar!',
					])
				);
			}

			$this->m_pemdus->insert([
				'nik' => $nik,
				'nama' => $nama,
				'alamat' => $alamat,
				'telepon' => $telepon,
				'email' => $email,
				'jabatan' => $jabatan,
				'industri_id' => $industri_id,
			]);

			$this->db->insert('user_pembimbing_industri', [
				'user_id' => $pengguna_id,
				'nik' => $nik,
			]);

			die(
				json_encode([
					'success' => true,
					'message' => 'Pembimbing Industri berhasil ditambahkan!',
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
			$nik = $this->input->post('nik');
			$nama = $this->input->post('nama');
			$alamat = $this->input->post('alamat');
			$telepon = $this->input->post('telepon');
			$email = $this->input->post('email');
			$jabatan = $this->input->post('jabatan');
			$industri_id = $this->input->post('industri_id');
			if (!$id || !$nik || !$nama || !$alamat || !$telepon || !$email || !$jabatan || !$industri_id) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Bad request.',
					])
				);
			}

			$pemdus = $this->m_pemdus->get_by_id($id);
			if (!$pemdus) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Pembimbing Industri tidak ditemukan!',
					])
				);
			}

			$this->m_pemdus->update($id, [
				'nik' => $nik,
				'nama' => $nama,
				'alamat' => $alamat,
				'telepon' => $telepon,
				'email' => $email,
				'jabatan' => $jabatan,
				'industri_id' => $industri_id,
			]);

			die(
				json_encode([
					'success' => true,
					'message' => 'Pembimbing Industri berhasil diubah!',
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
