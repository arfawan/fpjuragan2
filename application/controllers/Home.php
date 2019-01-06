<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library(array('template', 'cart'));
		$this->load->model('user');
	}

	public function index()
	{
		$this->load->library('pagination');
      //configure
      $config['base_url'] = base_url().'home/index';
      $config['total_rows'] = $this->user->get_all('t_items')->num_rows();
      $config['per_page'] = 6;
      $config['uri_segment'] = 3;

      $this->pagination->initialize($config);

      $data['link']  = $this->pagination->create_links();

		if ($this->session->userdata('user_login') == TRUE)
		{
			$data['fav'] = $this->user->get_where('t_favorite', ['id_user' => $this->session->userdata('user_id')]);
		}

      $data['data'] 		= $this->user->select_where_limit('t_items', ['aktif' => 1], $config['per_page'], $offset);
		$this->template->fpjuragan('home', $data);

	}



	
	public function detail()
	{

		if (is_numeric($this->uri->segment(3)))
		{

			$id = $this->uri->segment(3);

			$items = $this->user->get_where('t_items', array('link' => $id));
			$get = $items->row();

			$table = "t_rkategori rk
							JOIN t_kategori k ON (k.id_kategori = rk.id_kategori)";

			$data['kat'] 	= $this->user->get_where($table, array('rk.id_item' => $get->id_item));
			$data['data'] 	= $items;
			$data['img'] 	= $this->user->get_where('t_img', ['id_item' => $get->id_item]);

			$this->template->fpjuragan('item_detail', $data);

		} else {

			redirect('home');

		}

	}

	

	public function registrasi()
	{

		if($this->input->post('submit', TRUE) == 'Submit')
		{

			$this->load->library('form_validation');

			$this->form_validation->set_rules('nama1', 'Nama Depan', "required|min_length[3]|regex_match[/^[a-zA-Z'.]+$/]");
			$this->form_validation->set_rules('nama2', 'Nama Belakang', "regex_match[/^[a-zA-Z'.]+$/]");
			$this->form_validation->set_rules('user', 'Username', "required|min_length[5]|regex_match[/^[a-zA-Z0-9]+$/]");
			$this->form_validation->set_rules('email', 'Email', "required|valid_email");
			$this->form_validation->set_rules('pass1', 'Password', "required|min_length[5]");
			$this->form_validation->set_rules('pass2', 'Ketik Ulang Password', "required|matches[pass1]");
			$this->form_validation->set_rules('jk', 'Jenis Kelamin', "required");
			$this->form_validation->set_rules('telp', 'Telp', "required|min_length[8]|numeric");
			$this->form_validation->set_rules('alamat', 'Alamat', "required|min_length[10]");

			if ($this->form_validation->run() == TRUE)
			{

				$data = array(
					'username' 	=> $this->input->post('user', TRUE),
					'fullname' 	=> $this->input->post('nama1', TRUE).' '.$this->input->post('nama2', TRUE),
					'email' 		=> $this->input->post('email', TRUE),
					'password' 	=> password_hash($this->input->post('pass1', TRUE), PASSWORD_DEFAULT, ['cost' => 10]),
					'jk' 			=> $this->input->post('jk', TRUE),
					'telp' 		=> $this->input->post('telp', TRUE),
					'alamat' 	=> $this->input->post('alamat', TRUE),
					'status' 	=> 1
				);

				if ($this->user->insert('t_users', $data))
				{

					$halaman = 'reg_success';

				} else {

					echo '<script type="text/javascript">alert("Username / Email tidak tersedia");</script>';

					$halaman = 'register';

				}

			} else {

				$halaman = 'register';

			}

		} else {

			$halaman = 'register';

		}

		if ($this->session->userdata('user_login') == TRUE)
      {
         redirect('home');
      }

		$data = array(
			'user' 	=> $this->input->post('user', TRUE),
			'nama1' 	=> $this->input->post('nama1', TRUE),
			'nama2' 	=> $this->input->post('nama2', TRUE),
			'email' 	=> $this->input->post('email', TRUE),
			'jk' 		=> $this->input->post('jk', TRUE),
			'telp' 	=> $this->input->post('telp', TRUE),
			'alamat' => $this->input->post('alamat', TRUE),
		);

		$this->template->fpjuragan($halaman, $data);

	}

	public function login()
	{

		if ($this->input->post('submit') == 'Submit')
    {

      $user  = $this->input->post('username', TRUE);
      $pass  = $this->input->post('password', TRUE);
			$where = "username = '".$user."' && status = 1 || email = '".$user."' && status = 1";

			$cek 	 = $this->user->get_where('t_users', $where);

      if ($cek->num_rows() > 0)
			{

      	$data = $cek->row();

        if (password_verify($pass, $data->password))
        {
          $datauser = array (
				'user_id' 		=> $data->id_user,
            	'name' 			=> $data->fullname,
            	'user_login' 	=> TRUE
          );

          $this->session->set_userdata($datauser);

          redirect('home');

        } else {

          echo '<script type="text/javascript">alert("Password ditolak");</script>';

        }

      } else {

				echo '<script type="text/javascript">alert("Username tidak dikenali");</script>';

			}

		}

      if ($this->session->userdata('user_login') == TRUE)
      {
         redirect('home');
      }

		$profil['data'] = $this->user->get_all('t_profil');

		$this->load->view('login', $profil);

	}

	public function profil()
	{

		if (!$this->session->userdata('user_login'))
      {
         redirect('home/login');
      }

		$get = $this->user->get_where('t_users', array('id_user' => $this->session->userdata('user_id')))->row();

		if($this->input->post('submit', TRUE) == 'Submit')
		{

			$this->load->library('form_validation');

			$this->form_validation->set_rules('nama1', 'Nama Depan', "required|min_length[3]|regex_match[/^[a-zA-Z'.]+$/]");
			$this->form_validation->set_rules('nama2', 'Nama Belakang', "regex_match[/^[a-zA-Z'.]+$/]");
			$this->form_validation->set_rules('pass', 'Masukkan Password Anda', "required|min_length[5]");
			$this->form_validation->set_rules('jk', 'Jenis Kelamin', "required");
			$this->form_validation->set_rules('telp', 'Telp', "required|min_length[8]|numeric");
			$this->form_validation->set_rules('alamat', 'Alamat', "required|min_length[10]");

			if ($this->form_validation->run() == TRUE)
			{

				if (password_verify($this->input->post('pass', TRUE), $get->password))
				{

					$data = array(
						'fullname' 	=> $this->input->post('nama1', TRUE).' '.$this->input->post('nama2', TRUE),
						'jk' 			=> $this->input->post('jk', TRUE),
						'telp' 		=> $this->input->post('telp', TRUE),
						'alamat' 	=> $this->input->post('alamat', TRUE)
					);
					$where = ['id_user' => $this->session->userdata('user_id')];

					if ($this->user->update('t_users', $data, $where))
					{

						$this->session->set_userdata(array('name' => $this->input->post('nama1', TRUE).' '.$this->input->post('nama2', TRUE)));

						redirect('home');

					} else {

						echo '<script type="text/javascript">alert("Username / Email tidak tersedia");</script>';

					}

				} else {

					echo '<script type="text/javascript">alert("Password Salah...");window.location.replace("'.base_url().'/home/logout")</script>';

				}

			}

		}

		$name 			= explode(' ', $get->fullname);
		$data['nama1'] = $name[0];
		$data['nama2'] = $name[1];
		$data['user'] 	= $get->username;
		$data['email'] = $get->email;
		$data['jk'] 	= $get->jk;
		$data['telp'] 	= $get->telp;
		$data['alamat']= $get->alamat;

		$this->template->fpjuragan('user_profil', $data);

	}

	public function password()
	{

		if (!$this->session->userdata('user_login'))
      {
         redirect('home/login');
      }

		if ($this->input->post('submit', TRUE) == 'Submit')
		{

			$this->load->library('form_validation');
			//validasi form
			$this->form_validation->set_rules('pass1', 'Password Baru', 'required|min_length[5]');
			$this->form_validation->set_rules('pass2', 'Ketik Ulang Password Baru', 'required|matches[pass1]');
			$this->form_validation->set_rules('pass3', 'Password Lama', 'required');

			if ($this->form_validation->run() == TRUE)
			{

				$get_data = $this->user->get_where('t_users', array('id_user' => $this->session->userdata('user_id')))->row();

				if (!password_verify($this->input->post('pass3',TRUE), $get_data->password))
				{

					echo '<script type="text/javascript">alert("Password lama yang anda masukkan salah");window.location.replace("'.base_url().'home/logout")</script>';

				} else {

					$pass = $this->input->post('pass1', TRUE);
					$data['password'] = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 10]);
					$cond = array('id_user' => $this->session->userdata('user_id'));

					$this->user->update('t_users', $data, $cond);

					redirect('home/logout');

				}

			}

		}

		$this->template->fpjuragan('pass');

	}

	public function transaksi()
	{

		if (!$this->session->userdata('user_id'))
		{
			redirect('home');
		}


		$data['get'] = $this->user->get_where('t_order', ['id_user' => $this->session->userdata('user_id')]);

		$this->template->fpjuragan('transaksi', $data);

	}

	public function detail_transaksi()
	{

		if (!is_numeric($this->uri->segment(3)))
		{
			redirect('home');
		}

		$table = "t_order o
						JOIN t_detail_order do ON (o.id_order = do.id_order)
						JOIN t_items i ON (do.id_item = i.id_item)";

		$data['get'] = $this->user->get_where($table, ['o.id_order' => $this->uri->segment(3)]);

		$this->template->fpjuragan('detail_transaksi', $data);

	}

	public function hapus_transaksi()
	{

		if (!is_numeric($this->uri->segment(3)))
		{
			redirect('home');
		}
		//kembalikan stok
		$table 	= 't_detail_order do
							JOIN t_items i ON (do.id_item = i.id_item)';
		$get 		= $this->user->get_where($table, ['id_order' => $this->uri->segment(3)]);

		foreach ($get->result() as $key) {
			//jumlahkan stok
			$stok = ($key->qty + $key->stok);
			//update stok
			$this->user->update('t_items', ['stok' => $stok], ['id_item' => $key->id_item]);
		}

		$tables = array('t_order', 't_detail_order');
		$this->user->delete($tables, ['id_order' => $this->uri->segment(3)]);

		redirect('home/transaksi');

	}

	public function transaksi_selesai()
	{

		if (!is_numeric($this->uri->segment(3)))
		{
			redirect('home');
		}

		$this->user->update('t_order', ['status_proses' => 'selesai'], ['id_order' => $this->uri->segment(3)]);

		redirect('home/transaksi');

	}


	public function kategori()
	{

		if (!$this->uri->segment(3))
		{
			redirect('home');
		}

		$offset = (!$this->uri->segment(4)) ? 0 : $this->uri->segment(4);

		$url = strtolower(str_replace([' ','%20','_'], '-', $this->uri->segment(3)));

		$table = 't_kategori k
						JOIN t_rkategori rk ON (k.id_kategori = rk.id_kategori)
						JOIN t_items i ON (rk.id_item = i.id_item)';
		//load library pagination
		$this->load->library('pagination');
		//configure
		$config['base_url'] 		= base_url().'home/kategori/'.$this->uri->segment(3);
		$config['total_rows'] 	= $this->user->get_where($table, ['i.aktif' => 1, 'k.url' => $url])->num_rows();
		$config['per_page'] 		= 6;
		$config['uri_segment'] 	= 4;

		$this->pagination->initialize($config);

		$data['link']  = $this->pagination->create_links();
		$data['data'] 	= $this->user->select_where_limit($table, ['i.aktif' => 1, 'k.url' => $url], $config['per_page'], $offset);
		$data['url'] = ucwords(str_replace(['-','%20','_'], ' ', $this->uri->segment(3)));

		$this->template->fpjuragan('home', $data);

	}



	public function logout()
	{

		$this->session->sess_destroy();
		redirect('home');

	}
}
