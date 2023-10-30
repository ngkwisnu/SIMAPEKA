<?php
defined('BASEPATH') or exit('No direct script access allowed');

class BaseAPI extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		if (!$this->session->userdata('logged_in')) {
			die('Unauthorized.');
		}
	}
}
