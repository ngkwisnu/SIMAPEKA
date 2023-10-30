<?php
defined('BASEPATH') or exit('No direct script access allowed');

include 'BaseAPI.php';

class TandaTanganPembimbing extends BaseAPI
{
    public function __construct()
    {
        parent::__construct();

        $this->user = $this->m_user->get_by_id($this->session->userdata('user_id'));
        if ($this->user->role == 'mahasiswa') {
            $this->mhs = $this->m_mhs->get_by_uid($this->user->id);
        } elseif ($this->user->role == 'pembimbing_industri') {
            $this->pemdus = $this->m_pemdus->get_by_uid($this->user->id);
        } elseif ($this->user->role == 'pembimbing_kampus') {
            $this->pempus = $this->m_pempus->get_by_uid($this->user->id);
        }
    }

    public function add()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $nip = $this->pempus->nip;

            $config['upload_path'] = FCPATH . 'uploads\\ttd';
            $config['allowed_types'] = 'pdf|jpg|png|jpeg';
            $config['file_name'] = $nip;
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('file')) {
                die(json_encode([
                    'success' => false,
                    'message' => strip_tags($this->upload->display_errors()) . $config['upload_path']
                ]));
            }
            $filename = $this->upload->data('file_name');

            $this->m_bimbingan->insert_ttd_pempus([
                'file_ttd' => $filename,
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
    public function add1()
    {
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $nik = $this->pemdus->nik;

            $config['upload_path'] = FCPATH . 'uploads\\ttd';
            $config['allowed_types'] = 'pdf|jpg|png|jpeg';
            $config['file_name'] = $nik;
            $this->upload->initialize($config);

            if (!$this->upload->do_upload('file')) {
                die(json_encode([
                    'success' => false,
                    'message' => strip_tags($this->upload->display_errors()) . $config['upload_path']
                ]));
            }
            $filename = $this->upload->data('file_name');

            $this->m_aktivitas->insert_ttd_pemdus([
                'file_ttd' => $filename,
                'nik' => $nik
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
}
