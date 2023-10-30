<?php
defined('BASEPATH') or exit('No direct script access allowed');

include 'BaseController.php';

class DashboardController extends BaseController
{
	public function __construct()
	{
		parent::__construct();

		$this->user = $this->m_user->get_by_id($this->session->userdata('user_id'));
		if ($this->user->picture == null) {
			$this->user->picture = 'https://picsum.photos/200';
		}
		$this->mhs = $this->m_mhs->get_by_uid($this->user->id);
	}

	public function index()
	{
		$this->load->view('dashboard', [
			'user' => $this->user,
			'nama' => $this->user->role == 'mahasiswa' ? $this->mhs->nama : $this->user->username,
		]);
	}

	public function page()
	{
		if ($this->input->server('REQUEST_METHOD') == 'GET') {
			$id = $this->input->get('id');

			if ($id == 'dashboard') {
				$view = $this->load->view(
					'dashboard/pages/dashboard',
					[
						'user' => $this->user,
						'nama' => $this->user->role == 'mahasiswa' ? $this->mhs->nama : $this->user->username,
					],
					true
				);
				die(json_encode([
					'success' => true,
					'view' => $view,
					'view_name' => 'Dashboard',
				]));
			}

			// ============================= ADMIN
			if ($this->user->role == 'admin') {
				if ($id == 'data_pengguna') {
					$view = $this->load->view(
						'dashboard/pages/admin/data_pengguna',
						[
							'user' => $this->user,
						],
						true
					);

					die(json_encode([
						'success' => true,
						'view' => $view,
						'view_name' => 'Data Pengguna',
					]));
				}

				if ($id == 'data_mahasiswa') {
					$view = $this->load->view(
						'dashboard/pages/admin/data_mahasiswa',
						[
							'user' => $this->user,
						],
						true
					);

					die(json_encode([
						'success' => true,
						'view' => $view,
						'view_name' => 'Data Mahasiswa',
					]));
				}

				if ($id == 'data_industri') {
					$view = $this->load->view(
						'dashboard/pages/admin/data_industri',
						[
							'user' => $this->user,
						],
						true
					);

					die(json_encode([
						'success' => true,
						'view' => $view,
						'view_name' => 'Data Industri',
					]));
				}

				if ($id == 'data_pembimbing_industri') {
					$view = $this->load->view(
						'dashboard/pages/admin/data_pembimbing_industri',
						[
							'user' => $this->user,
						],
						true
					);

					die(json_encode([
						'success' => true,
						'view' => $view,
						'view_name' => 'Data Pembimbing Industri',
					]));
				}

				if ($id == 'data_pembimbing_kampus') {
					$view = $this->load->view(
						'dashboard/pages/admin/data_pembimbing_kampus',
						[
							'user' => $this->user,
						],
						true
					);

					die(json_encode([
						'success' => true,
						'view' => $view,
						'view_name' => 'Data Pembimbing Kampus',
					]));
				}

				if ($id == 'pengajuan_pkl') {
					$view = $this->load->view(
						'dashboard/pages/admin/pengajuan_pkl',
						[
							'user' => $this->user,
						],
						true
					);

					die(json_encode([
						'success' => true,
						'view' => $view,
						'view_name' => 'Pengajuan PKL',
					]));
				}

				if ($id == 'pilih_pembimbing') {
					$view = $this->load->view(
						'dashboard/pages/admin/pilih_pembimbing',
						[
							'user' => $this->user,
						],
						true
					);

					die(json_encode([
						'success' => true,
						'view' => $view,
						'view_name' => 'Pemilihan Pembimbing Kampus',
					]));
				}
			}

			// =============================== MAHASISWA
			if ($this->user->role == 'mahasiswa') {
				if ($id == 'daftar_pkl') {
					$mahasiswas = $this->db
						->select('(@n := @n + 1) as no, mahasiswa.id, mahasiswa.nim, mahasiswa.nama, program_studi.nama as prodi, mahasiswa.semester')
						->from('mahasiswa')
						->from('(SELECT @n := 0) as t')
						->join('program_studi', 'program_studi.id = mahasiswa.prodi_id')
						->join('user_mahasiswa', 'user_mahasiswa.nim = mahasiswa.nim')
						->where('mahasiswa.nim NOT IN (SELECT nim FROM anggota_pkl WHERE pkl_id IN (SELECT id FROM pkl WHERE tahap <= 99))')
						->get()
						->result();

					$view = $this->load->view(
						'dashboard/pages/mahasiswa/daftar_pkl',
						[
							'user' => $this->user,
							'mahasiswa' => $mahasiswas,
						],
						true
					);
					die(json_encode([
						'success' => true,
						'view' => $view,
						'view_name' => 'Daftar PKL',
					]));
				}

				if ($id == 'aktivitas_pkl') {
					$view = $this->load->view(
						'dashboard/pages/mahasiswa/aktivitas_pkl',
						[
							'user' => $this->user,
						],
						true
					);
					die(json_encode([
						'success' => true,
						'view' => $view,
						'view_name' => 'Aktivitas PKL',
					]));
				}

				if ($id == 'bimbingan') {
					$view = $this->load->view(
						'dashboard/pages/mahasiswa/bimbingan',
						[
							'user' => $this->user,
						],
						true
					);
					die(json_encode([
						'success' => true,
						'view' => $view,
						'view_name' => 'Bimbingan PKL',
					]));
				}

				if ($id == 'nilai_pkl') {
					$nilai1 = $this->db
						->select('motivasi, kreativitas, disiplin, metode')
						->from('nilai_kampus')
						->join('nilai_mahasiswa', 'nilai_mahasiswa.id = nilai_kampus.id_nilai')
						->where('nilai_mahasiswa.nim', $this->mhs->nim)
						->get()
						->row();
					$nilai2 = $this->db
						->select('kemampuan_kerja, disiplin, komunikasi, inisiatif, kreativitas, kerjasama')
						->from('nilai_pkl')
						->join('nilai_mahasiswa', 'nilai_mahasiswa.id = nilai_pkl.id_nilai')
						->where('nilai_mahasiswa.nim', $this->mhs->nim)
						->get()
						->row();
					$view = $this->load->view(
						'dashboard/pages/mahasiswa/nilai_pkl',
						[
							'user' => $this->user,
							'nilai1' => $nilai1,
							'nilai2' => $nilai2,
						],
						true
					);
					die(json_encode([
						'success' => true,
						'view' => $view,
						'view_name' => 'Nilai PKL',
					]));
				}

				if ($id == 'proses_pkl') {
					$letters = [];

					$result = $this->db
						->select('pkl.id as pkl_id, pkl.tahap, pkl.status')
						->from('pkl')
						->join('anggota_pkl', 'anggota_pkl.pkl_id = pkl.id')
						->where('anggota_pkl.nim', $this->mhs->nim)
						->get()
						->row();

					$tahap = $result->tahap;

					if ($tahap > 0) {
						$letter['judul'] = 'Surat Pengantar PKL';
						$letter['deskripsi'] = 'Surat pengantar yang dapat digunakan untuk mengajukan PKL ke Industri';
						if ($result->tahap == 0) {
							$letter['status'] = '<center><span class="badge badge-danger">Belum Tersedia</span></center>';
							$letter['aksi'] = '<button type="button" class="btn btn-sm btn-outline-primary btn-block mr-2" disabled"><i class="fa fa-eye"></i> Lihat</button>';
						} else {
							$letter['status'] = '<center><span class="badge badge-success">Sudah Tersedia</span></center>';
							$letter['aksi'] = '<button type="button" class="btn btn-sm btn-outline-primary btn-block mr-2" onclick="suratPengantarPKL()"><i class="fa fa-eye"></i> Lihat</button>';
						}
						$letters[] = $letter;

						$letter = [];
						$letter['judul'] = 'Surat Bukti Diterima Industri';
						$letter['deskripsi'] = 'Surat bukti bahwa anda diterima PKL oleh Industri';
						if ($result->tahap == 1) {
							if ($result->status == 'failed') {
								$letter['status'] = '<center><span class="badge badge-danger">Ditolak</span></center>';
							} else {
								$letter['status'] = '<center><span class="badge badge-danger">Belum Diunggah</span></center>';
							}
							$letter['aksi'] = '<button type="button" class="btn btn-sm btn-outline-warning btn-block mr-2" onclick="uploadSuratBuktiDiterima()"><i class="fa fa-upload"></i> Unggah</button>';
						} else {
							$letter['status'] = '<center><span class="badge badge-primary">Sudah Diunggah</span></center>';
							$letter['aksi'] = '<button type="button" class="btn btn-sm btn-outline-warning btn-block mr-2" disabled><i class="fa fa-upload"></i> Unggah</button>';
						}
						$letters[] = $letter;
					}

					if ($result->tahap >= 2) {
						$letter = [];
						$letter['judul'] = 'Surat Pengantar Pembimbing';
						$letter['deskripsi'] = 'Surat Pengantar Pembimbing Kampus & Industri';
						$letter['status'] = '<center><span class="badge badge-success">Sudah Tersedia</span></center>';
						$letter['aksi'] = '<button type="button" class="btn btn-sm btn-outline-primary btn-block mr-2" onclick="surat_pengantar_pembimbing()"><i class="fa fa-eye"></i> Lihat</button>';
						$letters[] = $letter;

						if ($result->tahap > 2) {
							$letter = [];
							$letter['judul'] = 'Lempiran Aktivitas Bimbingan PKL';
							$letter['deskripsi'] = 'Lembar kontrol aktivitas bimbingan Praktek Kerja Lapangan';
							$letter['status'] = '<center><span class="badge badge-success">Sudah Tersedia</span></center>';
							$letter['aksi'] = '<button type="button" class="btn btn-sm btn-outline-primary btn-block mr-2" onclick="lempiranBimbinganPKL()"><i class="fa fa-eye"></i> Lihat</button>';
							$letters[] = $letter;
						}

						if ($result->tahap == 1) {
							$letter = [];
							$letter['judul'] = 'Lempiran Aktivitas Mahasiswa PKL';
							$letter['deskripsi'] = 'Lembar kontrol aktivitas mahasiswa Praktek Kerja Lapangan';
							if ($result->tahap == 0) {
								$letter['status'] = '<center><span class="badge badge-danger">Belum Tersedia</span></center>';
								$letter['aksi'] = '<button type="button" class="btn btn-sm btn-outline-primary btn-block mr-2" disabled"><i class="fa fa-eye"></i> Lihat</button>';
							} else {
								$letter['status'] = '<center><span class="badge badge-success">Sudah Tersedia</span></center>';
								$letter['aksi'] = '<button type="button" class="btn btn-sm btn-outline-primary btn-block mr-2" onclick="lempiranAktivitasPKL()"><i class="fa fa-eye"></i> Lihat</button>';
							}
							$letters[] = $letter;

							$letter['judul'] = 'Penilaian Industri';
							$letter['deskripsi'] = 'Lembar hasil penilaian mahasiswa PKL oleh Industri';
							if ($result->tahap == 0) {
								$letter['status'] = '<center><span class="badge badge-danger">Belum Tersedia</span></center>';
								$letter['aksi'] = '<button type="button" class="btn btn-sm btn-outline-primary btn-block mr-2" disabled"><i class="fa fa-eye"></i> Lihat</button>';
							} else {
								$letter['status'] = '<center><span class="badge badge-success">Sudah Tersedia</span></center>';
								$letter['aksi'] = '<button type="button" class="btn btn-sm btn-outline-primary btn-block mr-2" onclick="lembarPenilaianIndustri()"><i class="fa fa-eye"></i> Lihat</button>';
							}
							$letters[] = $letter;

							$letter['judul'] = 'Penilaian Kampus';
							$letter['deskripsi'] = 'Lembar hasil penilaian mahasiswa PKL oleh Kampus';
							if ($result->tahap == 0) {
								$letter['status'] = '<center><span class="badge badge-danger">Belum Tersedia</span></center>';
								$letter['aksi'] = '<button type="button" class="btn btn-sm btn-outline-primary btn-block mr-2" disabled"><i class="fa fa-eye"></i> Lihat</button>';
							} else {
								$letter['status'] = '<center><span class="badge badge-success">Sudah Tersedia</span></center>';
								$letter['aksi'] = '<button type="button" class="btn btn-sm btn-outline-primary btn-block mr-2" onclick="lembarPenilaianKampus()"><i class="fa fa-eye"></i> Lihat</button>';
							}
							$letters[] = $letter;
							// 	$letter['judul'] = 'Penilaian Kampus';
							// 	$letter['deskripsi'] = 'Lembar hasil penilaian mahasiswa PKL oleh Kampus';
							// 	if ($result->tahap == 0) {
							// 		$letter['status'] = '<center><span class="badge badge-danger">Belum Tersedia</span></center>';
							// 		$letter['aksi'] = '<button type="button" class="btn btn-sm btn-outline-primary btn-block mr-2" disabled"><i class="fa fa-eye"></i> Lihat</button>';
							// 	} else {
							// 		$letter['status'] = '<center><span class="badge badge-success">Sudah Tersedia</span></center>';
							// 		$letter['aksi'] = '<button type="button" class="btn btn-sm btn-outline-primary btn-block mr-2" onclick="lembarPenilaianKampus()"><i class="fa fa-eye"></i> Lihat</button>';
							// 	}
							// 	$letters[] = $letter;
						}

						$view = $this->load->view(
							'dashboard/pages/mahasiswa/proses_pkl',
							[
								'user' => $this->user,
								'letters' => $letters,
							],
							true
						);

						die(json_encode([
							'success' => true,
							'view' => $view,
							'view_name' => 'Proses PKL',
						]));
					}
				}
			}

			// ============================= Pembimbing. Industri
			if ($this->user->role == 'pembimbing_industri') {
				if ($id == 'mahasiswa_pkl') {
					$view = $this->load->view(
						'dashboard/pages/pembimbing_industri/mahasiswa_pkl',
						[
							'user' => $this->user,
						],
						true
					);
					die(json_encode([
						'success' => true,
						'view' => $view,
						'view_name' => 'Mahasiswa PKL',
					]));
				}

				if ($id == 'aktivitas_pkl') {
					$view = $this->load->view(
						'dashboard/pages/mahasiswa/aktivitas_pkl',
						[
							'user' => $this->user,
						],
						true
					);
					die(json_encode([
						'success' => true,
						'view' => $view,
						'view_name' => 'Aktivitas PKL',
					]));
				}

				if ($id == 'daftar_nilai') {
					$view = $this->load->view(
						'dashboard/pages/pembimbing_industri/nilai_mahasiswa',
						[
							'user' => $this->user,
						],
						true
					);
					die(json_encode([
						'success' => true,
						'view' => $view,
						'view_name' => 'Nilai PKL Mahasiswa',
					]));
				}

				if ($id == 'aktivitas_mahasiswa') {
					$view = $this->load->view(
						'dashboard/pages/pembimbing_industri/aktivitas_mahasiswa',
						[
							'user' => $this->user,
						],
						true
					);
					die(json_encode([
						'success' => true,
						'view' => $view,
						'view_name' => 'Aktivitas Mahasiswa',
					]));
				}
			}

			// ============================= Pembimbing Kampus
			if ($this->user->role == 'pembimbing_kampus') {
				if ($id == 'mahasiswa_bimbingan') {
					$view = $this->load->view(
						'dashboard/pages/pembimbing_kampus/mahasiswa_bimbingan',
						[
							'user' => $this->user,
						],
						true
					);
					die(json_encode([
						'success' => true,
						'view' => $view,
						'view_name' => 'Mahasiswa Bimbingan',
					]));
				}

				if ($id == 'bimbingan_pkl') {
					$view = $this->load->view(
						'dashboard/pages/pembimbing_kampus/bimbingan_pkl',
						[
							'user' => $this->user,
						],
						true
					);
					die(json_encode([
						'success' => true,
						'view' => $view,
						'view_name' => 'Bimbingan PKL',
					]));
				}

				if ($id == 'nilai_bimbingan') {
					$view = $this->load->view(
						'dashboard/pages/pembimbing_kampus/nilai_mahasiswa',
						[
							'user' => $this->user,
						],
						true
					);
					die(json_encode([
						'success' => true,
						'view' => $view,
						'view_name' => 'Nilai PKL Mahasiswa',
					]));
				}
			}

			if ($id == 'pengaturan') {
				$view = $this->load->view(
					'dashboard/pages/pengaturan',
					[
						'user' => $this->user,
					],
					true
				);
				die(json_encode([
					'success' => true,
					'view' => $view,
					'view_name' => 'Pengaturan',
				]));
			} else {
				die(json_encode([
					'success' => false,
					'message' => 'Page not found.',
				]));
			}
		} else {
			return die('Method not allowed!');
		}
	}

