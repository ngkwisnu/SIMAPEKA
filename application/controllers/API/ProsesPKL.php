<?php
defined('BASEPATH') or exit('No direct script access allowed');

include 'BaseAPI.php';

class ProsesPKL extends BaseAPI
{
	public function __construct()
	{
		parent::__construct();

		$this->user = $this->m_user->get_by_id($this->session->userdata('user_id'));
		if ($this->user->role == 'mahasiswa') {
			$this->mhs = $this->m_mhs->get_by_uid($this->user->id);
		}
	}

	public function translateMonth($month)
	{
		$months = [
			'January' => 'Januari',
			'February' => 'Februari',
			'March' => 'Maret',
			'April' => 'April',
			'May' => 'Mei',
			'June' => 'Juni',
			'July' => 'Juli',
			'August' => 'Agustus',
			'September' => 'September',
			'October' => 'Oktober',
			'November' => 'November',
			'December' => 'Desember',
		];
		return $months[$month];
	}

	public function surat_pengantar_pkl()
	{
		$pkl = NULL;
		if ($this->user->role == 'admin') {
			$id = $this->input->get('id');
			$pkl = $this->db
				->select('pkl.id as id, industri.nama as nama_tempat, industri.alamat, industri.telepon, pkl.updated_at')
				->from('pkl')
				->join('industri', 'industri.id = pkl.industri_id')
				->where('pkl.id', $id)
				->get()
				->row();
		} else {
			$pkl = $this->db
				->select('pkl.id as id, industri.nama as nama_tempat, industri.alamat, industri.telepon, pkl.updated_at')
				->from('pkl')
				->join('industri', 'industri.id = pkl.industri_id')
				->join('anggota_pkl', 'anggota_pkl.pkl_id = pkl.id')
				->where('anggota_pkl.nim', $this->mhs->nim)
				->get()
				->row();
		}

		$anggotas = $this->db
			->select('mahasiswa.nama, mahasiswa.nim, mahasiswa.nomor_hp')
			->from('anggota_pkl')
			->join('mahasiswa', 'mahasiswa.nim = anggota_pkl.nim')
			->where('anggota_pkl.pkl_id', $pkl->id)
			->get()
			->result();

		$prodi = $this->db
			->select('program_studi.nama as nama_prodi, program_studi.jenjang as jenjang, jurusan.nama as nama_jurusan')
			->from('program_studi')
			->join('mahasiswa', 'mahasiswa.prodi_id = program_studi.id')
			->join('jurusan', 'jurusan.id = program_studi.jurusan_id')
			->where('mahasiswa.nim', $anggotas[0]->nim)
			->get()
			->row();

		$tanggal = explode(' ', $pkl->updated_at);
		$tanggal = explode('-', $tanggal[0]);
		$tanggal = $tanggal[2] . ' ' . $this->translateMonth(date('F', mktime(0, 0, 0, $tanggal[1], 10))) . ' ' . $tanggal[0];

		require_once APPPATH . 'third_party/TCPDF/tcpdf.php';
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'letter', true, 'UTF-8', false);

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Politeknik Negeri Bali ');
		$pdf->SetTitle('Surat Pengantar PKL');
		$pdf->SetSubject('');
		$pdf->SetKeywords('');

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(10, 10, 10);
		$pdf->setCellPaddings(0, 0, 0, 0);
		$pdf->SetAutoPageBreak(true, 10);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setLanguageArray(['en']);

		$pdf->SetFont('times', '', 11);

		$pdf->AddPage();
		$html = '<table width="100%">
<tr>
    <td rowspan="5" style="width:15%" align="right"><img src="/assets/logo.png"></td>
    <td style="width:80%"><h3 align="center" style="font-weight: none;">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</h3></td>
</tr>
<tr>
    <td><h1 align="center">POLITEKNIK NEGERI BALI</h1></td>
</tr>
<tr>
    <td><h5 align="center" style="font-weight: none;">Kampus Politeknik Negeri Bali Bukit Jimbaran Kuta Selatan Badung-Bali</h5></td>
</tr>
<tr>
    <td><h5 align="center" style="font-weight: none;">Telepon : 0361-701981, Fax : 0361-701128</h5></td>
</tr>
<tr>
    <td><h5 align="center" style="font-weight: none;">Website : www.pnb.ac.id, Email : poltek@pnb.ac.id</h5></td>
</tr>
</table><br><hr>
<table>
<tr>
    <td>&nbsp;</td>
</tr>
<tr>
    <td style="width: 50%;">Nomor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: 06.' . sprintf('%04d', $pkl->id) . '/PL8/TE/DT/' . date('Y') . '</td>
    <td><span style="width: 50%; text-align: right;">Bukit Jimbaran, ' . $tanggal . '</span></td>
</tr>
<tr>
    <td style="width: 70px;">Lampiran</td>
    <td>: -</td>
</tr>
<tr>
    <td style="width: 70px;">Perihal</td>
    <td>: Permohonan Persetujuan Praktek Kerja Lapangan</td>
</tr>
</table>
<br> <br>
Kepada YTH:<br>
<b>Bpk. CEO</b><br>
' . $pkl->nama_tempat . '<br><br>
Di:<br>
<b><u>' . $pkl->alamat . '</u></b><br><br>
Dengan Hormat,<br><br>
<span style="text-align: justify;">Dalam upaya untuk meningkatkan mutu Pendidikan, maka mahasiswa Program Studi <i>' . $prodi->jenjang . '</i> <b>' . $prodi->nama_prodi . '</b> Jurusan <b>' . $prodi->nama_jurusan . '</b> Politeknik Negeri Bali diwajibkan mengikuti Praktek Kerja Lapangan (PKL) pada semester ' . ($prodi->jenjang == 'Sarjana Terapan' ? 7 : 5) . ', Dengan adanya Praktek Kerja Lapangan  (PKL), diharapkan mahasiswa mengetahui secara langsung tentang kondisi kerja dan mempunyai pengetahuan dunia kerja sesungguhnya.</span><br><br>
<span style="text-align: justify;">Sehubung dengan hal diatas, kami mengharapkan agar mahasiswa tersebut dapat diterima Praktek Kerja Lapangan (PKL) selamat 6 (enam) bulan, terhitung mulai dari tanggal <b>23 Maret 2023</b>.</span><br><br>
Adapun mahasiswa yang akan melakukan Praktek Kerja Lapangan (PKL) sebagai berikut:<br><br>
<table border="1" cellpadding="5">
<tr>
    <th style="width: 35px; text-align:center"><b>NO</b></th>
    <th style="width: 250px; text-align: center"><b>N A M A</b></th>
    <th style="text-align: center"><b>N I M</b></th>
    <th style="text-align: center"><b>NO. HP</b></th>
</tr>';
		$no = 1;
		foreach ($anggotas as $anggota) {
			$html .= '
<tr>
    <td style="text-align: center">' . $no . '</td>
    <td style="text-align: center">' . $anggota->nama . '</td>
    <td style="text-align: center">' . $anggota->nim . '</td>
    <td style="text-align: center">' . $anggota->nomor_hp . '</td>
</tr>';
			$no++;
		}
		$html .= '
</table><br><br>
<span style="text-align: justify;">Demikian permohonan ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.<br><br><br><br></span>

<table style="text-align: center;">
<tr>
    <td style="width: 375px;">&nbsp;</td>
    <td>Jurusan Teknik Elektro</td>
</tr>
<tr>
    <td style="width: 375px;">&nbsp;</td>
    <td>Ketua, </td>
</tr>
<tr>
    <td style="width: 375px;">&nbsp;</td>
    <td><br></td>
</tr>
<tr>
    <td style="width: 375px;">&nbsp;</td>
    <td style="height: 50px;"></td>
</tr>
<tr>
    <td style="width: 375px;">&nbsp;</td>
    <td><br></td>
</tr>
<tr>
    <td style="width: 375px;">&nbsp;</td>
    <td><b><u>Ir. I Wayan Raka Ardana, M.T.</u></b></td>
</tr>
<tr>
    <td style="width: 375px;">&nbsp;</td>
    <td>NIP. 196705021993031005</td>
</tr>
</table>
';
		$pdf->writeHTML($html, true, 0, true, 0);
		$pdf->lastPage();

