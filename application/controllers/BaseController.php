<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BaseController extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->session->userdata('logged_in')) {
			if ($this->uri->segment(1) == 'page') {
				die(
					json_encode([
						'success' => false,
						'error' => 'UNATHENTICATED',
					])
				);
			} else {
				die($this->load->view('homepage', '', true));
			}
		}
	}
}
