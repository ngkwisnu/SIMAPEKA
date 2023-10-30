<?php
defined('BASEPATH') or exit('No direct script access allowed');

include('BaseAPI.php');

class PilihPembimbing extends BaseAPI
{
  public function __construct()
  {
    parent::__construct();

    $this->user = $this->m_user->get_by_id($this->session->userdata('user_id'));
    if ($this->user->role == 'mahasiswa') {
      $this->mhs = $this->m_mhs->get_by_uid($this->user->id);
    } elseif ($this->user->role == 'pembimbing_kampus') {
      # code...
      $this->pempus = $this->m_pempus->get_by_uid($this->user->id);
    }
  }

  public function index()
  {
    if ($this->input->server('REQUEST_METHOD') === 'GET') {
      $start = $this->input->get('start');
      $length = $this->input->get('length');
      $order = $this->input->get('order');
      if ($start === NULL || $length === NULL || $order == NULL) {
        die(json_encode(['success' => false, 'message' => 'Bad request.']));
      }
      $search = $this->input->get('search')['value'];
      $regex = $this->input->get('search')['regex'];

      $order_column = $this->input->get('columns')[$order[0]['column']]['data'];
      $order_dir = $order[0]['dir'];

      $pilih_dosen = $this->db->select('(@n := @n + 1) as no, pkl_id as id, mahasiswa.nama as nama_mahasiswa, anggota_pkl.nim, program_studi.nama as prodi, nip as status')
        ->from('anggota_pkl')
        ->from('(SELECT @n := 0) AS t')
        ->join('mahasiswa', 'mahasiswa.nim = anggota_pkl.nim')
        ->join('program_studi', 'program_studi.id = mahasiswa.prodi_id')
        ->group_start()
        ->like('mahasiswa.nama', $search, $regex ? 'both' : 'after')
        ->or_like('anggota_pkl.nim', $search, $regex ? 'both' : 'after')
        ->or_like('nip', $search, $regex ? 'both' : 'after')
        ->or_like('program_studi.nama', $search, $regex ? 'both' : 'after')
        ->group_end()
        ->where('nip IS NULL')
        ->order_by($order_column, $order_dir)
        ->get()
        ->result();

      $count = count($pilih_dosen);
      $pilih_dosen = array_slice($pilih_dosen, $start, $length);

      foreach ($pilih_dosen as $set) {
        $status = $set->status;
        $set->status = '<div class="justify-content">';

        if ($status == null) {
          $keterangan = 'Menunggu Pembimbing';
          $set->status .= '<span class="badge badge-warning text-white ml-1 mr-1">' . ucfirst($keterangan) . '</span>';
        }
        $set->status .= '</div></center>';
        $set->action = '<button type="button" class="btn btn-sm btn-outline-success btn-block mr-2" onclick="showEditModal(' . $set->id . ')"><i class="fas fa-arrow-right"></i> Pilih</button>';
      }

      die(json_encode([
        'success' => true,
        'data' => $pilih_dosen,
        'recordsTotal' => $this->db->count_all('anggota_pkl'),
        'recordsFiltered' => $count
      ]));
    } else {
      die(json_encode(['success' => false, 'message' => 'Method not allowed!']));
    }
  }

  public function get($id)
  {
    $set = $this->m_pkl->get_id_anggota($id);
    die(json_encode([
      'success' => true,
      'data' => $set
    ]));
  }

  public function edit($id)
  {
    if ($this->input->server('REQUEST_METHOD') === 'POST') {
      $status = $this->input->post('status');
      $nim = $this->input->post('nim');

      $set = $this->m_pkl->get_id_anggota($id);
      if (!$set) {
        die(json_encode([
          'success' => false,
          'message' => 'Mahasiswa tidak ditemukan!'
        ]));
      }

      $this->m_pkl->update_anggota($nim, [
        'nip' => $status
      ]);

      die(json_encode([
        'success' => true,
        'message' => 'Pemilihan Pembimbing Sukses!'
      ]));
    } else {
      die(json_encode([
        'success' => false,
        'message' => 'Method not allowed!'
      ]));
    }
  }
};