		$download = isset($_GET['download']);
		$pdf->Output('Surat Pengantar PKL.pdf', $download ? 'D' : 'I');
	}

	function randomString($length = 10)
	{
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[random_int(0, $charactersLength - 1)];
		}
		return $randomString;
	}

	public function surat_pengantar_pembimbing()
	{
		$akun_pembimbing_pus = $this->db
			->select('pkl.id, pembimbing_kampus.nama as nama, user.username, user.password, pkl.created_at as tanggal, industri.nama as nama_industri, industri.alamat as alamat')
			->from('anggota_pkl')
			->join('pembimbing_kampus', 'pembimbing_kampus.nip = anggota_pkl.nip')
			->join('pkl', 'pkl.id = anggota_pkl.pkl_id')
			->join('user_pembimbing_kampus', 'user_pembimbing_kampus.nip = pembimbing_kampus.nip')
			->join('user', 'user.id = user_pembimbing_kampus.user_id')
			->join('industri', 'industri.id = pkl.industri_id')
			->where('anggota_pkl.nim', $this->mhs->nim)
			->get()
			->row();

		$akun_pembimbing_kampus = $this->db
			->select('pkl.id, pembimbing_kampus.nama as nama, user.username, user.password, pkl.created_at as tanggal, user.id as user_id')
			->from('anggota_pkl')
			->join('pembimbing_kampus', 'pembimbing_kampus.nip = anggota_pkl.nip')
			->join('pkl', 'pkl.id = anggota_pkl.pkl_id')
			->join('user_pembimbing_kampus', 'user_pembimbing_kampus.nip = pembimbing_kampus.nip')
			->join('user', 'user.id = user_pembimbing_kampus.user_id')
			->where('anggota_pkl.nim', $this->mhs->nim)
			->get()
			->row();

		$akun_pembimbing_industri = $this->db
			->select('pkl.id, pembimbing_industri.nama as nama, user.username, user.password, pkl.created_at as tanggal, user.id as user_id')
			->from('anggota_pkl')
			->join('pkl', 'pkl.id = anggota_pkl.pkl_id')
			->join('industri', 'industri.id = pkl.industri_id')
			->join('pembimbing_industri', 'pembimbing_industri.industri_id = industri.id')
			->join('user_pembimbing_industri', 'user_pembimbing_industri.nik = pembimbing_industri.nik')
			->join('user', 'user.id = user_pembimbing_industri.user_id')
			->where('anggota_pkl.nim', $this->mhs->nim)
			->get()
			->row();

		$prodi = $this->db
			->select('program_studi.nama as nama_prodi, program_studi.jenjang as jenjang, jurusan.nama as nama_jurusan')
			->from('program_studi')
			->join('mahasiswa', 'mahasiswa.prodi_id = program_studi.id')
			->join('jurusan', 'jurusan.id = program_studi.jurusan_id')
			->where('mahasiswa.nim', $this->mhs->nim)
			->get()
			->row();

		$pwdKampus = $this->randomString(6);
		$hashPwdKampus = md5($pwdKampus);

		$pwdIndus = $this->randomString(6);
		$hashPwdIndustri = md5($pwdIndus);

		$this->m_user->update($akun_pembimbing_kampus->user_id, [
			'password' => $hashPwdKampus,
		]);

		$this->m_user->update($akun_pembimbing_industri->user_id, [
			'password' => $hashPwdIndustri,
		]);

		$tanggal = explode(' ', $akun_pembimbing_pus->tanggal);
		$tanggal = explode('-', $tanggal[0]);
		$tanggal = $tanggal[2] . ' ' . $this->translateMonth(date('F', mktime(0, 0, 0, $tanggal[1], 10))) . ' ' . $tanggal[0];

		require_once APPPATH . 'third_party/TCPDF/tcpdf.php';
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'letter', true, 'UTF-8', false);

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Politeknik Negeri Bali ');
		$pdf->SetTitle('Surat Pengantar PKL');
		$pdf->SetSubject('');
		$pdf->SetKeywords('');

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(10, 10, 10);
		$pdf->setCellPaddings(0, 0, 0, 0);
		$pdf->SetAutoPageBreak(true, 10);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setLanguageArray(['en']);

		$pdf->SetFont('times', '', 11);

		$pdf->AddPage();
		$html = '<table width="100%">
<tr>
    <td rowspan="5" style="width:15%" align="right"><img src="/assets/logo.png"></td>
    <td style="width:80%"><h3 align="center" style="font-weight: none;">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</h3></td>
</tr>
<tr>
    <td><h1 align="center">POLITEKNIK NEGERI BALI</h1></td>
</tr>
<tr>
    <td><h5 align="center" style="font-weight: none;">Kampus Politeknik Negeri Bali Bukit Jimbaran Kuta Selatan Badung-Bali</h5></td>
</tr>
<tr>
    <td><h5 align="center" style="font-weight: none;">Telepon : 0361-701981, Fax : 0361-701128</h5></td>