	public function change_password()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$old = $this->input->post('old');
			$new = $this->input->post('new');
			$confirm = $this->input->post('confirm');

			if ($new !== $confirm) {
				die(json_encode([
					'success' => false,
					'message' => 'Konfirmasi password anda tidak sesuai!',
				]));
			}

			if (md5($old) !== $this->user->password) {
				die(json_encode([
					'success' => false,
					'message' => 'Password lama anda salah!',
				]));
			}

			$this->m_user->update($this->user->id, [
				'password' => md5($new),
			]);

			die(json_encode([
				'success' => true,
				'message' => 'Password berhasil diubah!',
			]));
		}
		die(json_encode([
			'success' => false,
			'message' => 'Unauthorized',
		]));
	}

	public function upload_picture()
	{
		if ($this->input->server('REQUEST_METHOD') == 'POST') {
			$config['upload_path'] = FCPATH . 'assets\\img\\profile';
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size'] = 2048;
			$config['encrypt_name'] = true;
			$config['file_ext_tolower'] = true;
			$this->upload->initialize($config);

			if ($this->user->picture != null && $this->user->picture != 'https://picsum.photos/200') {
				$old_picture = explode('/', $this->user->picture);
				$old_picture = end($old_picture);
				unlink(FCPATH . 'assets\\img\\profile\\' . $old_picture);
			}

			if (!$this->upload->do_upload('file')) {
				die(json_encode([
					'success' => false,
					'message' => strip_tags($this->upload->display_errors()),
				]));
			}

			$upload_data = $this->upload->data();
			$this->m_user->update($this->user->id, [
				'picture' => 'assets/img/profile/' . $upload_data['file_name'],
			]);

			die(json_encode([
				'success' => true,
				'message' => 'Foto berhasil diubah!',
				'url' => 'assets/img/profile/' . $upload_data['file_name'],
			]));
		}
		die(json_encode([
			'success' => false,
			'message' => 'Unauthorized',
		]));
	}
}
