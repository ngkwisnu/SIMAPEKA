<?php
defined('BASEPATH') or exit('No direct script access allowed');

class AuthController extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	public function sendEmail($email, $subject, $message)
	{
		$headers = 'From: noreply@pkl.pnb.ac.id' . 
			"\r\n" . 'CC: aimaradhitya@gmail.com' . 
			"\r\n" . 'MIME-Version: 1.0' . 
			"\r\n" . 'Content-type: text/html; charset=UTF-8';

		return mail($email, $subject, $message, $headers);
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

	public function login()
	{
		if ($this->input->server('REQUEST_METHOD') == 'GET') {
			$this->session->set_flashdata('page', 'login');
			return $this->load->view('authentication');
		} else {
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			$recaptcha = $this->input->post('recaptcha');
			if (!$username || !$password || !$recaptcha) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Bad request.',
					])
				);
			}

			$result = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe' . '&response=' . $recaptcha . '&remoteip=' . $_SERVER['REMOTE_ADDR']));
			if (!$result->success) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Proses ReCaptcha gagal! Silahkan coba lagi.',
					])
				);
			}

			$user = null;
			if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
				$user = $this->m_user->login_by_email($username, $password);
			} else {
				if (is_numeric($username)) {
					$user = $this->m_user->login_by_nim($username, $password);
				} else {
					$user = $this->m_user->login_by_email($username, $password);
				}
			}

			if ($user) {
				if (!$user->verified) {
					die(
						json_encode([
							'success' => false,
							'message' => 'Akun anda belum diverifikasi',
						])
					);
				} else {
					if ($user->status != 'active') {
						die(
							json_encode([
								'success' => false,
								'message' => 'Akun anda dinonaktifkan!',
							])
						);
					} else {
						$this->session->set_userdata([
							'logged_in' => true,
							'user_id' => $user->id,
						]);
						die(
							json_encode([
								'success' => true,
							])
						);
					}
				}
			} else {
				die(
					json_encode([
						'success' => false,
						'message' => 'Username atau Password salah!',
					])
				);
			}
		}
	}

	public function register()
	{
		if ($this->input->server('REQUEST_METHOD') == 'GET') {
			$this->session->set_flashdata('page', 'register');
			return $this->load->view('authentication');
		} else {
			$nim = $this->input->post('nim');
			$email = $this->input->post('email');
			$recaptcha = $this->input->post('recaptcha');
			if (!$nim || !$email || !$recaptcha) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Bad request.',
					])
				);
			}

			if ($this->m_user->get_by_username($email)) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Email sudah digunakan!',
					])
				);
			}

			$result = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe' . '&response=' . $recaptcha . '&remoteip=' . $_SERVER['REMOTE_ADDR']));
			if (!$result->success) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Proses ReCaptcha gagal! Silahkan coba lagi.',
					])
				);
			}

			if (!$this->m_mhs->get_by_nim($nim)) {
				die(
					json_encode([
						'success' => false,
						'message' => 'Anda belum terdaftar sebagai Mahasiswa di Politeknik Negeri Bali!',
					])
				);
			}

			if ($this->m_user->get_by_nim($nim)) {
				die(
					json_encode([
						'success' => false,
						'message' => 'NIM anda sudah memiliki akun!',
					])
				);
			}

			$password = $this->randomString(8);
			$subject = 'Password Akun';
			$message =
				"Berikut adalah <i>password</i> akun anda:<br><br>
				Password: <b>$password</b><br><br>
				Silahkan login melalui website: " . base_url('/login');

			if ($this->sendEmail($email, $subject, $message)) {
				$user_id = $this->m_user->register($nim, $email);
				if ($user_id) {
					$this->m_user->update($user_id, [
						'password' => md5($password),
					]);

					die(
						json_encode([
							'success' => true,
							'message' => 'Akun anda berhasil didaftarkan! Silahkan cek email anda untuk mendapatkan password akun anda!',
						])
					);
				} else {
					die(
						json_encode([
							'success' => false,
							'message' => 'Registrasi gagal, Silahkan coba lagi! (2)',
						])
					);
				}
			} else {
				die(
					json_encode([
						'success' => false,
						'message' => 'Registrasi gagal, Silahkan coba lagi! (3)',
					])
				);
			}
		}
	}

	public function verify()
	{
		if ($this->input->server('REQUEST_METHOD') == 'GET') {
			$this->session->set_flashdata('page', 'verify');
			return $this->load->view('authentication');
		} else {
		}
	}

	public function forgot()
	{
		if ($this->input->server('REQUEST_METHOD') == 'GET') {
			$this->session->set_flashdata('page', 'forgot');
			return $this->load->view('authentication');
		}
	}

	public function reset()
	{
		if ($this->input->server('REQUEST_METHOD') == 'GET') {
			$this->session->set_flashdata('page', 'reset');
			return $this->load->view('authentication');
		}
	}

	public function logout()
	{
		if ($this->input->server('REQUEST_METHOD') == 'GET') {
			$this->session->set_userdata('logged_in', false);
			$this->session->unset_userdata('user_id');
			return redirect('login');
		}
	}
}