</tr>
<tr>
    <td><h5 align="center" style="font-weight: none;">Website : www.pnb.ac.id, Email : poltek@pnb.ac.id</h5></td>
</tr>
</table><br><hr>
<table>
<tr>
    <td>&nbsp;</td>
</tr>
<tr>
    <td style="width: 50%;">Nomor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: 06.' . sprintf('%04d', $akun_pembimbing_pus->id) . '/PL8/TE/DT/' . date('Y') . '</td>
    <td><span style="width: 50%; text-align: right;">Bukit Jimbaran, ' . $tanggal . '</span></td>
</tr>
<tr>
    <td style="width: 70px;">Lampiran</td>
    <td>: -</td>
</tr>
<tr>
    <td style="width: 70px;">Perihal</td>
    <td>: Permohonan Bimbingan Praktek Kerja Lapangan</td>
</tr>
</table>
<br> <br>
Kepada YTH:<br>
<b>Bpk. Dosen Politeknik Negeri Bali & Bpk.CEO</b><br>
' . $akun_pembimbing_pus->nama_industri . '<br><br>
Di:<br>
<b><u>' . $akun_pembimbing_pus->alamat . '</u></b><br><br>
Dengan Hormat,<br><br>
<span style="text-align: justify;">Dalam upaya untuk meningkatkan mutu Pendidikan, maka mahasiswa Program Studi <i>' . $prodi->jenjang . '</i> <b>' . $prodi->nama_prodi . '</b> Jurusan <b>' . $prodi->nama_jurusan . '</b> Politeknik Negeri Bali diwajibkan mengikuti Praktek Kerja Lapangan (PKL) pada semester ' . ($prodi->jenjang == 'Sarjana Terapan' ? 7 : 5) . ', Dengan adanya Praktek Kerja Lapangan  (PKL), diharapkan mahasiswa mengetahui secara langsung tentang kondisi kerja dan mempunyai pengetahuan dunia kerja sesungguhnya.</span><br><br>
<span style="text-align: justify;">Sehubung dengan hal diatas, kami mengharapkan agar Bapak/Ibu bersedia membimbing Mahasiswa dalam melaksanakan Praktek Kerja Lapangan (PKL) selamat 6 (enam) bulan, terhitung mulai dari tanggal <b>23 Maret 2023</b>.</span><br><br>
Adapun user/akun pembimbing yang dapat Bapak/Ibu gunakan dalam membimbing mahasiswa selama proses Praktek Kerja Lapangan (PKL) sebagai berikut:<br><br>
<table border="1" cellpadding="5">
<tr>
    <th style="width: 35px; text-align:center"><b>NO</b></th>
    <th style="width: 250px; text-align: center"><b>N A M A</b></th>
    <th style="text-align: center"><b>Username</b></th>
    <th style="text-align: center"><b>Password</b></th>
</tr>';
		$no = 1;

		$html .= '
<tr>
    <td style="text-align: center">' . $no . '</td>
    <td style="text-align: center">' . $akun_pembimbing_kampus->nama . '</td>
    <td style="text-align: center">' . $akun_pembimbing_kampus->username . '</td>
    <td style="text-align: center">' . $pwdKampus . '</td>
</tr>
<tr>
    <td style="text-align: center">' . $no . '</td>
    <td style="text-align: center">' . $akun_pembimbing_industri->nama . '</td>
    <td style="text-align: center">' . $akun_pembimbing_industri->username . '</td>
    <td style="text-align: center">' . $pwdIndus . '</td>
</tr>';
		$no++;

		$html .= '
</table><br><br>
<span style="text-align: justify;">Demikian permohonan ini kami sampaikan, atas perhatian dan kerjasamanya kami ucapkan terima kasih.<br><br><br><br></span>

<table style="text-align: center;">
<tr>
    <td style="width: 375px;">&nbsp;</td>
    <td>Jurusan Teknik Elektro</td>
</tr>
<tr>
    <td style="width: 375px;">&nbsp;</td>
    <td>Ketua, </td>
</tr>
<tr>
    <td style="width: 375px;">&nbsp;</td>
    <td><br></td>
</tr>
<tr>
    <td style="width: 375px;">&nbsp;</td>
    <td style="height: 50px;"></td>
</tr>
<tr>
    <td style="width: 375px;">&nbsp;</td>
    <td><br></td>
</tr>
<tr>
    <td style="width: 375px;">&nbsp;</td>
    <td><b><u>Ir. I Wayan Raka Ardana, M.T.</u></b></td>
</tr>
<tr>
    <td style="width: 375px;">&nbsp;</td>
    <td>NIP. 196705021993031005</td>
</tr>
</table>
';
		$pdf->writeHTML($html, true, 0, true, 0);
		$pdf->lastPage();

		$download = isset($_GET['download']);
		$pdf->Output('Surat Pengantar PKL.pdf', $download ? 'D' : 'I');
	}

	public function lempiran_bimbingan_pkl()
	{
		$pkl = NULL;
		if ($this->user->role == 'mahasiswa') {
			$pkl = $this->db
				->select('mahasiswa.nama as nama_mahasiswa, anggota_pkl.nim, industri.nama as nama_industri, updated_at, pembimbing_kampus.nama as nama_pembimbing, anggota_pkl.nip')
				->from('pkl')
				->join('industri', 'industri.id = pkl.industri_id')
				->join('anggota_pkl', 'anggota_pkl.pkl_id = pkl.id')
				->join('mahasiswa', 'mahasiswa.nim = anggota_pkl.nim')
				->join('pembimbing_kampus', 'pembimbing_kampus.nip = anggota_pkl.nip')
				->where('anggota_pkl.nim', $this->mhs->nim)
				->get()
				->row();
		}

		$bimbingan = $this->db
			->select('tanggal, deskripsi')
			->from('bimbingan_pkl')
			->join('mahasiswa', 'mahasiswa.nim = bimbingan_pkl.nim')
			->where('bimbingan_pkl.nim', $this->mhs->nim)
			->get()
			->result();

		$tanggal = explode(' ', $pkl->updated_at);
		$tanggal = explode('-', $tanggal[0]);
		$tanggal = $tanggal[2] . ' ' . $this->translateMonth(date('F', mktime(0, 0, 0, $tanggal[1], 10))) . ' ' . $tanggal[0];

		require_once APPPATH . 'third_party/TCPDF/tcpdf.php';
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'letter', true, 'UTF-8', false);

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Politeknik Negeri Bali ');
		$pdf->SetTitle('Lempiran Bimbingan PKL');
		$pdf->SetSubject('');
		$pdf->SetKeywords('');

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(10, 10, 10);
		$pdf->setCellPaddings(0, 0, 0, 0);
		$pdf->SetAutoPageBreak(true, 10);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setLanguageArray(['en']);

		$pdf->SetFont('times', '', 11);

		$pdf->AddPage();
		$html = '<table width="100%" style="line-height: 1.5; font-size: 16px;">
