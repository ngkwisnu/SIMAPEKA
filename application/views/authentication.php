<?php
defined('BASEPATH') or exit('No direct script access allowed');
$TITLE = 'Login';

if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {   
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo $TITLE; ?></title>
  <link rel="icon" type="image/x-icon" href=<?php echo base_url() . "favicon.ico"; ?>>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
  <link rel="stylesheet" href="<?php echo base_url(); ?>assets/plugins/fontawesome-free/css/all.min.css">
</head>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<body>
  <div class="d-flex align-items-center justify-content-center vh-100 m-4">
    <div id="main-form" class="main-form">
      <?php
      } 

      $page = $this->session->flashdata('page');
      if (!$page) {
        require('authentication/login.php');
      } else {
        require("authentication/$page.php");
      }

      if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
      ?>
    </div>
  </div>
</body>

<style>
  @media (min-width:320px) {
    .main-form {
      width: 300px;
    }
  }

  @media (min-width:481px) {
    .main-form {
      width: 510px;
    }
  }
</style>
<?php
$error = $this->session->flashdata('error');
if ($error) {
  echo "
			<script>
			Swal.fire({
				icon: 'error',
				title: 'Gagal ☹️',
				text: '$error'
			});
			</script>";
} else {
  $success = $this->session->flashdata('success');
  if ($success) {
    echo "
				<script>
				Swal.fire({
					icon: 'success',
					title: 'Berhasil 😁',
					text: '$success'
				});
				</script>";
  }
}
?>
</html>
<?php } ?>