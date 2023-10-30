<?php
defined('BASEPATH') or exit('No direct script access allowed');

include 'BaseAPI.php';

class Aktivitas extends BaseAPI
{
	public function __construct()
	{
		parent::__construct();

		$this->user = $this->m_user->get_by_id($this->session->userdata('user_id'));
		if ($this->user->role == 'mahasiswa') {
			$this->mhs = $this->m_mhs->get_by_uid($this->user->id);
		} elseif ($this->user->role == 'pembimbing_industri') {
			$this->pemdus = $this->m_pemdus->get_by_uid($this->user->id);
		}
	}

	public function index()
	{
		if ($this->input->server('REQUEST_METHOD') === 'GET') {
			if ($this->user->role == 'mahasiswa') {
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

				$aktivitas = $this->db
					->select('(@n := @n + 1) as no, id, tanggal, jenis_kegiatan, uraian_kegiatan, jam, validasi')
					->from('aktivitas_pkl')
					->from('(SELECT @n := 0) AS t')
					->join('anggota_pkl', 'anggota_pkl.nim = aktivitas_pkl.nim')
					->group_start()
					->like('tanggal', $search, $regex ? 'both' : 'after')
					->or_like('jenis_kegiatan', $search, $regex ? 'both' : 'after')
					->or_like('uraian_kegiatan', $search, $regex ? 'both' : 'after')
					->or_like('jam', $search, $regex ? 'both' : 'after')
					->group_end()
					->where('anggota_pkl.nim', $this->mhs->nim)
					->order_by($order_column, $order_dir)
					->get()
					->result();

				$count = count($aktivitas);
				$aktivitas = array_slice($aktivitas, $start, $length);

				foreach ($aktivitas as $akv) {
					$validasi = $akv->validasi;
					$akv->validasi = '<center>';

					if ($validasi == 'belum_validasi') {
						$validasi_aktivitas = 'Belum Validasi';
						$akv->validasi .= '<span class="badge badge-warning text-white ml-1 mr-1">' . ucfirst($validasi_aktivitas) . '</span>';
					} else {
						$validasi_aktivitas = 'Sudah Validasi';
						$akv->validasi .= '<span class="badge badge-success font-white ml-1 mr-1">' . ucfirst($validasi_aktivitas) . '</span>';
					}
					$akv->validasi .= '</center>';

					if ($validasi == 'belum_validasi') {
						$akv->action = '<button type="button" class="btn btn-sm btn-outline-warning btn-block mr-2" onclick="showEditModal(' . $akv->id . ')"><i class="fa fa-edit"></i> Edit</button>';
					} else {
						$akv->action = '<button type="button" class="btn btn-sm btn-outline-warning btn-block mr-2" onclick="showEditModal(' . $akv->id . ')" disabled><i class="fa fa-edit"></i> Edit</button>';
					}
				}

				die(
					json_encode([
						'success' => true,
						'data' => $aktivitas,
						'recordsTotal' => $this->db->where('nim', $this->mhs->nim)->count_all_results('aktivitas_pkl'),
						'recordsFiltered' => $count,
					])
				);
			} elseif ($this->user->role == 'pembimbing_industri') {
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

				$aktivitas = $this->db
					->select('(@n := @n + 1) as no, aktivitas_pkl.id, mahasiswa.nama, jenis_kegiatan, tanggal, jam, validasi')
					->from('aktivitas_pkl')
					->from('(SELECT @n := 0) AS t')
					->join('anggota_pkl', 'anggota_pkl.nim = aktivitas_pkl.nim')
					->join('mahasiswa', 'mahasiswa.nim = anggota_pkl.nim')
					->group_start()
					->like('tanggal', $search, $regex ? 'both' : 'after')
					->or_like('jenis_kegiatan', $search, $regex ? 'both' : 'after')
					->or_like('uraian_kegiatan', $search, $regex ? 'both' : 'after')
					->or_like('jam', $search, $regex ? 'both' : 'after')
					->group_end()
					->where('aktivitas_pkl.nik', $this->pemdus->nik)
					->order_by($order_column, $order_dir)
					->get()
					->result();

				$count = count($aktivitas);
				$aktivitas = array_slice($aktivitas, $start, $length);

				foreach ($aktivitas as $akv) {
					$validasi = $akv->validasi;
					$akv->validasi = '<center>';

					if ($validasi == 'belum_validasi') {
						$validasi_aktivitas = 'Belum Validasi';
						$akv->validasi .= '<span class="badge badge-warning text-white ml-1 mr-1">' . ucfirst($validasi_aktivitas) . '</span>';
					} else {
						$validasi_aktivitas = 'Sudah Validasi';
						$akv->validasi .= '<span class="badge badge-success font-white ml-1 mr-1">' . ucfirst($validasi_aktivitas) . '</span>';
					}
					$akv->validasi .= '</center>';
					$akv->action = '<button type="button" class="btn btn-sm btn-outline-primary btn-block mr-2" onclick="showEditModal(' . $akv->id . ')"><i class="fa fa-eye"></i> Detail</button>';
				}

				die(
					json_encode([
						'success' => true,
						'data' => $aktivitas,
						'recordsTotal' => $this->db->count_all('aktivitas_pkl'),
						'recordsFiltered' => $count,
					])
				);
			}
		} else {
			die(json_encode(['success' => false, 'message' => 'Method not allowed!']));
		}
	}