<tr>
    <td style="width:100%"><h3 align="center" style="font-weight: none;"><b>LAMPIRAN D. KONTROL AKTIVITAS BIMBINGAN PKL</b></h3></td>
</tr>
<tr>
    <td style="width:100%"><h3 align="center" style="font-weight: none;">LEMBAR KONTROL AKTIVITAS BIMBINGAN PRAKTIK KERJA LAPANGAN</h3></td>
</tr>
<tr>
    <td style="width:100%"><h3 align="center" style="font-weight: none;">TAHUN AJARAN : ______________</h3></td>
</tr>
<br>
<tr>
    <td style="width:170px"><h4 align="left" style="font-weight: bold; font-size: 17px;">Nama Mahasiswa</h4></td>
	<td style="font-weight: none; font-size: 17px;"><b>:</b> &nbsp;&nbsp;' . $pkl->nama_mahasiswa . '</td>
</tr>
<tr>
    <td style="width:170px"><h4 align="left" style="font-weight: bold; font-size: 17px;">NIM</h4></td>
	<td style="font-weight: none; font-size: 17px;"><b>:</b> &nbsp;&nbsp;' . $pkl->nim . '</td>
</tr>
<tr>
    <td style="width:170px"><h4 align="left" style="font-weight: bold; font-size: 17px;">Tempat PKL</h4></td>
	<td style="font-weight: none; font-size: 17px;"><b>:</b> &nbsp;&nbsp;' . $pkl->nama_industri . '</td>
</tr>
<tr>
    <td style="width:170px"><h4 align="left" style="font-weight: bold; font-size: 17px;">Waktu PKL</h4></td>
	<td style="font-weight: none; font-size: 17px;"><b>:</b> &nbsp;&nbsp;' . $tanggal . '</td>
</tr>
<tr>
    <td style="width:170px"><h4 align="left" style="font-weight: bold; font-size: 17px;">Dosen Pembimbing</h4></td>
	<td style="font-weight: none; font-size: 17px;"><b>:</b> &nbsp;&nbsp;' . $pkl->nama_pembimbing . '</td>
</tr>
<tr>
    <td style="width:170px"><h4 align="left" style="font-weight: bold; font-size: 17px;">NIP Pembimbing</h4></td>
	<td style="font-weight: none; font-size: 17px;"><b>:</b> &nbsp;&nbsp;' . $pkl->nip . '</td>
</tr>
<br><br>
<table border="1" cellpadding="5">
<tr>
    <th style="width: 35px; text-align: center;"><b>No</b></th>
    <th style="text-align: center;"><b>Tanggal</b></th>
    <th style="width: 300px;"><b>Deskripsi Bimbingan</b></th>
    <th style="text-align: center"><b>Paraf</b></th>
</tr>';
		$no = 1;
		foreach ($bimbingan as $bim) {
			$html .= '
<tr>
    <td style="text-align: center;">' . $no . '</td>
    <td style="text-align: center;">' . $bim->tanggal . '</td>
    <td>' . $bim->deskripsi . '</td>
    <td style="text-align: center;"> <img src="' . base_url('uploads/ttd/' . $pkl->nip . '.png') . '" width="100" height="50" alt="Gambar"></td>
    <td style="text-align: center;"></td>
</tr>';
			$no++;
		}
		$html .= '
</table><br><br>
<tr>
	<td style="align: left; width: 520px;">Mengetahui,</td>
	<td>Badung, ' . $tanggal . '</td>
</tr>
<br><br>
<tr>
	<td style="align: left; width: 517px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ketua Program Studi TRPL</td>
	<td>Dosen Pembimbing</td>
</tr>
<br><br><br>
<tr>
	<td style="align: left; width: 505px;">I Nyoman Eddy Indrayana, S.Kom., M.T.</td>
	<td>' . $pkl->nama_pembimbing . '</td>
</tr>
<tr>
	<td style="align: left; width: 533px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;NIP.197602202006041000</td>
	<td>NIP.' . $pkl->nip . '</td>
</tr>
';
		$pdf->writeHTML($html, true, 0, true, 0);
		$pdf->lastPage();

		$download = isset($_GET['download']);
		$pdf->Output('Lempiran Bimbingan PKL.pdf', $download ? 'D' : 'I');
	}

	public function lembar_penilaian_industri()
	{
		$pkl = NULL;
		if ($this->user->role == 'mahasiswa') {
			$pkl = $this->db
				->select('mahasiswa.nama as nama_mahasiswa, mahasiswa.nim, jurusan.nama as nama_jurusan, mahasiswa.semester, industri.nama as nama_industri, updated_at , pembimbing_industri.nama as nama_pembimbing, pembimbing_industri.nik as nik')
				->from('anggota_pkl')
				->join('mahasiswa', 'mahasiswa.nim = anggota_pkl.nim')
				->join('pkl', 'pkl.id = anggota_pkl.pkl_id')
				->join('industri', 'industri.id = pkl.industri_id')
				->join('program_studi', 'program_studi.id = mahasiswa.prodi_id')
				->join('jurusan', 'jurusan.id = program_studi.jurusan_id')
				->join('pembimbing_industri', 'pembimbing_industri.industri_id = industri.id')
				->where('anggota_pkl.nim', $this->mhs->nim)
				->get()
				->row();
		}

		$nilai = $this->db
			->select('kemampuan_kerja, disiplin, komunikasi, inisiatif, kreativitas, kerjasama')
			->from('nilai_pkl')
			->join('nilai_mahasiswa', 'nilai_mahasiswa.id = nilai_pkl.id_nilai')
			->where('nilai_mahasiswa.nim', $this->mhs->nim)
			->get()
			->row();

		$tanggal = explode(' ', $pkl->updated_at);
		$tanggal = explode('-', $tanggal[0]);
		$tanggal = $tanggal[2] . ' ' . $this->translateMonth(date('F', mktime(0, 0, 0, $tanggal[1], 10))) . ' ' . $tanggal[0];

		require_once APPPATH . 'third_party/TCPDF/tcpdf.php';
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'letter', true, 'UTF-8', false);

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Politeknik Negeri Bali ');
		$pdf->SetTitle('Lembar Penilaian Industri');
		$pdf->SetSubject('');
		$pdf->SetKeywords('');

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(10, 10, 10);
		$pdf->setCellPaddings(0, 0, 0, 0);
		$pdf->SetAutoPageBreak(true, 10);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setLanguageArray(['en']);

		$pdf->SetFont('times', '', 11);

		$pdf->AddPage();
		$html = '<table width="100%">
