<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>SIMAPEKA</title>
	<link rel="icon" type="image/x-icon" href=<?php echo base_url() . "favicon.ico"; ?>>
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fontawesome-free/css/all.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/alt/ionicons.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/dist/css/adminlte.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/toastr/toastr.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2/css/select2.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
</head>

<body class="hold-transition sidebar-mini ">
	<div class="wrapper">
		<nav class="main-header navbar navbar-expand navbar-light">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
				</li>
				<li class="nav-item d-none d-sm-inline-block">
					<a href="/" class="nav-link">DASHBOARD</a>
				</li>
			</ul>

			<ul class="navbar-nav ml-auto">
				<li class="nav-item dropdown">
					<a class="nav-link" data-toggle="dropdown" href="#">
						<i class="far fa-bell"></i>
						<span id="count" class="badge badge-warning navbar-badge"></span>
					</a>
					<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
						<span class="dropdown-item dropdown-header"><b>Notifikasi</b></span>
						<div id="notifications"></div>
					</div>
				</li>
			</ul>
		</nav>

		<div id="modals">

		</div>

		<aside class="main-sidebar sidebar-light-primary elevation-4">
			<a href="#" class="brand-link text-center">
				<span class="brand-text font-weight-light">Sistem Manajemen PKL</span>
			</a>

			<div class="sidebar">
				<div class="user-panel mt-3 pb-3 mb-3 d-flex">
					<div class="image">
						<img src="<?= $this->user->picture ?>" class="img-circle elevation-2" alt="User Picture" style="width: 35px; height: 35px; min-width: 35px; min-height: 35px;">
					</div>
					<div class="info">
						<a href="#" class="d-block">
							<?php echo ucfirst($nama); ?>
						</a>
					</div>
				</div>

				<nav class="mt-2">
					<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
						<li class="nav-header">MENU UTAMA</li>
						<li class="nav-item">
							<a href="#dashboard" class="nav-link" onclick="loadPage(this)">
								<i class="fas fa-tachometer-alt"></i>
								<p>
									Dashboard
								</p>
							</a>
						</li>
						<?php require('dashboard/sidebar.php'); ?>
						<li class="nav-header">LAINNYA</li>
						<li class="nav-item">
							<a href="#pengaturan" class="nav-link" onclick="loadPage(this)">
								<i class="nav-icon fas fa-cogs"></i>
								<p>
									Pengaturan
								</p>
							</a>
						</li>
						<li class="nav-item">
							<a href="/logout" rel="no-refrerer" referrerPolicy="no-referrer" class="nav-link">
								<i class="nav-icon fas fa-sign-out-alt"></i>
								<p>
									Keluar
								</p>
							</a>
						</li>
					</ul>
				</nav>
			</div>
		</aside>

		<div class="content-wrapper">
			<div class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 id="title" class="m-0 text-capitalize">Loading...</h1>
						</div>
					</div>
				</div>
			</div>

			<div id="content" class="pl-4 pr-4">
			</div>
		</div>

		<footer class="main-footer">
			<strong>Copyright &copy; 2023 <a href="https://pnb.ac.id">Politeknik Negeri Bali</a>.</strong>
			All rights reserved.
		</footer>
	</div>


	<script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/dist/js/adminlte.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/jszip/jszip.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/pdfmake/pdfmake.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/pdfmake/vfs_fonts.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/toastr/toastr.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/bootstrap4-duallistbox/jquery.bootstrap-duallistbox.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/moment/moment.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/inputmask/jquery.inputmask.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/daterangepicker/daterangepicker.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/select2/js/select2.full.min.js"></script>
	<script src="<?php echo base_url(); ?>assets/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

<script>
	function loadPage(btn) {
		$('.nav-link').removeClass('active');

		var id = '';
		if (btn != null) {
			if ($(btn).attr('href') == '#') {
				id = 'dashboard';
			} else {
				id = $(btn).attr('href').substr(1);
			}

			$(btn).addClass('active');
		} else {
			id = 'dashboard';
			$('a[href="#dashboard"]').addClass('active');
		}

		$('#title').text('Loading...');
		window.history.pushState('', '', '/#' + id);

		$.ajax({
			url: '/page?id=' + id,
			type: 'GET',
			dataType: 'json',
			beforeSend: function () {
				$('.nav-link').addClass('disabled text-muted');
				$('#content').animate({
					opacity: 0,
					'margin-top': '-15px'
				}, 150);
			},
			success: function (res) {
				if (res.success) {
					$('#title').text(res.view_name);
					$('#content').html(res.view);
					$('#content').animate({
						opacity: 1,
						'margin-top': '0px'
					}, 150);
					lastBtn = btn;
				} else {
					if (res.message != undefined) {
						Swal.fire({
							icon: 'error',
							title: 'Error',
							text: res.message
						}).then(() => {
							$('#title').text('Error');
							$('#content').html(res.message);
						});
					} else {
						if (res.error != undefined) {
							if (res.error == 'UNATHENTICATED') {
								window.location.href = '/login';
							}
						}
					}
				}
			},
			complete: function () {
				$('.nav-link').removeClass('disabled text-muted');
			},
			error: function (xhr, status, error) {
				$('#title').text('Error');
				$('#content').html('Something wrong happened.');
				$('#content').animate({
					opacity: 1,
					'margin-top': '0px'
				}, 150);
			},
		});
	}

	$(function () {
		var hash = window.location.hash;
		if (hash) {
			var btn = $('a[href="' + hash + '"]')[0];
			loadPage(btn);
		} else {
			loadPage(null);
		}

		const state = localStorage.getItem('darkMode', false);
		if (state == "true") {
			$("body").addClass("dark-mode");

			$("aside.main-sidebar").removeClass("sidebar-light-primary");
			$("aside.main-sidebar").addClass("sidebar-dark-primary");

			$("nav.navbar").removeClass("navbar-light");
			$("nav.navbar").addClass("navbar-dark");
		} else {
			$("body").removeClass("dark-mode");

			$("aside.main-sidebar").removeClass("sidebar-dark-primary");
			$("aside.main-sidebar").addClass("sidebar-light-primary");

			$("nav.navbar").removeClass("navbar-dark");
			$("nav.navbar").addClass("navbar-light");
		}
	});

	function formatDate(date) {
		var d = new Date(date);
		var options = {
		day: 'numeric',
		month: 'long',
		year: 'numeric'
		};
		return d.toLocaleDateString('id-ID', options);
	}
</script>
<?php
$error = $this->session->flashdata('error');
if ($error) {
  echo "
<script>
toastr.error('$error');
</script>";
} else {
  $success = $this->session->flashdata('success');
  if ($success) {
    echo "
<script>
toastr.success('$success');
</script>";
  }
}
?>

</html>
