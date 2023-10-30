<?php
defined('BASEPATH') or exit('No direct script access allowed');

include 'BaseAPI.php';

class Mahasiswa extends BaseAPI
{
	public function __construct()
	{
		parent::__construct();

		$this->user = $this->m_user->get_by_id($this->session->userdata('user_id'));
		if ($this->user->role == 'mahasiswa') {
			$this->mhs = $this->m_mhs->get_by_uid($this->user->id);
		}
	}

	function numberToRoman($number)
	{
		$romanSymbols = [
			'M' => 1000,
			'CM' => 900,
			'D' => 500,
			'CD' => 400,
			'C' => 100,
			'XC' => 90,
			'L' => 50,
			'XL' => 40,
			'X' => 10,
			'IX' => 9,
			'V' => 5,
			'IV' => 4,
			'I' => 1,
		];

		$roman = '';
		foreach ($romanSymbols as $symbol => $value) {
			while ($number >= $value) {
				$roman .= $symbol;
				$number -= $value;
			}
		}

		return $roman;
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

			$mahasiswa = $this->db
				->select('(@n := @n + 1) as no, mahasiswa.id, nim, mahasiswa.nama, program_studi.nama as prodi, semester')
				->from('mahasiswa')
				->from('(SELECT @n := 0) as t')
				->join('program_studi', 'program_studi.id = mahasiswa.prodi_id')
				->like('nim', $search, $regex ? 'both' : 'after')
				->or_like('mahasiswa.nama', $search, $regex ? 'both' : 'after')
				->or_like('program_studi.nama', $search, $regex ? 'both' : 'after')
				->order_by($order_column, $order_dir)
				->get()
				->result();

			$count = count($mahasiswa);
			$mahasiswa = array_slice($mahasiswa, $start, $length);

			foreach ($mahasiswa as $mhs) {
				$mhs->semester = $this->numberToRoman($mhs->semester);
				$mhs->action = '<button type="button" class="btn btn-sm btn-outline-warning btn-block mr-2" onclick="showEditModal(' . $mhs->id . ')"><i class="fa fa-edit"></i> Edit</button>';
			}

			die(
				json_encode([
					'success' => true,
					'data' => $mahasiswa,
					'recordsTotal' => $this->db->count_all('mahasiswa'),
					'recordsFiltered' => $count,
				])
			);
		} else {
			die(json_encode(['success' => false, 'message' => 'Method not allowed!']));
		}
	}

	public function get($id)
	{
		$mhs = $this->m_mhs->get_by_id($id);
		if (!$mhs) {
			die(
				json_encode([
					'success' => false,
					'message' => 'Mahasiswa tidak ditemukan!',
				])
			);
		}

		$mhs->prodi = $this->db
			->select('nama')
			->from('program_studi')
			->where('id', $mhs->prodi_id)
			->get()
			->row()->nama;

		die(
			json_encode([
				'success' => true,
				'data' => $mhs,
			])
		);
	}

	public function add()
	{
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$nim = $this->input->post('nim');
			$nama = $this->input->post('nama');
			$email = $this->input->post('email');
			$nomor_hp = $this->input->post('nomor_hp');
			$alamat = $this->input->post('alamat');
			$prodi_id = $this->input->post('prodi_id');
			$semester = $this->input->post('semester');
			if (!$nim || !$nama || !$email || !$nomor_hp || !$alamat || !$prodi_id || !$semester) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Bad request.',
					])
				);
			}

			$mhs = $this->m_mhs->get_by_nim($nim);
			if ($mhs) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Mahasiswa Sudah Terdaftar!',
					])
				);
			}

			$this->m_mhs->insert([
				'nim' => $nim,
				'nama' => $nama,
				'email' => $email,
				'nomor_hp' => $nomor_hp,
				'alamat' => $alamat,
				'prodi_id' => $prodi_id,
				'semester' => $semester,
			]);

			die(
				json_encode([
					'success' => true,
					'message' => 'Mahasiswa berhasil ditambahkan!',
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
			$nim = $this->input->post('nim');
			$nama = $this->input->post('nama');
			$email = $this->input->post('email');
			$nomor_hp = $this->input->post('nomor_hp');
			$alamat = $this->input->post('alamat');
			$prodi = $this->input->post('prodi');
			$semester = $this->input->post('semester');
			if (!$id || !$nim || !$nama || !$email || !$nomor_hp || !$alamat || !$prodi || !$semester) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Bad request.',
					])
				);
			}

			$mhs = $this->m_mhs->get_by_id($id);
			if (!$mhs) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Mahasiswa tidak ditemukan!',
					])
				);
			}

			$this->m_mhs->update($id, [
				'nim' => $nim,
				'nama' => $nama,
				'email' => $email,
				'nomor_hp' => $nomor_hp,
				'alamat' => $alamat,
				'prodi_id' => $prodi,
				'semester' => $semester,
			]);

			die(
				json_encode([
					'success' => true,
					'message' => 'Data Mahasiswa berhasil diubah!',
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