<tr>
    <td rowspan="5" style="width:15%" align="right"><img src="/assets/logo.png"></td>
    <td style="width:80%"><h4 align="center" style="font-weight: none;">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</h4></td>
</tr>
<tr>
    <td><h1 align="center">POLITEKNIK NEGERI BALI</h1></td>
</tr>
<tr>
    <td><h5 align="center" style="font-weight: none;">Kampus Politeknik Negeri Bali Bukit Jimbaran Kuta Selatan Badung-Bali</h5></td>
</tr>
<tr>
    <td><h5 align="center" style="font-weight: none;">Telepon : 0361-701981, Fax : 0361-701128</h5></td>
</tr>
<tr>
    <td><h5 align="center" style="font-weight: none;">Website : www.pnb.ac.id, Email : poltek@pnb.ac.id</h5></td>
</tr>
</table><hr>
<table>
<tr>
    <td>&nbsp;</td>
</tr>
<tr>
    <td style="width:100%"><h1 align="center" style="font-weight: none;">LEMBAR PENILAIAN INDUSTRI</h1></td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>
<br>
<tr>
    <td style="width:170px"><h3 align="left" style="font-weight: none; font-size: 17px; line-height: 1.5;">Nama Mahasiswa</h3></td>
    <td style="font-weight: none; font-size: 17px;">: &nbsp;&nbsp;' . $pkl->nama_mahasiswa . '</td>
</tr>
<tr>
    <td style="width:170px"><h3 align="left" style="font-weight: none; font-size: 17px; line-height: 1.5;">NIM</h3></td>
    <td style="font-weight: none; font-size: 17px;">: &nbsp;&nbsp;' . $pkl->nim . '</td>
</tr>
<tr>
    <td style="width:170px"><h3 align="left" style="font-weight: none; font-size: 17px; line-height: 1.5;">Jurusan</h3></td>
    <td style="font-weight: none; font-size: 17px;">: &nbsp;&nbsp;' . $pkl->nama_jurusan . '</td>
</tr>
<tr>
    <td style="width:170px"><h3 align="left" style="font-weight: none; font-size: 17px; line-height: 1.5;">Semester</h3></td>
    <td style="font-weight: none; font-size: 17px;">: &nbsp;&nbsp;' . $pkl->semester . '</td>
</tr>
<tr>
    <td style="width:170px"><h3 align="left" style="font-weight: none; font-size: 17px; line-height: 1.5;">Tempat PKL</h3></td>
    <td style="font-weight: none; font-size: 17px;">: &nbsp;&nbsp;' . $pkl->nama_industri . '</td>
</tr>
<tr>
    <td style="width:170px"><h3 align="left" style="font-weight: none; font-size: 17px; line-height: 1.5;">Waktu PKL</h3></td>
    <td style="font-weight: none; font-size: 17px;">: &nbsp;&nbsp;' . $tanggal . '</td>
</tr>
</table>
<tr>
	<td>&nbsp;</td>
</tr>';
$html .= '
<table border="1" cellpadding="5">
<tr>
    <th style="width: 35px; text-align: center;"><b>NO</b></th>
    <th style="width: 220px; text-align: center;"><b>KOMPONEN PENILAIAN</b></th>
    <th style="text-align: center"><b>BOBOT (%)</b></th>
    <th style="text-align: center"><b>NILAI</b></th>
    <th style="text-align: center"><b>BOBOT x NILAI</b></th>
</tr>';
		$no = 1;
		
		$bobot_kemampuan_kerja = 30;
		$bobot_disiplin = 20;
		$bobot_komunikasi = 15;
		$bobot_inisiatif = 15;
		$bobot_kreativitas = 10;
		$bobot_kerjasama = 10;

    	$html .= '
	<tr>
		<td style="text-align: center">' . $no++ . '</td>
		<td style="text-align: center">Kemampuan Kerja</td>
		<td style="text-align: center">' . $bobot_kemampuan_kerja . '</td>
		<td style="text-align: center">' . $nilai->kemampuan_kerja . '</td>
		<td style="text-align: center">' . ($bobot_kemampuan_kerja / 100) * $nilai->kemampuan_kerja . '</td>
	</tr>
	<tr>
		<td style="text-align: center">' . $no++ . '</td>
		<td style="text-align: center">Disiplin</td>
		<td style="text-align: center">' . $bobot_disiplin . '</td>
		<td style="text-align: center">' . $nilai->disiplin . '</td>
		<td style="text-align: center">' . ($bobot_disiplin / 100) * $nilai->disiplin . '</td>
	</tr>
	<tr>
		<td style="text-align: center">' . $no++ . '</td>
		<td style="text-align: center">Komunikasi</td>
		<td style="text-align: center">' . $bobot_komunikasi . '</td>
		<td style="text-align: center">' . $nilai->komunikasi . '</td>
		<td style="text-align: center">' . ($bobot_komunikasi / 100) * $nilai->komunikasi . '</td>
	</tr>
	<tr>
		<td style="text-align: center">' . $no++ . '</td>
		<td style="text-align: center">Inisiatif</td>
		<td style="text-align: center">' . $bobot_inisiatif . '</td>
		<td style="text-align: center">' . $nilai->inisiatif . '</td>
		<td style="text-align: center">' . ($bobot_inisiatif / 100) * $nilai->inisiatif . '</td>
	</tr>
	<tr>
		<td style="text-align: center">' . $no++ . '</td>
		<td style="text-align: center">Kreativitas</td>
		<td style="text-align: center">' . $bobot_kreativitas . '</td>
		<td style="text-align: center">' . $nilai->kreativitas . '</td>
		<td style="text-align: center">' . ($bobot_kreativitas / 100) * $nilai->kreativitas . '</td>
	</tr>
	<tr>
		<td style="text-align: center">' . $no++ . '</td>
		<td style="text-align: center">Kerjasama</td>
		<td style="text-align: center">' . $bobot_kerjasama . '</td>
		<td style="text-align: center">' . $nilai->kerjasama . '</td>
		<td style="text-align: center">' . ($bobot_kerjasama / 100) * $nilai->kerjasama . '</td>
	</tr>
		';
		
		$total_nilai = ($bobot_kemampuan_kerja / 100) * $nilai->kemampuan_kerja + ($bobot_disiplin / 100) * $nilai->disiplin + ($bobot_komunikasi / 100) * $nilai->komunikasi + ($bobot_inisiatif/ 100) * $nilai->inisiatif + ($bobot_kreativitas/ 100) * $nilai->kreativitas + ($bobot_kerjasama/ 100) * $nilai->kerjasama;
		$html .= '
	<tr>
		<td colspan="4" style="text-align: right;"><b>TOTAL NILAI</b></td>
		<td style="text-align: center">'.$total_nilai.'</td>
	</tr>