	public function get($id)
	{
		$akv = $this->m_aktivitas->get_by_id($id);
		if (!$akv) {
			die(
				json_encode([
					'success' => false,
					'message' => 'Aktivitas tidak ditemukan!',
				])
			);
		}

		die(
			json_encode([
				'success' => true,
				'data' => $akv,
			])
		);
	}

	public function add()
	{
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$tanggal = $this->input->post('tanggal');
			$jenis_kegiatan = $this->input->post('jenis_kegiatan');
			$uraian_kegiatan = $this->input->post('uraian_kegiatan');
			$jam = $this->input->post('jam');
			$status = 'belum_validasi';

			if (!$tanggal || !$jenis_kegiatan || !$uraian_kegiatan || !$jam || !$status) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Bad Request.',
					])
				);
			}

			$pkl_id_result = $this->db
				->select('pkl_id')
				->from('anggota_pkl')
				->where('nim', $this->mhs->nim)
				->get()
				->row();

			$pkl_id = $pkl_id_result ? $pkl_id_result->pkl_id : null;

			$nik_result = $this->db
				->select('nik')
				->from('pembimbing_industri')
				->join('industri', 'industri.id = pembimbing_industri.industri_id')
				->join('pkl', 'pkl.industri_id = industri.id')
				->where('pkl.id', $pkl_id)
				->get()
				->row();

			$nik = $nik_result ? $nik_result->nik : null;

			$this->m_aktivitas->insert([
				'tanggal' => $tanggal,
				'jenis_kegiatan' => $jenis_kegiatan,
				'uraian_kegiatan' => $uraian_kegiatan,
				'jam' => $jam,
				'validasi' => $status,
				'nim' => $this->mhs->nim,
				'pkl_id' => $pkl_id,
				'nik' => $nik,
			]);

			die(
				json_encode([
					'success' => true,
					'message' => 'Aktivitas Berhasil Ditambahkan',
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
			$tanggal = $this->input->post('tanggal');
			$jenis_kegiatan = $this->input->post('jenis_kegiatan');
			$uraian_kegiatan = $this->input->post('uraian_kegiatan');
			$jam = $this->input->post('jam');
			$verified = $this->input->post('verified');

			if (!$tanggal || !$jenis_kegiatan || !$uraian_kegiatan || !$jam) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Bad Request.',
					])
				);
			}

			$aktivitas = $this->m_aktivitas->get_by_id($id);
			if (!$aktivitas) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Aktivitas tidak ditemukan!',
					])
				);
			}

			if ($this->user->role == 'mahasiswa') {
				if ($aktivitas->nim != $this->mhs->nim) {
					die(
						json_encode([
							'success' => false,
							'message' => 'Anda tidak memiliki akses untuk mengubah aktivitas ini!',
						])
					);
				}

				if ($aktivitas->validasi == 'sudah_validasi') {
					die(
						json_encode([
							'success' => false,
							'message' => 'Aktivitas sudah divalidasi!',
						])
					);
				}
			}

			$this->m_aktivitas->update($id, [
				'tanggal' => $tanggal,
				'jenis_kegiatan' => $jenis_kegiatan,
				'uraian_kegiatan' => $uraian_kegiatan,
				'jam' => $jam,
				'validasi' => $verified ? 'sudah_validasi' : 'belum_validasi',
			]);

			die(
				json_encode([
					'success' => true,
					'message' => $verified ? 'Aktivitas berhasil divalidasi.' : 'Aktivitas berhasil diubah.',
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
