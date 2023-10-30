<!DOCTYPE html>
<html lang="en">

<head>
	<title>SIMAPEKA</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/x-icon" href=<?php echo base_url() . "favicon.ico"; ?>>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
	<nav class="navbar navbar-expand-sm bg-light navbar-light fixed-top">
		<div class="container">
			<a class="navbar-brand" href="#">
				<img src="assets/img/poltek.png" alt="Avatar Logo" style="width:40px;" class="rounded-pill">
			</a>
			<a class="navbar-brand" href="#">Sistem Manajemen PKL</a>
			<div class="collapse navbar-collapse" id="collapsibleNavbar">
				<ul class="navbar-nav">
					<li class="nav-item">
						<a class="nav-link" href="/">Home</a>
					</li>
				</ul>
			</div>

			<div class="nav navbar-nav navbar-right">
				<div class="row">
					<div class="col-sm">
						<a class="btn btn-sm btn-primary text-white" href="/login">Login</a>
					</div>
				</div>
			</div>
		</div>
	</nav>

	<div class="container">
		<img src="assets/img/poltekjumbo.jpg" class="img-fluid mt-5 pt-4" alt="">
	</div>
	<div class="container mt-4 p-3">
		<h1 class="pb-2 ">Politeknik Negeri Bali</h1>
		<hr>
		<div class="row">
			<div class="col">
				<p style="text-align: justify;">Pendidikan Politeknik didirikan pada tahun 1976 yang merupakan kerjasama antara ITB dengan pemerintah Swiss.
					Karena dinilai berhasil selanjutnya dikembangkan 6 (enam) rintisan politeknik di Indonesia dan dianggap berhasil.
					Pendidikan Politeknik membekali lulusannya dengan keterampilan yang didukung dengan pengetahuan dasar teoritis
					yang cukup dan sikap disiplin yang tangguh.</p>
				<p style="text-align: justify;">Politeknik Negeri Bali (PNB) secara resmi dilembagakan pada tanggal 28 April 1997 berdasarkan Keputusan Menteri Pendidikan dan Kebudayaan Republik Indonesia.
					Politeknik Negeri Bali mempunyai visi yaitu Menjadi lembaga pendidikan tinggi vokasi terdepan penghasil lulusan profesional berdaya saing internasional pada tahun 2025.
					Untuk mewujudkan visi tersebut, kampus yang menyelenggarakan pendidikan di bidang vokasi ini lebih mengedepankan praktik daripada teori.
					Pembelajaran di PNB menerapkan pola praktik sesuai dengan tuntutan industri (60%-70%) dan teori (30%-40%), agar lulusan mampu mengisi kebutuhan industri baik dalam negeri maupun luar negeri. </p>
			</div>
			<div class="col">
				<div class="card">
					<div class="list-group">
						<a href="#" class="list-group-item list-group-item-action flex-column align-items-start active">
							<div class="d-flex w-100 justify-content-between">
								<h5 class="mb-1">Praktek Kerja Lapangan di PT. Aman Damai</h5>
								<small>14j</small>
							</div>
							<p class="mb-1">Telah terlaksana kegiatan Praktek Kerja Lapangan di PT. Aman Damai,
								yang dilakukan oleh mahasiswa jurusan Pariwisata, Politeknik Negeri Bali</p>
							<small class="text-light">Baca Selengkapnya > </small>
						</a>
						<a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
							<div class="d-flex w-100 justify-content-between">
								<h5 class="mb-1">Praktek Kerja Lapangan di PT. Makmur Jaya</h5>
								<small class="text-muted">3h</small>
							</div>
							<p class="mb-1">Telah terlaksana kegiatan Praktek Kerja Lapangan di PT. Aman Damai,
								yang dilakukan oleh mahasiswa jurusan Teknik Elektro, Politeknik Negeri Bali.</p>
							<small class="text-muted">Baca Selengkapnya > </small>
						</a>
						<a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
							<div class="d-flex w-100 justify-content-between">
								<h5 class="mb-1">Praktek Kerja Lapangan di PT. Sejahtera</h5>
								<small class="text-muted">4h</small>
							</div>
							<p class="mb-1">Telah terlaksana kegiatan Praktek Kerja Lapangan di PT. Aman Damai,
								yang dilakukan oleh mahasiswa jurusan Akutansi, Politeknik Negeri Bali.</p>
							<small class="text-muted">Baca Selengkapnya > </small>
						</a>
					</div>

				</div>
			</div>
		</div>
	</div>

	<div class="container mt-4 bg-primary text-white p-3 text-center">
		<h2>Apa Itu Sistem PKL?</h2>
		<hr>
		<p class="p-3" style="text-align: justify;">Kegiatan praktik kerja lapangan merupakan kegiatan wajib yag harus dilakukan
			oleh semua mahasiswa sebagai bentuk syarat untuk mengambil mata kuliah Tugas
			Akhir. Tujuan PKL ini adalah untuk meningkatkan wawasan pengetahuan,
			pengalaman, serta kemampuan mahasiswa tentang pengalaman kerja sebelum
			memasuki dunia kerja yang sesungguhnya. Berdasarkan hasil diskusi dengan
			klien/pengguna dari Program Studi Sarjana Terapan Teknologi Rekayasa Perangkat
			Lunak Jurusan Teknik Elektro, pelayanan PKL masih dilakukan secara konvensioal,
			sehingga prosesnya memerlukan waktu dan tahapan yang cukup lama sehingga
			pelayanan terhadap kegiatan ini tidak optimal.</p>

		<p class="ps-3 pe-3" style="text-align: justify;">Dari permasalahan tersebut, maka perlu
			dibuatkan sistem informasi berbasis web untuk mengoptimalkan pelayanan PKL.
			Sistem yang dirancang berjudul “Perancangan Sistem Informasi Pelayanan Praktik
			Kerja Lapangan (PKL) Berbasis Web”. Sistem ini memiliki fitur seperti proses
			pendaftaran PKL, surat menyurat, data tempat industri, data pembimbing, proses
			bimbingan, penilaian dari pembimbing baik dari kampus maupun industri, serta
			mengupload file berkas selama proses kegiatan PKL (Praktik Kerja Lapangan).</p>
	</div>

	<div class="mt-5 p-3" style="background-color: rgba(51,51,153,.1);">
		<h2 class="mb-4 pt-3 text-center">Kerjasama Industri</h2>
		<div class="container pt-2 pb-4">
			<div class="row">
				<div class="col">
					<div class="position-static border">
						<img src="assets/img/pnl1.jpg" alt="" class="w-100 rounded-top">
					</div>
					<div class="bg-light p-3 w-100 rounded-bottom border shadow-sm">
						PT Perusahaan Listrik Negara.
					</div>
				</div>
				<div class="col">
					<div class="position-static border">
						<img src="assets/img/Mekanik.jpg" alt="" class="w-100 h-100 rounded-top">
					</div>
					<div class="bg-light p-3 w-100 rounded-bottom border shadow-sm">
						Dealer Honda PT. Tri Mitrabali.
					</div>
				</div>
				<div class="col">
					<div class="position-static border">
						<img src="assets/img/bri1.jpg" alt="" class="w-100 h-100 rounded-top">
					</div>
					<div class="bg-light p-3 w-100 rounded-bottom border shadow-sm">
						PT Bank Rakyat Indonesia Tbk.
					</div>
				</div>
			</div>
			<div class="row mt-4">
				<div class="col">
					<div class="position-static border">
						<img src="assets/img/sipil.jpg" alt="" class="w-100 rounded-top">
					</div>
					<div class="bg-light p-3 w-100 rounded-bottom border shadow-sm">
						PT VIRAMA KARYA (Persero).
					</div>
				</div>
				<div class="col">
					<div class="position-static border">
						<img src="assets/img/front-office.jpg" alt="" class="w-100 h-100 rounded-top">
					</div>
					<div class="bg-light p-3 w-100 rounded-bottom border shadow-sm">
						Bali Chaya Hotel.
					</div>
				</div>
				<div class="col">
					<div class="position-static border">
						<img src="assets/img/gesang.jpeg" alt="" class="w-100 h-100 rounded-top">
					</div>
					<div class="bg-light p-3 w-100 rounded-bottom border shadow-sm">
						PT Tirta Gesang Tunggal.
					</div>
				</div>
			</div>
		</div>
	</div>


	<section class="">
		<footer class="text-center text-white mt-2" style="background-color: #0a4275;">
			<div class="text-center p-3" style="background-color: rgba(0, 0, 0, 0.2);">
				©<?php echo date("Y", time()) ?> Copyright:
				<a class="text-white" href="https://pnb.ac.id/">Politeknik Negeri Bali</a>
			</div>
		</footer>
	</section>
</body>

</html>
