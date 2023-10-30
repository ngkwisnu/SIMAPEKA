<?php
defined('BASEPATH') or exit('No direct script access allowed');

include 'BaseAPI.php';

class NilaiBimbingan extends BaseAPI
{
	public function __construct()
	{
		parent::__construct();

		$this->user = $this->m_user->get_by_id($this->session->userdata('user_id'));
		if ($this->user->role == 'mahasiswa') {
			$this->mhs = $this->m_mhs->get_by_uid($this->user->id);
		} elseif ($this->user->role == 'pembimbing_industri') {
			$this->pem = $this->m_pemdus->get_by_uid($this->user->id);
		} elseif ($this->user->role == 'pembimbing_kampus') {
			$this->pemkam = $this->m_pempus->get_by_uid($this->user->id);
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

			$daftar_nilai = $this->db
				->select('nilai_mahasiswa.id as id, mahasiswa.nama, mahasiswa.nim, industri.nama as nama_industri')
				->from('nilai_mahasiswa')
				->join('anggota_pkl', 'anggota_pkl.nim = nilai_mahasiswa.nim')
				->join('mahasiswa', 'mahasiswa.nim = nilai_mahasiswa.nim')
				->join('pkl', 'pkl.id = anggota_pkl.pkl_id')
				->join('industri', 'industri.id = pkl.industri_id')
				->where('anggota_pkl.nip', $this->pemkam->nip)
				// ->order_by($order_column, $order_dir)
				->get()
				->result();

			$count = count($daftar_nilai);
			$daftar_nilai = array_slice($daftar_nilai, $start, $length);

			foreach ($daftar_nilai as $nilai) {
				$nilai->action = '<button type="button" class="btn btn-sm btn-outline-success btn-block mr-2" onclick="showEditModal(' . $nilai->id . ')"><i class="fa fa-edit"></i> Nilai</button>';
			}

			die(
				json_encode([
					'success' => true,
					'data' => $daftar_nilai,
					'recordsTotal' => $this->db->count_all('pkl'),
					'recordsFiltered' => $count,
				])
			);
		} else {
			die(json_encode(['success' => false, 'message' => 'Method not allowed!']));
		}
	}

  public function get($id)
  {
    $nilai = $this->m_nilai->get_by_id($id);
    if (!$nilai) {
      die(json_encode([
        'success' => false,
        'message' => 'Nilai tidak ditemukan!'
      ]));
    }

    die(json_encode([
      'success' => true,
      'data' => $nilai
    ]));
  }

	public function edit($id)
	{
		if ($this->input->server('REQUEST_METHOD') === 'POST') {
			$motivasi = $this->input->post('motivasi');
			$kreativitas = $this->input->post('kreativitas');
			$disiplin = $this->input->post('disiplin');
			$metode = $this->input->post('metode');

			if (!$motivasi || !$kreativitas || !$disiplin || !$metode) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Bad Request.',
					])
				);
			}

      $mhs = $this->m_nilai->get_by_id($id);
      if (!$mhs) {
        die(json_encode([
          'success' => false,
          'message' => 'Mahasiswa tidak ditemukan!'
        ]));
      }

			$id_nilai_result = $this->db
				->select('id')
				->from('nilai_mahasiswa')
				->where('id', $id)
				->get()
				->row();

			$id_nilai = $id_nilai_result ? $id_nilai_result->id : null;

			$this->m_nilai->insert_nilai_kampus([
				'id_nilai' => $id_nilai,
				'motivasi' => $motivasi,
				'kreativitas' => $kreativitas,
				'disiplin' => $disiplin,
				'metode' => $metode,
			]);

			die(
				json_encode([
					'success' => true,
					'message' => 'Nilai Berhasil Disimpan',
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