</table>
<br><br>
<tr>
	<td style="width:150px;"><h3 align="left" style="font-weight: bold; font-size: 15px;"><u>Keterangan</u>:</h3></td>
	<td style="font-weight: none; font-size: 15px;">&nbsp;&nbsp;</td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>
<tr>
    <td style="width:115px"><h3 align="left" style="font-weight: font-size: 15px;">Istimewa</h3></td>
    <td style="font-weight: none; font-size: 15px;">(A) : 8,1 - 1,0</td>
</tr>
<tr>
    <td style="width:115px"><h3 align="left" style="font-weight: font-size: 15px;">Baik Sekali</h3></td>
    <td style="font-weight: none; font-size: 15px;">(AB) : 7,6 - 8,0</td>
</tr>
<tr>
    <td style="width:115px"><h3 align="left" style="font-weight: font-size: 15px;">Baik</h3></td>
    <td style="font-weight: none; font-size: 15px;">(B) : 6,6 - 7,5</td>
</tr>
<tr>
    <td style="width:115px"><h3 align="left" style="font-weight: font-size: 15px;">Cukup Baik</h3></td>
    <td style="font-weight: none; font-size: 15px;">(BC) : 6,1 - 6,5</td>
</tr>
<tr>
    <td style="width:115px"><h3 align="left" style="font-weight: font-size: 15px;">Cukup</h3></td>
    <td style="font-weight: none; font-size: 15px;">(C) : 5,6 - 6,0</td>
</tr>
<tr>
    <td style="width:115px"><h3 align="left" style="font-weight: font-size: 15px;">Kurang</h3></td>
    <td style="font-weight: none; font-size: 15px;">(D) : 4,1 - 5,5</td>
</tr>
<tr>
    <td style="width:115px"><h3 align="left" style="font-weight: font-size: 15px;">Kurang Sekali</h3></td>
    <td style="font-weight: none; font-size: 15px;">(E) : &lt;4,1</td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>
<table style="text-align: center;">
<tr>
    <td style="align: right; width: 363px;">&nbsp;</td>
    <td>Bukit jimbaran, '. $tanggal .'</td>
</tr>
<tr>
    <td style="width: 353px;">&nbsp;</td>
    <td>Dosen Pembimbing </td>
</tr>
<tr>
    <td style="width: 375px;">&nbsp;</td>
    <td><br></td>
</tr>
<tr>
    <td style="width: 375px;">&nbsp;</td>
    <td style="height: 50px;"></td>
</tr>
<tr>
    <td style="width: 375px;">&nbsp;</td>
    <td><br></td>
</tr>
<tr>
    <td style="width: 375px;">&nbsp;</td>
	<td>Nama ' . $pkl->nama_pembimbing .'</td>
</tr>
<tr>
    <td style="width: 375px;">&nbsp;</td>
    <td>NIK ' . $pkl->nik .'</td>
</tr>
</table>
';

		$pdf->writeHTML($html, true, 0, true, 0);
		$pdf->lastPage();

		$download = isset($_GET['download']);
		$pdf->Output('Lembar Penilaian Industri.pdf', $download ? 'D' : 'I');
	}

	public function lembar_penilaian_kampus()
	{
		$pkl = NULL;
		if ($this->user->role == 'mahasiswa') {
			$pkl = $this->db
				->select('mahasiswa.nama as nama_mahasiswa, mahasiswa.nim, industri.nama as nama_industri, industri.alamat as alamat_industri, pembimbing_kampus.nama as nama_pembimbing, pembimbing_kampus.nip as nip, updated_at ')
				->from('anggota_pkl')
				->join('mahasiswa', 'mahasiswa.nim = anggota_pkl.nim')
				->join('pkl', 'pkl.id = anggota_pkl.pkl_id')
				->join('industri', 'industri.id = pkl.industri_id')
				->join('pembimbing_kampus', 'pembimbing_kampus.nip = anggota_pkl.nip')
				->where('anggota_pkl.nim', $this->mhs->nim)
				->get()
				->row();
		}

		$nilai = $this->db
			->select('motivasi, kreativitas, disiplin, metode')
			->from('nilai_kampus')
			->join('nilai_mahasiswa', 'nilai_mahasiswa.id = nilai_kampus.id_nilai')
			->where('nilai_mahasiswa.nim', $this->mhs->nim)
			->get()
			->row();

		$tanggal = explode(' ', $pkl->updated_at);
		$tanggal = explode('-', $tanggal[0]);
		$tanggal = $tanggal[2] . ' ' . $this->translateMonth(date('F', mktime(0, 0, 0, $tanggal[1], 10))) . ' ' . $tanggal[0];	

		require_once APPPATH . 'third_party/TCPDF/tcpdf.php';
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'letter', true, 'UTF-8', false);

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Politeknik Negeri Bali ');
		$pdf->SetTitle('Lembar Penilaian Kampus');
		$pdf->SetSubject('');
		$pdf->SetKeywords('');

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(10, 10, 10);
		$pdf->setCellPaddings(0, 0, 0, 0);
		$pdf->SetAutoPageBreak(true, 10);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setLanguageArray(['en']);

		$pdf->SetFont('times', '', 11);

		$pdf->AddPage();
		$html = '<table width="100%">
<tr>
	<td rowspan="5" style="width:15%" align="right"><img src="/assets/logo.png"></td>
	<td style="width:80%"><h4 align="center" style="font-weight: none;">KEMENTERIAN PENDIDIKAN, KEBUDAYAAN, RISET, DAN TEKNOLOGI</h4></td>
</tr>
<tr>
	<td><h1 align="center">POLITEKNIK NEGERI BALI</h1></td>
</tr>
<tr>
	<td><h5 align="center" style="font-weight: none;">Kampus Politeknik Negeri Bali Bukit Jimbaran Kuta Selatan Badung-Bali</h5></td>
</tr>
<tr>
	<td><h5 align="center" style="font-weight: none;">Telepon : 0361-701981, Fax : 0361-701128</h5></td>
</tr>
<tr>
	<td><h5 align="center" style="font-weight: none;">Website : www.pnb.ac.id, Email : poltek@pnb.ac.id</h5></td>
</tr>
</table><hr>
<tr>
	<td>&nbsp;</td>
