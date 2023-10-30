<?php
defined('BASEPATH') or exit('No direct script access allowed');

include('BaseAPI.php');

class BimbinganPKL extends BaseAPI
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

            $bimbingan = $this->db->select('(@n := @n + 1) as no, bimbingan_pkl.id, mahasiswa.nama as nama_mahasiswa, deskripsi, file, status')
                ->from('bimbingan_pkl')
                ->from('(SELECT @n := 0) AS t')
                ->join('pembimbing_kampus', 'pembimbing_kampus.nip = bimbingan_pkl.nip')
                ->join('mahasiswa', 'mahasiswa.nim = bimbingan_pkl.nim')
                ->group_start()
                ->like('tanggal', $search, $regex ? 'both' : 'after')
                ->or_like('deskripsi', $search, $regex ? 'both' : 'after')
                ->group_end()
                ->where('pembimbing_kampus.nip', $this->pempus->nip)
                ->group_start()
                ->where('bimbingan_pkl.status', 'menunggu')
                ->or_where('bimbingan_pkl.status', 'revisi')
                ->group_end()
                ->order_by($order_column, $order_dir)
                ->get()
                ->result();

            $count = count($bimbingan);
            $bimbingan = array_slice($bimbingan, $start, $length);

            foreach ($bimbingan as $bim) {
                $status = $bim->status;
                $bim->status = '<div class="justify-content">';

                if ($status == 'revisi') {
                    $keterangan = 'Revisi';
                    $bim->status .= '<span class="badge badge-danger text-white ml-1 mr-1">' . ucfirst($keterangan) . '</span>';
                } elseif ($status == 'validasi') {
                    $keterangan = 'Lulus';
                    $bim->status .= '<span class="badge badge-success font-white ml-1 mr-1">' . ucfirst($keterangan) . '</span>';
                } elseif ($status == 'menunggu') {
                    $keterangan = 'Waiting';
                    $bim->status .= '<span class="badge badge-warning font-white ml-1 mr-1">' . ucfirst($keterangan) . '</span>';
                }
                $bim->status .= '</div></center>';
                $bim->action = '<button type="button" class="btn btn-sm btn-outline-primary btn-block mr-2" onclick="showEditModal(' . $bim->id . ')"><i class="fas fa-arrow-right"></i> Detail</button>';
            }

            die(json_encode([
                'success' => true,
                'data' => $bimbingan,
                'recordsTotal' => $this->db->count_all('bimbingan_pkl'),
                'recordsFiltered' => $count
            ]));
        } else {
            die(json_encode(['success' => false, 'message' => 'Method not allowed!']));
        }
    }

    public function get($id)
    {
        $bim = $this->m_bimbingan->get_by_id($id);
        if (!$bim) {
            die(json_encode([
                'success' => false,
                'message' => 'Bimbingan tidak ditemukan!'
            ]));
        }
        die(json_encode([
            'success' => true,
            'data' => $bim
        ]));
    }

    public function edit($id)
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $uraian = $this->input->post('uraian');
            $validasi = $this->input->post('status');

            $bim = $this->m_bimbingan->get_by_id($id);
            if (!$bim) {
                die(json_encode([
                    'success' => false,
                    'message' => 'Bimbingan tidak ditemukan!'
                ]));
            }

            $this->m_bimbingan->update($id, [
                'status' => $validasi,
                'uraian' => $uraian
            ]);

            die(json_encode([
                'success' => true,
                'message' => 'Bimbingan Telah Di cek!'
            ]));
        } else {
            die(json_encode([
                'success' => false,
                'message' => 'Method not allowed!'
            ]));
        }
    }
};
