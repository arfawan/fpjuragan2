<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lost_User extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('form_validation');
      $this->load->model('app');
	}

   public function index()
   {
      if ($this->input->post('submit', TRUE) == 'Submit')
      {
         $this->form_validation->set_rules('email', 'Email', 'required|valid_email');

         if ($this->form_validation->run() == TRUE)
         {
            $get = $this->app->get_where('t_users', array('email' => $this->input->post('email', TRUE)));

            if ($get->num_rows() > 0)
            {
               //proses
		      $profil = $this->app->get_where('t_profil', ['id_profil' => 1])->row();
                  $this->load->library('email');
               $config['charset'] = 'utf-8';
               $config['useragent'] = 'Coba';
               $config['protocol'] = 'smtp';
               $config['mailtype'] = 'html';
               $config['smtp_host'] = 'ssl://smtp.gmail.com';
               $config['smtp_port'] = '465';
               $config['smtp_timeout'] = '5';
                  $config['smtp_user'] = $profil->email_toko; //isi dengan email gmail
                  $config['smtp_pass'] = $profil->pass_toko; //isi dengan password

               $config['crlf'] = "\r\n";
               $config['newline'] = "\r\n";
               $config['wordwrap'] = TRUE;

            
                  $this->email->initialize($config);

                        
               $key = md5(md5(time()));

               $this->email->from($profil->email_toko, $profil->title);
               $this->email->to($this->input->post('email', TRUE));
               $this->email->subject('Reset Password');
               $this->email->message(
                  'Apakah anda lupa dengan password anda ? silahkan klik <a href="'.base_url().'lost_user/reset/'.$key.'">disini</a> . Jika anda tidak merasa request fitur ini, silahkan abaikan pesan ini'
               );

               if ($this->email->send())
               {
                  $data['reset'] = $key;
                  $cond['email'] = $this->input->post('email', TRUE);
                  $this->app->update('t_users', $data, $cond);

                  $this->session->set_flashdata('success', "Email berhasil dikirim.. silahkan cek email anda");
               } else {
                  $this->session->set_flashdata('alert', "Email gagal dikirim... silahkan coba lagi..");
               }

            } else {
               //pesan
               $this->session->set_flashdata('alert', "email yang anda masukkan tidak terdaftar");
            }
         }
      }
		$data['data'] = $this->app->get_all('t_profil');
		$this->load->view('lost_pass', $data);
   }

	public function reset()
	{
		if ($this->uri->segment(3))
		{
			$this->load->view('form_reset');

			if ($this->input->post('submit', TRUE) == 'Submit')
			{
				$this->form_validation->set_rules('pass1', 'Password Baru', 'required|min_length[5]');
				$this->form_validation->set_rules('pass2', 'Ketik Ulang Password', 'required|matches[pass1]');

				if ($this->form_validation->run() == TRUE)
				{
					$pass = $this->input->post('pass1', TRUE);
					$data['password'] = password_hash($pass, PASSWORD_DEFAULT, ['cost' => 10]);
					$data['reset'] = '';

					$cond['reset'] = $this->uri->segment(3);

					$this->app->update('t_users', $data, $cond);

					$this->session->set_flashdata('success', "Password berhasil diperbarui");

					redirect('home/login');
				}
			}
		} else {
			redirect('lost_user');
		}
	}
}
