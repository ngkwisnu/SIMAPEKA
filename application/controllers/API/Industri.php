<?php
defined('BASEPATH') or exit('No direct script access allowed');

include 'BaseAPI.php';

class Industri extends BaseAPI
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

			$industri = $this->db
				->select('(@n := @n + 1) as no, id, nama, alamat, telepon, penanggung_jawab, google_maps, bidang_industri')
				->from('industri')
				->from('(SELECT @n := 0) as t')
				->like('nama', $search, $regex ? 'both' : 'after')
				->or_like('alamat', $search, $regex ? 'both' : 'after')
				->or_like('telepon', $search, $regex ? 'both' : 'after')
				->or_like('penanggung_jawab', $search, $regex ? 'both' : 'after')
				->or_like('bidang_industri', $search, $regex ? 'both' : 'after')
				->or_like('google_maps', $search, $regex ? 'both' : 'after')
				->order_by($order_column, $order_dir)
				->get()
				->result();

			$count = count($industri);
			$industri = array_slice($industri, $start, $length);

			foreach ($industri as $ids) {
				$ids->action = '<button type="button" class="btn btn-sm btn-success btn-block mr-2" onclick="showEditModal(' . $ids->id . ')">Detail & Edit</button>';
			}

			die(json_encode([
				'success' => true,
				'data' => $industri,
				'recordsTotal' => $this->db->count_all('industri'),
				'recordsFiltered' => $count,
			]));
		} else {
			die(json_encode(['success' => false, 'message' => 'Method not allowed!']));
		}
	}

	public function get($id)
	{
		$ids = $this->m_industri->get_by_id($id);
		if (!$ids) {
			die(json_encode([
				'success' => false,
				'message' => 'Industri tidak ditemukan!',
			]));
		}

		die(json_encode([
			'success' => true,
			'data' => $ids,
		]));
	}

	public function add()
	{
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$nama = $this->input->post('nama');
			$alamat = $this->input->post('alamat');
			$telepon = $this->input->post('telepon');
			$penanggung_jawab = $this->input->post('penanggung_jawab');
			$lokasi = $this->input->post('lokasi');
			$bidang_industri = $this->input->post('bidang_industri');

			if (!$nama || !$alamat || !$telepon || !$penanggung_jawab || !$lokasi || !$bidang_industri) {
				die(json_encode([
					'success' => false,
					'message' => 'Bad Request.',
				]));
			}

			$this->m_industri->insert([
				'nama' => $nama,
				'alamat' => $alamat,
				'telepon' => $telepon,
				'penanggung_jawab' => $penanggung_jawab,
				'google_maps' => $lokasi,
				'bidang_industri' => $bidang_industri,
			]);

			die(json_encode([
				'success' => true,
				'message' => 'Industri Berhasil Ditambahkan',
			]));
		} else {
			die(json_encode([
				'success' => false,
				'message' => 'Method not allowed!',
			]));
		}
	}

	public function edit($id)
	{
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$id = $this->input->post('id');
			$nama = $this->input->post('nama');
			$alamat = $this->input->post('alamat');
			$telepon = $this->input->post('telepon');
			$penanggung_jawab = $this->input->post('penanggung_jawab');
			$lokasi = $this->input->post('lokasi');
			$bidang_industri = $this->input->post('bidang_industri');

			// if (!$nama || !$alamat || !$telepon || !$penanggung_jawab || !$bidang_industri) {
			// 	die(json_encode([
			// 		'success' => false,
			// 		'message' => 'Bad Request.',
			// 	]));
			// }

			$industri = $this->m_industri->get_by_id($id);
			if (!$industri) {
				die(json_encode([
					'success' => false,
					'message' => 'Industri tidak ditemukan!',
				]));
			}

			$this->m_industri->update($id, [
				'nama' => $nama,
				'alamat' => $alamat,
				'telepon' => $telepon,
				'penanggung_jawab' => $penanggung_jawab,
				'google_maps' => $lokasi,
				'bidang_industri' => $bidang_industri,
			]);

			die(json_encode([
				'success' => true,
				'message' => 'Data Industri berhasil diubah.',
			]));
		} else {
			die(json_encode([
				'success' => false,
				'message' => 'Method not allowed!',
			]));
		}
	}
}
