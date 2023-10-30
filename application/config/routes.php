<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'DashboardController/index';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['login'] = 'AuthController/login';
$route['register'] = 'AuthController/register';
$route['verify'] = 'AuthController/verify';
$route['forgot-password'] = 'AuthController/forgot';
$route['reset-password'] = 'AuthController/reset';
$route['logout'] = 'AuthController/logout';

$route['page'] = 'DashboardController/page';

$route['setting/change-password'] = 'DashboardController/change_password';
$route['setting/upload-picture'] = 'DashboardController/upload_picture';

$route['api/users'] = 'API/User/index';
$route['api/user/(:num)'] = 'API/User/get/$1';
$route['api/user/(:num)/edit'] = 'API/User/edit/$1';

$route['api/mahasiswa'] = 'API/Mahasiswa/index';
$route['api/mahasiswa/(:num)'] = 'API/Mahasiswa/get/$1';
$route['api/mahasiswa/(:num)/edit'] = 'API/Mahasiswa/edit/$1';

$route['api/industri'] = 'API/Industri/index';
$route['api/industri/(:num)'] = 'API/Industri/get/$1';
$route['api/industri/(:num)/edit'] = 'API/Industri/edit/$1';
$route['api/industri/add'] = 'API/Industri/add';

$route['api/pkl'] = 'API/PKL/index';
$route['api/pkl/(:num)'] = 'API/PKL/get/$1';
$route['api/pkl/(:num)/cek'] = 'API/PKL/cekanggota/$1';
$route['api/pkl/(:num)/terima'] = 'API/PKL/terima/$1';
$route['api/pkl/(:num)/tolak'] = 'API/PKL/tolak/$1';

$route['api/pkl/(:num)/terima-undangan'] = 'API/PKL/terima_undangan/$1';
$route['api/pkl/(:num)/tolak-undangan'] = 'API/PKL/tolak_undangan/$1';

$route['api/pkl/cari-tempat'] = 'API/PKL/cari_tempat';
$route['api/pkl/daftar'] = 'API/PKL/daftar';

$route['api/pembimbing-industri'] = 'API/PembimbingIndustri/index';
$route['api/pembimbing-industri/(:num)'] = 'API/PembimbingIndustri/get/$1';
$route['api/pembimbing-industri/(:num)/edit'] = 'API/PembimbingIndustri/edit/$1';
$route['api/pembimbing-industri/add'] = 'API/PembimbingIndustri/add';

$route['api/aktivitas'] = 'API/Aktivitas/index';
$route['api/aktivitas/(:num)'] = 'API/Aktivitas/get/$1';
$route['api/aktivitas/(:num)/edit'] = 'API/Aktivitas/edit/$1';
$route['api/aktivitas/add'] = 'API/Aktivitas/add';

$route['api/pembimbing-kampus'] = 'API/PembimbingKampus/index';
$route['api/pembimbing-kampus/(:num)'] = 'API/PembimbingKampus/get/$1';
$route['api/pembimbing-kampus/(:num)/edit'] = 'API/PembimbingKampus/edit/$1';
$route['api/pembimbing-kampus/add'] = 'API/PembimbingKampus/add';

$route['api/nilai-bimbingan'] = 'API/NilaiBimbingan/index';
$route['api/nilai-bimbingan/(:num)'] = 'API/NilaiBimbingan/get/$1';
$route['api/nilai-bimbingan/(:num)/edit'] = 'API/NilaiBimbingan/edit/$1';
$route['api/nilai-bimbingan/add'] = 'API/NilaiBimbingan/add';

$route['api/nilai-mahasiswa'] = 'API/NilaiMahasiswa/index';
$route['api/nilai-mahasiswa/(:num)'] = 'API/NilaiMahasiswa/get/$1';
$route['api/nilai-mahasiswa/(:num)/edit'] = 'API/NilaiMahasiswa/edit/$1';
$route['api/nilai-mahasiswa/add'] = 'API/NilaiMahasiswa/add';

$route['api/bimbingan-mhs'] = 'API/BimbinganMahasiswa/index';
$route['api/bimbingan-mhs/(:num)'] = 'API/BimbinganMahasiswa/get/$1';
$route['api/bimbingan-mhs/add'] = 'API/BimbinganMahasiswa/add';
$route['api/bimbingan-mhs/(:num)/edit'] = 'API/BimbinganMahasiswa/edit/$1';

$route['api/bimbingan-pkl'] = 'API/BimbinganPKL/index';
$route['api/bimbingan-pkl/(:num)'] = 'API/BimbinganPKL/get/$1';
$route['api/bimbingan-pkl/add'] = 'API/BimbinganPKL/add';
$route['api/bimbingan-pkl/(:num)/edit'] = 'API/BimbinganPKL/edit/$1';

$route['api/proses-pkl/surat/download/pengantar-pkl'] = 'API/ProsesPKL/surat_pengantar_pkl';
$route['api/proses-pkl/surat/upload/bukti-diterima'] = 'API/ProsesPKL/surat_bukti_diterima';

$route['api/proses-pkl/surat/download/bimbingan-pkl'] = 'API/ProsesPKL/lempiran_bimbingan_pkl';

$route['api/proses-pkl/surat/download/penilaian-industri'] = 'API/ProsesPKL/lembar_penilaian_industri';
$route['api/proses-pkl/surat/download/penilaian-kampus'] = 'API/ProsesPKL/lembar_penilaian_kampus';

$route['api/pilih-pembimbing'] = 'API/PilihPembimbing/index';
$route['api/pilih-pembimbing/(:num)'] = 'API/PilihPembimbing/get/$1';
$route['api/pilih-pembimbing/(:num)/edit'] = 'API/PilihPembimbing/edit/$1';

$route['api/ttd-pembimbing/add'] = 'API/TandaTanganPembimbing/add';

$route['api/mhs_bim'] = 'API/MahasiswaBimbingan/index';
$route['api/mhs_pkl'] = 'API/MahasiswaPKL/index';

$route['api/proses-pkl/surat/download/aktivitas-pkl'] = 'API/ProsesPKL/lempiran_aktivitas_pkl';
$route['api/proses-pkl/surat/download/surat-pengantar-pembimbing'] = 'API/ProsesPKL/surat_pengantar_pembimbing';


$route['api/ttd-pembimbing/add1'] = 'API/TandaTanganPembimbing/add1';