</tr>
<tr>
	<td style="width:100%"><h2 align="center" style="font-weight: none; line-height: 1.5;">LEMBAR NILAI BIMBINGAN</h2></td>
</tr>
<br></br>
<tr>
	<td style="width:100%"><h2 align="center" style="font-weight: none; line-height: 1.5;">PRAKTEK KERJA LAPANGAN</h2></td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>
<br>
<tr>
    <td style="width:170px"><h3 align="left" style="font-weight: none; font-size: 17px; line-height: 1.5;">Nama Mahasiswa</h3></td>
    <td style="font-weight: none; font-size: 17px;">: &nbsp;&nbsp;' . $pkl->nama_mahasiswa . '</td>
</tr>
<tr>
	<td style="width:170px"><h3 align="left" style="font-weight: none; font-size: 17px; line-height: 1.5;">NIM</h3></td>
	<td style="font-weight: none; font-size: 17px;">: &nbsp;&nbsp;' . $pkl->nim . '</td>
</tr>
<tr>
	<td style="width:170px"><h3 align="left" style="font-weight: none; font-size: 17px; line-height: 1.5;">Nama Perusahaan</h3></td>
	<td style="font-weight: none; font-size: 17px;">: &nbsp;&nbsp;' . $pkl->nama_industri . '</td>
</tr>
<tr>
	<td style="width:170px"><h3 align="left" style="font-weight: none; font-size: 17px; line-height: 1.5;">Alamat Perusahaan</h3></td>
	<td style="font-weight: none; font-size: 17px;">: &nbsp;&nbsp;' . $pkl->alamat_industri . '</td>
</tr>
<tr>
	<td>&nbsp;</td>
</tr>';
$html .=' 
<table border="1" cellpadding="5">
<tr>
    <th style="width: 35px; text-align: center;"><b>NO</b></th>
    <th style="width: 220px; text-align: center;"><b>UNSUR PENILAIAN</b></th>
    <th style="text-align: center"><b>BOBOT (%)</b></th>
    <th style="text-align: center"><b>NILAI</b></th>
    <th style="text-align: center"><b>BOBOT x NILAI</b></th>
</tr>';

		$no = 1;

		$bobot_motivasi = 20;
		$bobot_kreativitas = 20;
		$bobot_disiplin = 20;
		$bobot_metode = 40;

    	$html .= '
<tr>
    <td style="text-align: center">' . $no++ . '</td>
    <td style="text-align: center">Motivasi</td>
    <td style="text-align: center">' . $bobot_motivasi . '</td>
    <td style="text-align: center">' . $nilai->motivasi . '</td>
    <td style="text-align: center">' . ($bobot_motivasi / 100) * $nilai->motivasi . '</td>
</tr>
<tr>
    <td style="text-align: center">' . $no++ . '</td>
    <td style="text-align: center">Kreativitas</td>
    <td style="text-align: center">' . $bobot_kreativitas . '</td>
    <td style="text-align: center">' . $nilai->kreativitas . '</td>
    <td style="text-align: center">' . ($bobot_kreativitas / 100) * $nilai->kreativitas . '</td>
</tr>
<tr>
    <td style="text-align: center">' . $no++ . '</td>
    <td style="text-align: center">Disiplin</td>
    <td style="text-align: center">' . $bobot_disiplin . '</td>
    <td style="text-align: center">' . $nilai->disiplin . '</td>
    <td style="text-align: center">' . ($bobot_disiplin / 100) * $nilai->disiplin . '</td>
</tr>
<tr>
    <td style="text-align: center">' . $no++ . '</td>
    <td style="text-align: center">metode</td>
    <td style="text-align: center">' . $bobot_metode . '</td>
    <td style="text-align: center">' . $nilai->metode . '</td>
    <td style="text-align: center">' . ($bobot_metode / 100) * $nilai->metode . '</td>
</tr>
	';
	
	$total_nilai = ($bobot_motivasi / 100) * $nilai->motivasi + ($bobot_kreativitas / 100) * $nilai->kreativitas + ($bobot_disiplin / 100) * $nilai->disiplin + ($bobot_metode / 100) * $nilai->metode;
	$html .= '
<tr>
    <td colspan="4" style="text-align: right;"><b>TOTAL NILAI</b></td>
	<td style="text-align: center">'.$total_nilai.'</td>
</tr>
</table>
		';
		$html .= '
<tr>
    <td>&nbsp;</td>
</tr>
<tr>
	<td style="width:150px;"><h3 align="left" style="font-weight: bold; font-size: 15px;"><u>Keterangan</u>:</h3></td>
	<td style="font-weight: none; font-size: 15px;">&nbsp;&nbsp;</td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>
<tr>
    <td style="width:115px"><h3 align="left" style="font-weight: font-size: 15px;">Istimewa</h3></td>
    <td style="font-weight: none; font-size: 15px;">(A) : 8,1 - 1,0</td>
</tr>
<tr>
    <td style="width:115px"><h3 align="left" style="font-weight: font-size: 15px;">Baik Sekali</h3></td>
    <td style="font-weight: none; font-size: 15px;">(AB) : 7,6 - 8,0</td>
</tr>
<tr>
    <td style="width:115px"><h3 align="left" style="font-weight: font-size: 15px;">Baik</h3></td>
    <td style="font-weight: none; font-size: 15px;">(B) : 6,6 - 7,5</td>
</tr>
<tr>
    <td style="width:115px"><h3 align="left" style="font-weight: font-size: 15px;">Cukup Baik</h3></td>
    <td style="font-weight: none; font-size: 15px;">(BC) : 6,1 - 6,5</td>
</tr>
<tr>
    <td style="width:115px"><h3 align="left" style="font-weight: font-size: 15px;">Cukup</h3></td>
    <td style="font-weight: none; font-size: 15px;">(C) : 5,6 - 6,0</td>
</tr>
<tr>
    <td style="width:115px"><h3 align="left" style="font-weight: font-size: 15px;">Kurang</h3></td>
    <td style="font-weight: none; font-size: 15px;">(D) : 4,1 - 5,5</td>
</tr>
<tr>
    <td style="width:115px"><h3 align="left" style="font-weight: font-size: 15px;">Kurang Sekali</h3></td>
    <td style="font-weight: none; font-size: 15px;">(E) : &lt;4,1</td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>
<br></br>
<table style="text-align: left;">
<tr>
	<td style="align: left; width: 499px;"></td>
	<td>Bukit jimbaran, ' . $tanggal . '</td>
</tr>
<tr>
	<td style="align: left; width: 499px;"></td>
	<td>Politeknik Negeri Bali</td>
