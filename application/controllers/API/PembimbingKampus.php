<?php
defined('BASEPATH') or exit('No direct script access allowed');

include 'BaseAPI.php';

class PembimbingKampus extends BaseAPI
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

			$pempus = $this->db
				->select('(@n := @n + 1) as no, pembimbing_kampus.id, nip, pembimbing_kampus.nama, pembimbing_kampus.alamat, pembimbing_kampus.telepon, pembimbing_kampus.email, pembimbing_kampus.bidang_ilmu')
				->from('pembimbing_kampus')
				->from('(SELECT @n := 0) as t')
				->like('nip', $search, $regex ? 'both' : 'after')
				->or_like('pembimbing_kampus.nama', $search, $regex ? 'both' : 'after')
				->or_like('pembimbing_kampus.alamat', $search, $regex ? 'both' : 'after')
				->or_like('pembimbing_kampus.telepon', $search, $regex ? 'both' : 'after')
				->or_like('pembimbing_kampus.email', $search, $regex ? 'both' : 'after')
				->or_like('pembimbing_kampus.bidang_ilmu', $search, $regex ? 'both' : 'after')
				->order_by($order_column, $order_dir)
				->get()
				->result();

			$count = count($pempus);
			$pempus = array_slice($pempus, $start, $length);

			foreach ($pempus as $ids) {
				$ids->action = '<button type="button" class="btn btn-sm btn-outline-warning btn-block mr-2" onclick="showEditModal(' . $ids->id . ')"><i class="fa fa-edit"></i> Edit</button>';
			}

			die(
				json_encode([
					'success' => true,
					'data' => $pempus,
					'recordsTotal' => $this->db->count_all('pembimbing_kampus'),
					'recordsFiltered' => $count,
				])
			);
		} else {
			die(json_encode(['success' => false, 'message' => 'Method not allowed!']));
		}
	}

	public function get($id)
	{
		$ids = $this->m_pempus->get_by_id($id);
		if (!$ids) {
			die(
				json_encode([
					'success' => false,
					'message' => 'Pembimbing Kampus tidak ditemukan!',
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
			$nip = $this->input->post('nip');
			$nama = $this->input->post('nama');
			$alamat = $this->input->post('alamat');
			$telepon = $this->input->post('telepon');
			$email = $this->input->post('email');
			$bidang_ilmu = $this->input->post('bidang_ilmu');
			$pengguna_id = $this->input->post('pengguna_id');
			if (!$nip || !$nama || !$alamat || !$telepon || !$email || !$bidang_ilmu || $pengguna_id == -1) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Bad request.',
					])
				);
			}

			$pempus = $this->m_pempus->get_by_nip($nip);
			if ($pempus) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Pembimbing Kampus Sudah Terdaftar!',
					])
				);
			}

			$this->m_pempus->insert([
				'nip' => $nip,
				'nama' => $nama,
				'alamat' => $alamat,
				'telepon' => $telepon,
				'email' => $email,
				'bidang_ilmu' => $bidang_ilmu,
			]);

			$this->db->insert('user_pembimbing_kampus', [
				'user_id' => $pengguna_id,
				'nip' => $nip,
			]);

			die(
				json_encode([
					'success' => true,
					'message' => 'Pembimbing Kampus berhasil ditambahkan!',
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
			$nip = $this->input->post('nip');
			$nama = $this->input->post('nama');
			$alamat = $this->input->post('alamat');
			$telepon = $this->input->post('telepon');
			$email = $this->input->post('email');
			$bidang_ilmu = $this->input->post('bidang_ilmu');
			if (!$id || !$nip || !$nama || !$alamat || !$telepon || !$email || !$bidang_ilmu) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Bad request.',
					])
				);
			}

			$pempus = $this->m_pempus->get_by_id($id);
			if (!$pempus) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Pembimbing Kampus tidak ditemukan!',
					])
				);
			}

			$this->m_pempus->update($id, [
				'nip' => $nip,
				'nama' => $nama,
				'alamat' => $alamat,
				'telepon' => $telepon,
				'email' => $email,
				'bidang_ilmu' => $bidang_ilmu,
			]);

			die(
				json_encode([
					'success' => true,
					'message' => 'Pembimbing Kampus berhasil diubah!',
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
