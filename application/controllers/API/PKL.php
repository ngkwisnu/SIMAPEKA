<?php
defined('BASEPATH') or exit('No direct script access allowed');

include 'BaseAPI.php';

class PKL extends BaseAPI
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

			$data = $this->db
				->select('(@n := @n + 1) as no, pkl.id as id, mahasiswa.nama, mahasiswa.email, industri.nama as nama_industri, industri.alamat as alamat_industri, industri.telepon as telepon, pkl.tahap')
				->from('pkl')
				->from('(SELECT @n := 0) as t')
				->join('industri', 'industri.id = pkl.industri_id')
				->join('anggota_pkl', 'anggota_pkl.pkl_id = pkl.id')
				->join('mahasiswa', 'mahasiswa.nim = anggota_pkl.nim')
				->group_start()
				->like('mahasiswa.nama', $search, $regex ? 'both' : 'after')
				->or_like('mahasiswa.email', $search, $regex ? 'both' : 'after')
				->or_like('industri.nama', $search, $regex ? 'both' : 'after')
				->or_like('industri.alamat', $search, $regex ? 'both' : 'after')
				->or_like('industri.telepon', $search, $regex ? 'both' : 'after')
				->group_end()
				->where('pkl.tahap', 0)
				->order_by($order_column, $order_dir)
				->get()
				->result();

			$sql = $this->db->last_query();

			$count = count($data);
			$pkl = array_slice($data, $start, $length);

			// Ubah array objek stdClass menjadi array asosiatif berdasarkan kunci unik (id)
			$associativeArray = array_reduce($data, function ($result, $item) {
				$result[$item->id] = $item;
				return $result;
			}, array());

			// Menghitung berapa kali setiap elemen muncul dalam array asosiatif
			$countedData = array_count_values(array_map(function ($item) {
				return serialize($item);
			}, $associativeArray));

			// Mengidentifikasi objek yang merupakan duplikat berdasarkan hasil perhitungan
			$duplicateObjects = array_filter($countedData, function ($count) {
				return $count > 1;
			});

			// Menentukan objek duplikat berdasarkan properti unik (id)
			$uniqueIds = array();
			$duplicateObjects = array();

			foreach ($data as $item) {
				if (in_array($item->id, $uniqueIds)) {
					$duplicateObjects[] = $item;
				} else {
					$uniqueIds[] = $item->id;
				}
			}

			// Jika Anda ingin menghapus duplikat dari $data, gunakan $uniqueIds untuk memfilternya
			$data = array_filter($data, function ($item) use ($uniqueIds) {
				return in_array($item->id, $uniqueIds);
			});

			// Jika Anda ingin menambahkan properti action hanya untuk objek duplikat
			foreach ($duplicateObjects as $p) {
				$p->action = '<button type="button" class="btn btn-sm btn-outline-primary btn-block mr-2" onclick="LihatPengajuan(' . $p->id . ')"><i class="fa fa-eye"></i> Lihat</button>';
			}

			die(json_encode([
				'success' => true,
				'data' => $duplicateObjects,
				'recordsTotal' => $this->db->count_all('pkl'),
				'recordsFiltered' => $count,
			]));
		} else {
			die(json_encode(['success' => false, 'message' => 'Method not allowed!']));
		}
	}

	public function get($id)
	{
		$pkl = $this->m_pkl->get_by_id($id);
		if (!$pkl) {
			die(json_encode([
				'success' => false,
				'message' => 'Maaf, Data Tidak Ditemukan!',
			]));
		}

		die(json_encode([
			'success' => true,
			'data' => $pkl,
		]));
	}

	public function cekanggota($id)
	{
		$anggota = $this->m_pkl->cek_anggota($id);
		if (!$anggota) {
			die(json_encode([
				'success' => false,
				'message' => 'Maaf, Data Tidak Ditemukan!',
			]));
		}

		die(json_encode([
			'success' => true,
			'data' => $anggota,
		]));
	}

	public function cari_tempat()
	{
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$nama_tempat = $this->input->post('nama_tempat');
			$alamat_tempat = $this->input->post('alamat_tempat');
			$kontak_tempat = $this->input->post('kontak_tempat');

			$result = $this->db
				->select('id, nama, alamat, telepon, penanggung_jawab, google_maps, bidang_industri')
				->from('industri')
				->like('nama', $nama_tempat, 'both')
				->get()
				->result();

			if (count($result) > 0) {
				die(json_encode([
					'success' => true,
					'data' => $result,
				]));
			} else {
				die(json_encode([
					'success' => false,
				]));
			}
		} else {
			die(json_encode([
				'success' => false,
				'message' => 'Method not allowed!',
			]));
		}
	}

	public function daftar()
	{
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$id_tempat = $this->input->post('id_tempat');
			$nama_tempat = $this->input->post('nama_tempat');
			$alamat_tempat = $this->input->post('alamat_tempat');
			$kontak_tempat = $this->input->post('kontak_tempat');
			if (!$nama_tempat || !$alamat_tempat || !$kontak_tempat) {
				if (!isset($id_tempat)) {
					die(json_encode([
						'success' => false,
						'message' => 'Bad request.',
					]));
				}
			}
			$anggota = $this->input->post('anggota');

			$industri_id = -1;
			if (isset($id_tempat) && !empty($id_empty)) {
				$industri = $this->m_industri->get_by_id($id_tempat);
				if (!$industri) {
					die(json_encode([
						'success' => false,
						'message' => 'Bad request.',
					]));
				} else {
					$industri_id = $id_tempat;
				}
			} else {
				$industri_id = $this->m_industri->insert([
					'nama' => $nama_tempat,
					'alamat' => $alamat_tempat,
					'telepon' => $kontak_tempat,
				]);
			}

			if (!$industri_id) {
				die(json_encode([
					'success' => false,
					'message' => 'Error (1)',
				]));
			}

			$pkl_id = $this->m_pkl->insert([
				'industri_id' => $industri_id,
				'tahap' => count($anggota) > 0 ? -1 : 0,
				'status' => 'pending',
			]);

			if (!$pkl_id) {
				die(json_encode([
					'success' => false,
					'message' => 'Error (2)',
				]));
			}

			$this->m_pkl->tambah_anggota($pkl_id, $this->mhs->nim);
			die(json_encode([
				'success' => true,
				'message' => 'Pendaftaran berhasil, silahkan menunggu validasi admin!',
			]));
		} else {
			die(json_encode([
				'success' => false,
				'message' => 'Method not allowed!',
			]));
		}
	}

	public function terima($id)
	{
		if ($this->input->server('REQUEST_METHOD') === 'GET') {
			if (!$id) {
				die(json_encode([
					'success' => false,
					'message' => 'Bad request.',
				]));
			}

			$pkl = $this->m_pkl->get_by_id($id);
			if (!$pkl) {
				die(json_encode([
					'success' => false,
					'message' => 'Data tidak ditemukan.',
				]));
			}

			$this->m_pkl->update($id, [
				'tahap' => 2, // nanti jadiin 1
				'updated_at' => date('Y-m-d H:i:s', time()),
			]);

			$anggotas = $this->db
				->select('anggota_pkl.nim')
				->from('anggota_pkl')
				->where('pkl_id', $id)
				->get()
				->result();

			foreach ($anggotas as $anggota) {
				$this->m_nilai->insert([
					'nim' => $anggota->nim,
				]);
			}

			die(json_encode([
				'success' => true,
				'message' => 'Pengajuan berhasil diterima!',
			]));
		} else {
			die(json_encode([
				'success' => false,
				'message' => 'Method not allowed!',
			]));
		}
	}

	function tolak($id)
	{
		if ($this->input->server('REQUEST_METHOD') === 'GET') {
			if (!$id) {
				die(json_encode([
					'success' => false,
					'message' => 'Bad request.',
				]));
			}

			$pkl = $this->m_pkl->get_by_id($id);
			if (!$pkl) {
				die(json_encode([
					'success' => false,
					'message' => 'Data tidak ditemukan.',
				]));
			}

			$this->m_pkl->update($id, [
				'tahap' => -1,
				'updated_at' => date('Y-m-d H:i:s', time()),
			]);

			die(json_encode([
				'success' => true,
				'message' => 'Pengajuan berhasil ditolak!',
			]));
		} else {
			die(json_encode([
				'success' => false,
				'message' => 'Method not allowed!',
			]));
		}
	}

	function terima_undangan($id)
	{
		if ($this->input->server('REQUEST_METHOD') === 'GET') {
			if (!$id) {
				die(json_encode([
					'success' => false,
					'message' => 'Bad request.',
				]));
			}

			$pkl = $this->m_pkl->get_by_id($id);
			if (!$pkl) {
				die(json_encode([
					'success' => false,
					'message' => 'Data tidak ditemukan.',
				]));
			}

			if (!$this->db->update('undangan', [
				'status' => 'diterima'
			], [
				'nim' => $this->mhs->nim,
				'pkl_id' => $id
			])) {
				die(json_encode([
					'success' => false,
					'message' => 'Terjadi kesalahan!',
				]));
			}

			$this->m_pkl->tambah_anggota($id, $this->mhs->nim);

			$undangan = $this->db
				->select('*')
				->from('undangan')
				->where('pkl_id', $id)
				->where('status', NULL)
				->count_all_results();

			if ($undangan == 0) {
				$this->m_pkl->update($id, [
					'tahap' => 0
				]);
			}

			die(json_encode([
				'success' => true,
				'message' => 'Undangan berhasil diterima!',
			]));
		} else {
			die(json_encode([
				'success' => false,
				'message' => 'Method not allowed!',
			]));
		}
	}

	function tolak_undangan($id)
	{
		if ($this->input->server('REQUEST_METHOD') === 'GET') {
			if (!$id) {
				die(json_encode([
					'success' => false,
					'message' => 'Bad request.',
				]));
			}

			$pkl = $this->m_pkl->get_by_id($id);
			if (!$pkl) {
				die(json_encode([
					'success' => false,
					'message' => 'Data tidak ditemukan.',
				]));
			}

			if (!$this->db->update('undangan', [
				'status' => 'ditolak'
			], [
				'nim' => $this->mhs->nim,
				'pkl_id' => $id
			])) {
				die(json_encode([
					'success' => false,
					'message' => 'Terjadi kesalahan!',
				]));
			}

			$undangan = $this->db
				->select('*')
				->from('undangan')
				->where('pkl_id', $id)
				->where('status', NULL)
				->count_all_results();

			if ($undangan == 0) {
				$this->m_pkl->update($id, [
					'tahap' => 0
				]);
			}

			die(json_encode([
				'success' => true,
				'message' => 'Undangan berhasil ditolak!',
			]));
		} else {
			die(json_encode([
				'success' => false,
				'message' => 'Method not allowed!',
			]));
		}
	}
}