</tr>
<tr>
	<td style="align: left; width: 499px;"></td>
	<td>Jurusan Teknik Elektro</td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>
<table style="text-align: left; width: 100%;">
<tr>
	<td style="align: left; width: 500px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Koordinator PKL</td>
	<td>Pembimbing PKL</td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>
<br><br><br>
<tr>
	<td style="align: left; width: 500px;">________________________</td>
	<td>' . $pkl->nama_pembimbing . '</td>
</tr>
<tr>
	<td style="align: left; width: 500px;">NIP._____________________</td>
	<td>NIP. ' . $pkl->nip . '</td>
</tr>
</table>
';

		$pdf->writeHTML($html, true, 0, true, 0);
		$pdf->lastPage();

		$download = isset($_GET['download']);
		$pdf->Output('Lembar Penilaian Kampus.pdf', $download ? 'D' : 'I');
	}


	//Pembuatan lempiran aktivitas mahasiswa
	public function lempiran_aktivitas_pkl()
	{
		$pkl = NULL;
		if ($this->user->role == 'mahasiswa') {
			$pkl = $this->db
				->select('mahasiswa.nama as nama_mahasiswa, aktivitas_pkl.nim as nim, industri.nama as tempat_industri,pembimbing_industri.nama as nama_pem, pembimbing_industri.jabatan as jabatan')
				->from('aktivitas_pkl, pkl')
				->join('industri', 'industri.id = pkl.industri_id')
				->join('anggota_pkl', 'anggota_pkl.nim = aktivitas_pkl.nim')
				->join('mahasiswa', 'mahasiswa.nim = anggota_pkl.nim')
				->join('pembimbing_industri', 'pembimbing_industri.nik = aktivitas_pkl.nik')
				->where('anggota_pkl.nim', $this->mhs->nim)
				->get()
				->row();
		}

		$aktivitas = $this->db
			->select('tanggal, jenis_kegiatan, uraian_kegiatan, jam, pembimbing_industri.nama as nama, ttd_pemdus.nik')
			->from('aktivitas_pkl')
			->join('pembimbing_industri', 'pembimbing_industri.nik = aktivitas_pkl.nik')
			->where('aktivitas_pkl.nim', $this->mhs->nim)
			->where('aktivitas_pkl.validasi', $validasi = 'sudah_validasi')
			->get()
			->result();

		// $tanggal_surat = date("d F Y");
		$nama_bulan = array(
			1 => 'Januari',
			2 => 'Februari',
			3 => 'Maret',
			4 => 'April',
			5 => 'Mei',
			6 => 'Juni',
			7 => 'Juli',
			8 => 'Agustus',
			9 => 'September',
			10 => 'Oktober',
			11 => 'November',
			12 => 'Desember'
		);
		$tanggal_sekarang = date("d");
		$bulan_sekarang = date("n");
		$tahun_sekarang = date("Y");

		$tanggal_indonesia = $tanggal_sekarang . ' ' . $nama_bulan[$bulan_sekarang] . ' ' . $tahun_sekarang;

		require_once APPPATH . 'third_party/TCPDF/tcpdf.php';
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, 'letter', true, 'UTF-8', false);

		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Politeknik Negeri Bali ');
		$pdf->SetTitle('Lempiran Aktivitas PKL');
		$pdf->SetSubject('');
		$pdf->SetKeywords('');

		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetMargins(10, 10, 10);
		$pdf->setCellPaddings(0, 0, 0, 0);
		$pdf->SetAutoPageBreak(true, 10);
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setLanguageArray(['en']);

		$pdf->SetFont('times', '', 11);

		$pdf->AddPage();
		$html = '<table width="100%" style="line-height: 1.5; font-size: 16px;">
<tr>
    <td style="width:100%"><h3 align="center" style="font-weight: none;"><b>LAMPIRAN A. KONTROL AKTIVITAS PKL</b></h3></td>
</tr>
<tr>
    <td style="width:100%"><h3 align="center" style="font-weight: none;">LEMBAR KONTROL AKTIVITAS PKL</h3></td>
</tr>
<br><br>
<table border="1" cellpadding="5">
<tr>
    <th rowspan = "2"; style="width: 30px; text-align: center;"><b>No</b></th>
    <th rowspan = "2"; style="width: 85px;text-align: center;"><b>Tanggal</b></th>
	<th rowspan = "2"; style="width: 120px; text-align: center;"><b>Jenis Kegiatan</b></th>
    <th rowspan = "2"; style="width: 175px; text-align: center;"><b>Uraian Kegiatan</b></th>
	<th rowspan = "2"; style="width: 75px; text-align: center;"><b>Jam</b></th>
    <th colspan = "2"; style="width: 200px; text-align: center"><b>Pembimbing</b></th>
</tr>
<tr>
    <th style="width: 120px; text-align: center;"><b>Nama</b></th>
    <th style="width: 80px; text-align: center"><b>Paraf</b></th>
</tr>';
		$no = 1;
		foreach ($aktivitas as $akv) {
			$html .= '
<tr>
    <td style="text-align: center;">' . $no . '</td>
	<td style="text-align: center;">' . $akv->tanggal . '</td>
	<td>' . $akv->jenis_kegiatan . '</td>
    <td>' . $akv->uraian_kegiatan . '</td>
	<td>' . $akv->jam . '</td>
	<td>' . $akv->nama . '</td>
	<td style="text-align: center;"> <img src="' . base_url('uploads/ttd/' . $akv->nik . '.png') . '" width="100" height="80" alt="Gambar"></td>
</tr>';
		$no++;
		}
		$html .= '
</table><br><br><br><br>
<tr>
	<td style="align: left; width: 600px">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	Badung, ' . $tanggal_indonesia . '</td>
</tr>
<tr>
	<td style="align: left; width: 600px;">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	Pembimbing Industri,</td>
</tr>
<tr>
		<td  style="align: left; width: 600px;">
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		<img src="' . base_url('uploads/ttd/'. $akv->nik .'.png') . '" width="100" height="50" alt="Gambar">
		</td>
</tr>
<tr>
	<td style="align: left; width: 600px;">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	Nama &nbsp;&nbsp;&nbsp;: ' . $pkl->nama_pem . '</td>
</tr>
<tr>
	<td style="align: left; width: 600px;">
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	Jabatan &nbsp;: ' . $pkl->jabatan . '</td>
</tr>
';
		$pdf->writeHTML($html, true, 0, true, 0);
		$pdf->lastPage();

		$download = isset($_GET['download']);
		$pdf->Output('Lempiran Aktivitas PKL.pdf', $download ? 'D' : 'I');
	}
}