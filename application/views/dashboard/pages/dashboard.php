<div class="callout callout-info">
	<h5><b>Informasi</b></h5>
	<?php
	if ($this->user->role == 'mahasiswa') {
		$pkl = $this->m_pkl->get_by_nim($this->mhs->nim);

		$msg = '';
		if ($pkl || ($pkl && ($pkl->tahap == NULL || $pkl->tahap == -1))) {
			if ($pkl->tahap == 0) {
				$msg = 'Pendaftaran PKL anda sedang di<i>review</i> oleh admin, silahkan menunggu!';
			} else if ($pkl->tahap == -1) {
				$msg = 'Sedang menunggu konfirmasi anggota yang anda undang...';
			} else {
				$msg = 'Proses PKL sedang berjalan, semangat ya!';
			}
		} else {
			$msg = 'Silahkan <b style="color: red;">SEGERA</b> melakukan pendaftaran PKL!';
		}
	} else {
		$msg = 'Welcome to Sistem Manajemen PKL!';
	}
	?>
	<p><?= $msg ?></p>
</div>

<div class="row">
	<div class="col-4">
		<div class="card card-green card-outline">
			<div class="card-body box-profile">
				<div class="text-center">
					<img class="profile-user-img img-fluid img-circle" src="<?= $this->user->picture ?>" alt="User profile picture" style="min-width: 100px; min-height: 100px;">
				</div>

				<h3 class="profile-username text-center mt-2 mb-2">
					<?php echo ucfirst($nama); ?>
				</h3>

				<?php if ($this->user->role === "mahasiswa") { ?>
					<p class="text-muted text-center text-sm">
						<?php
						$prodi = $this->db->select('nama')
							->from('program_studi')
							->where('id', $this->mhs->prodi_id)
							->get()
							->row();

						echo $prodi->nama;
						?>
					</p>

					<ul class="list-group list-group-unbordered mb-2">
						<li class="list-group-item">
							<b>NIM</b> <a class="float-right">
								<?= $this->mhs->nim ?>
							</a>
						</li>
						<li class="list-group-item">
							<b>Kelas</b> <a class="float-right">
								<?= 'C' ?>
							</a>
						</li>
						<li class="list-group-item">
							<b>Semester</b> <a class="float-right">
								<?= $this->mhs->semester ?>
							</a>
						</li>
					</ul>
				<?php } else { ?>

				<?php } ?>
			</div>
		</div>
	</div>
	<div class="col-8">
		<?php if ($this->user->role == 'mahasiswa') { ?>
		<?php
			$undangan = $this->db
					->select('mahasiswa.nama as nama_pengundang, pkl_id, status')
					->from('undangan')
					->join('mahasiswa', 'mahasiswa.nim = undangan.pengundang')
					->where('undangan.nim', $this->mhs->nim)
					->where('undangan.status', NULL)
					->get()
					->result();

			if ($undangan) {
		?>
			<div class="card card-info">
				<div class="card-header">
					<h3 class="card-title">Undangan PKL</h3>

					<div class="card-tools">
						<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
							<i class="fas fa-minus"></i>
						</button>
					</div>
				</div>
				<div class="card-body">
					<table id="table-undangan" class="table table-sm table-bordered table-striped display compact text-sm" style="width: 100%">
						<thead>
							<tr>
								<th class="text-center align-middle" style="width: 30px;">#</th>
								<th class="align-middle">Pegundang</th>
								<th class="align-middle">Industri</th>
								<th style="width: 100px;" class="text-center align-middle">Action</th>
							</tr>
						</thead>
						<tbody>
						<?php
							$no = 1;
							foreach ($undangan as $u) {
								$pkl = $this->m_pkl->get_by_id($u->pkl_id);

								echo '<tr>';
								echo '<td class="text-center align-middle">' . $no++ . '</td>';
								echo '<td class="align-middle">' . $u->nama_pengundang . '</td>';
								echo '<td class="align-middle">' . $pkl->nama_industri . '</td>';
								echo '<td class="text-center align-middle">';
								echo '<button onclick="terimaUndangan('.$pkl->id.')" class="btn btn-sm btn-success mr-2" title="Terima"><i class="fas fa-check"></i></button>';
								echo '<button onclick="tolakUndangan('.$pkl->id.')" class="btn btn-sm btn-danger" title="Tolak"><i class="fas fa-times"></i></button>';
								echo '</td>';
								echo '</tr>';
							}
						?>
						</tbody>
					</table>
				</div>
			</div>
			<script>
				function terimaUndangan(id) {
					if (confirm("Anda yakin ingin menerima undangan ini?")) {
						$.ajax({
							url: "/api/pkl/" + id + "/terima-undangan",
							type: "GET",
							dataType: "json",
							beforeSend: function() {
								$("#table-undangan").find("button").attr("disabled", true);
							},
							success: function(res) {
								if (res.success == true) {
									toastr.success(res.message);
									setTimeout(function() {
										window.location.reload();
									}, 500);
								} else {
									toastr.error(res.message);
								}
							},
							error: function(err) {
								toastr.error("Telah terjadi kesalahan, silahkan coba lagi nanti!");
							},
							complete: function() {
								$("#table-undangan").find("button").attr("disabled", false);
							},
						});
					}
				};

				function tolakUndangan(id) {
					if (confirm("Anda yakin ingin menolak undangan ini?")) {
						$.ajax({
							url: "/api/pkl/" + id + "/tolak-undangan",
							type: "GET",
							dataType: "json",
							beforeSend: function() {
								$("#table-undangan").find("button").attr("disabled", true);
							},
							success: function(res) {
								if (res.success == true) {
									toastr.success(res.message);
									setTimeout(function() {
										window.location.reload();
									}, 500);
								} else {
									toastr.error(res.message);
								}
							},
							error: function(err) {
								toastr.error("Telah terjadi kesalahan, silahkan coba lagi nanti!");
							},
							complete: function() {
								$("#table-undangan").find("button").attr("disabled", false);
							},
						});
					}
				}
			</script>
			<?php }?>
		<?php } ?>
	</div>
</div>