<?php
defined('BASEPATH') or exit('No direct script access allowed');

include 'BaseAPI.php';

class MahasiswaBimbingan extends BaseAPI
{
    public function __construct()
    {
        parent::__construct();

        $this->user = $this->m_user->get_by_id($this->session->userdata('user_id'));
        if ($this->user->role == 'mahasiswa') {
            $this->mhs = $this->m_mhs->get_by_uid($this->user->id);
        } elseif ($this->user->role == 'pembimbing_kampus') {
            $this->pempus = $this->m_pempus->get_by_uid($this->user->id);
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

            $mhsbim = $this->db
                ->select('(@n := @n + 1) as no, mahasiswa.id, mahasiswa.nim, mahasiswa.nama, program_studi.nama as prodi, jurusan.nama as jurusan, tahap as status')
                ->from('mahasiswa')
                ->from('(SELECT @n := 0) AS t')
                ->join('program_studi', 'program_studi.id = mahasiswa.prodi_id')
                ->join('jurusan', 'jurusan.id = program_studi.jurusan_id')
                ->join('anggota_pkl', 'anggota_pkl.nim = mahasiswa.nim')
                ->join('pkl', 'pkl.id = anggota_pkl.pkl_id')
                ->group_start()
                ->like('mahasiswa.nim', $search, $regex ? 'both' : 'after')
                ->or_like('mahasiswa.nama', $search, $regex ? 'both' : 'after')
                ->group_end()
                ->where('anggota_pkl.nip', $this->pempus->nip)
                ->order_by($order_column, $order_dir)
                ->get()
                ->result();

            $count = count($mhsbim);
            $mhsbim = array_slice($mhsbim, $start, $length);

            foreach ($mhsbim as $mhs) {
                $status = $mhs->status;
                $mhs->status = '<center>';

                if ($status == '1') {
                    $status_mhsbim = 'Proses PKL';
                    $mhs->status .= '<span class="badge badge-warning text-white ml-1 mr-1">' . ucfirst($status_mhsbim) . '</span>';
                } else {
                    $status_mhsbim = 'Lulus';
                    $mhs->status .= '<span class="badge badge-success font-white ml-1 mr-1">' . ucfirst($status_mhsbim) . '</span>';
                }
            }

            die(json_encode([
                'success' => true,
                'data' => $mhsbim,
                'recordsTotal' => $this->db->count_all('mahasiswa'),
                'recordsFiltered' => $count,
            ]));
        } else {
            die(json_encode(['success' => false, 'message' => 'Method not allowed!']));
        }
    }
}
