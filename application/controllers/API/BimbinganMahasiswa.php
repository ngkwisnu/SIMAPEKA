<?php
defined('BASEPATH') or exit('No direct script access allowed');

include('BaseAPI.php');

class BimbinganMahasiswa extends BaseAPI
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
            if ($start === NULL || $length === NULL || $order == NULL) {
                die(json_encode(['success' => false, 'message' => 'Bad request.']));
            }
            $search = $this->input->get('search')['value'];
            $regex = $this->input->get('search')['regex'];

            $order_column = $this->input->get('columns')[$order[0]['column']]['data'];
            $order_dir = $order[0]['dir'];

            $bimbingan = $this->db->select('(@n := @n + 1) as no, bimbingan_pkl.id, tanggal, deskripsi, status')
                ->from('bimbingan_pkl')
                ->from('(SELECT @n := 0) AS t')
                ->join('mahasiswa', 'mahasiswa.nim = bimbingan_pkl.nim')
                ->group_start()
                ->like('tanggal', $search, $regex ? 'both' : 'after')
                ->or_like('deskripsi', $search, $regex ? 'both' : 'after')
                ->group_end()
                ->where('mahasiswa.nim', $this->mhs->nim)
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
                    $bim->status .= '<span class="badge badge-danger text-white ml-1 mr-1">' . $keterangan . '</span>';
                } elseif ($status == 'validasi') {
                    $keterangan = 'Lulus';
                    $bim->status .= '<span class="badge badge-success font-white ml-1 mr-1">' . $keterangan . '</span>';
                } elseif ($status == 'menunggu') {
                    $keterangan = 'Menunggu';
                    $bim->status .= '<span class="badge badge-warning font-white ml-1 mr-1">' . $keterangan . '</span>';
                }
                $bim->status .= '</div></center>';
                $bim->action = '<button type="button" class="btn btn-sm btn-info btn-block mr-2" onclick="showEditModal(' . $bim->id . ')"><i class="fas fa-arrow-right"></i> Detail</button>';
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

    public function add()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $tanggal = $this->input->post('tanggal');
            $deskripsi = $this->input->post('deskripsi');

            if (!$tanggal || !$deskripsi) {
                die(json_encode([
                    'success' => false,
                    'message' => 'Bad Request.'
                ]));
            }

            $pkl_id_result = $this->db->select('pkl_id')
                ->from('anggota_pkl')
                ->where('nim', $this->mhs->nim)
                ->get()
                ->row();

            $pkl_id = $pkl_id_result ? $pkl_id_result->pkl_id : null;

            $nip_result = $this->db->select('nip')
                ->from('anggota_pkl')
                ->where('pkl_id', $pkl_id)
                ->where('nim', $this->mhs->nim)
                ->get()
                ->row();

            $nip = $nip_result ? $nip_result->nip : null;

            $config['upload_path'] = FCPATH . 'uploads\\bimbingan';
            $config['allowed_types'] = '*';
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('file')) {
                die(json_encode([
                    'success' => false,
                    'message' => strip_tags($this->upload->display_errors()) . $config['upload_path']
                ]));
            }
            $filename = $this->upload->data('file_name');

            $this->m_bimbingan->insert([
                'tanggal' => $tanggal,
                'deskripsi' => $deskripsi,
                'status' => 'menunggu',
                'file' => $filename,
                'id_pkl' => $pkl_id,
                'nim' => $this->mhs->nim,
                'nip' => $nip
            ]);

            die(json_encode([
                'success' => true,
                'message' => 'Berkas Berhasil Diunggah'
            ]));
        } else {
            die(json_encode([
                'success' => false,
                'message' => 'Method not allowed!'
            ]));
        }
    }

    public function edit($id)
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $tanggal = $this->input->post('tanggal');
            $deskripsi = $this->input->post('deskripsi');

            if (!$tanggal || !$deskripsi) {
                die(json_encode([
                    'success' => false,
                    'message' => 'Bad Request.'
                ]));
            }

            $config['upload_path'] = FCPATH . 'uploads\\bimbingan';
            $config['allowed_types'] = '*';
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('file')) {
                die(json_encode([
                    'success' => false,
                    'message' => strip_tags($this->upload->display_errors()) . $config['upload_path']
                ]));
            }
            $filename = $this->upload->data('file_name');

            $this->m_bimbingan->update($id, [
                'tanggal' => $tanggal,
                'deskripsi' => $deskripsi,
                'file' => $filename,
                'status' => 'menunggu'
            ]);

            die(json_encode([
                'success' => true,
                'message' => 'Berkas Berhasil Diunggah'
            ]));
        } else {
            die(json_encode([
                'success' => false,
                'message' => 'Method not allowed!'
            ]));
        }
    }
};
