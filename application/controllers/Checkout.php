<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Checkout extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library(array('template', 'cart', 'encryption'));
		$this->load->model('user');
	}

	public function index()
	{
		if (!$this->cart->contents())
		{
			redirect('home');
		}

		if ($this->input->post('submit', TRUE) == 'Submit')
    	{
			$this->load->library('form_validation');

      		$this->form_validation->set_rules('prov', 'Provinsi', 'required');
			$this->form_validation->set_rules('kota', 'Kota / Kabupaten', 'required');
			$this->form_validation->set_rules('alamat', 'Alamat', 'required');
			$this->form_validation->set_rules('kd_pos', 'Kode Pos', 'required|numeric|min_length[5]');
			$this->form_validation->set_rules('kurir', 'Kurir', 'required');
			$this->form_validation->set_rules('layanan', 'Layanan', 'required');
			$this->form_validation->set_rules('ongkir', 'Ongkir', 'required|numeric');
			$this->form_validation->set_rules('total', 'Total', 'required');

			if (!$this->session->userdata('user_id'))
			{
				$this->form_validation->set_rules('first_name', 'Nama Depan', 'required');
				$this->form_validation->set_rules('user_mail', 'Email', 'required|valid_email');
			}

         if ($this->form_validation->run() == TRUE)
         {
				if ($this->session->userdata('user_id'))
				{
          			$get = $this->user->get_where('t_users', ['id_user' => $this->session->userdata('user_id')]);
					$user = $get->row();

					$nama_pemesan = $user->fullname;
					$email = $user->email;
				} else {
					$nama_pemesan = $this->input->post('first_name', TRUE).' '.$this->input->post('last_name', TRUE);
					$email = $this->input->post('user_mail', TRUE);
				}

				$profil 	= $this->user->get_where('t_profil', ['id_profil' => 1])->row();
				$admin		= $this->user->get_where('t_admin', ['id_admin' => 1])->row();
				//proses
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
				

        		$id_order 	= time();
				$kota 		= explode(",", $this->encryption->decrypt($this->input->post('kota', TRUE)));
				$alamat 	= $this->input->post('alamat', TRUE);
				$pos 		= $this->input->post('kd_pos', TRUE);
				$kurir 		= $this->input->post('kurir', TRUE);
				$layanan 	= explode(",", $this->encryption->decrypt($this->input->post('layanan', TRUE)));
				$ongkir 	= $layanan[0];
				$total 		= ($layanan[0] + $this->cart->total());
				$tgl_pesan 	= date("Y-m-d");
				$bts 		= date("Y-m-d", mktime(0,0,0, date("m"), date("d") + 3, date("Y")));
				$bts_bayar	= date('d-m-Y', strtotime($bts));

				$table = '';
				$no = 1;
				foreach ($this->cart->contents() as $carts) {
					$table .= '<tr><td>'.$no++.'</td><td>'.$carts['name'].'</td><td>'.$carts['qty'].'</td><td style="text-align:right">'.number_format($carts['subtotal'], 0, ',', '.').'</td></tr>';
				}

        $this->email->from($profil->email_toko, $profil->title);
        $this->email->to($email);
        $this->email->subject('Pembayaran');
        $this->email->message(
          'Terima Kasih telah melakukan pemesanan di toko kami, selanjutnya silahkan anda mentransfer uang senilai <b>Rp. '.number_format($total, 0, ',', '.').',-</b> ke no. rekening <b>'.$profil->rekening.'</b> segera lakukan pembayaran dan upload bukti pembayaran sebelum tanggal '.$bts_bayar.' agar pesanan anda bisa kami proses. Detail pembayaran sebagai berikut :<br/><br/>
					Id Order : '.$id_order.' ('.$tgl_pesan.')<br/><br/>
					<table border="1" style="width: 80%">
						<tr><th>#</th><th>Nama Barang</th><th>Jumlah</th><th>Harga</th></tr>
						'.$table.'
						<tr><td colspan="3">Ongkos Kirim</td><td style="text-align:right">'.number_format($ongkir, 0, ',', '.').'</td></tr>
						<tr><td colspan="3">Total</td><td style="text-align:right">'.number_format($total, 0, ',', '.').'</td></tr>
					</table>
					'
          );

          if ($this->email->send())
          {
            	$data = array(
						'id_order' 		=> $id_order,
						'id_user' => $user->id_user,
						'nama_pemesan' 	=> $nama_pemesan,
						'email' 		=> $email,
						'total' 		=> $total,
						'tujuan' 		=> $alamat,
						'pos' 			=> $pos,
						'kota' 			=> $kota[1],
						'kurir' 		=> $kurir,
						'service' 		=> $layanan[1],
						'tgl_pesan' 	=> $tgl_pesan,
						'bts_bayar' 	=> $bts,
						'status_proses'	=> 'belum'
					);

				if ($this->user->insert('t_order', $data)) {

					foreach ($this->cart->contents() as $key) {
						$detail = [
									'id_order' 	=> $id_order,
									'id_item' 	=> $key['id'],
									'qty' 		=> $key['qty'],
									'biaya' 		=> $key['subtotal']
								];
						$this->user->insert('t_detail_order', $detail);
					}

					$this->cart->destroy();

					$this->email->from($profil->email_toko, $profil->title);
		        	$this->email->to($admin->email);
		        	$this->email->subject('Pesanan Masuk');
		        	$this->email->message(
		          	'Hai admin,<br /><br />Ada pesanan baru dari '.$profil->title.' dengan ID pesanan '.$id_order.' pada tanggal '.date('d M Y', strtotime($tgl_pesan)).'. Silahkan login untuk melihat detail pesanan secara lengkap.'
		        	);

					if ($this->email->send())
					{
						echo '<script type="text/javascript">alert("Silahkan cek email anda untuk detail pembayaran...");window.location.replace("'.base_url().'")</script>';
					}
				}
          } else {
            echo $this->email->print_debugger(array('headers'));
          }

      	}
      }

		$key['key']  = $this->user->get_where('t_profil', ['id_profil' => 1]);

		if ($this->session->userdata('user_id'))
		{
			$this->template->fpjuragan('checkout', $key);
		} else {
			$this->template->fpjuragan('checkout_guest', $key);
		}
	}

   public function city()
   {
		if (!$this->input->is_ajax_request()) {

			redirect('checkout');

		} else {

			$this->load->library('form_validation');

			$this->form_validation->set_rules('prov', 'Provinsi', 'required');

			if ($this->form_validation->run() == TRUE)
			{
		      $prov = explode(",", $this->encryption->decrypt($this->input->post('prov', TRUE)));
				$key  = $this->user->get_where('t_profil', ['id_profil' => 1])->row();
		      $curl = curl_init();

		      curl_setopt_array($curl, array(
		        CURLOPT_URL => "http://api.rajaongkir.com/starter/city?province=$prov[0]",
		        CURLOPT_RETURNTRANSFER => true,
		        CURLOPT_ENCODING => "",
		        CURLOPT_MAXREDIRS => 10,
		        CURLOPT_TIMEOUT => 30,
		        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		        CURLOPT_CUSTOMREQUEST => "GET",
		        CURLOPT_HTTPHEADER => array(
		          "key: ".$key->api_key
		        ),
		      ));

		      $response = curl_exec($curl);
		      $err = curl_error($curl);

		      curl_close($curl);

		      if ($err) {
		        echo "cURL Error #:" . $err;
		      } else {
		         $data = json_decode($response, TRUE);

		         echo '<option value="" selected disabled>Kota / Kabupaten</option>';

		         for ($i=0; $i < count($data['rajaongkir']['results']); $i++) {
		            echo '<option value="'.$this->encryption->encrypt($data['rajaongkir']['results'][$i]['city_id'].','.$data['rajaongkir']['results'][$i]['city_name']).'">'.$data['rajaongkir']['results'][$i]['city_name'].'</option>';
		         }
		      }
			}
		}
   }

	public function getcost()
	{
		if (!$this->input->is_ajax_request()) {

			redirect('checkout');

		} else {

			$this->load->library('form_validation');

			$this->form_validation->set_rules('dest', 'Tujuan', 'required');
			$this->form_validation->set_rules('kurir', 'Kurir', 'required');

			if ($this->form_validation->run() == TRUE)
			{
				$api  = $this->user->get_where('t_profil', ['id_profil' => 1])->row();
				$asal = $api->asal;
				$dest = explode(",", $this->encryption->decrypt($this->input->post('dest', TRUE)));
				$kurir = $this->input->post('kurir', TRUE);
				$berat = 0;

				foreach ($this->cart->contents() as $key) {
					$berat += ($key['weight'] * $key['qty']);
				}

				$curl = curl_init();

				curl_setopt_array($curl, array(
				  CURLOPT_URL => "http://api.rajaongkir.com/starter/cost",
				  CURLOPT_RETURNTRANSFER => true,
				  CURLOPT_ENCODING => "",
				  CURLOPT_MAXREDIRS => 10,
				  CURLOPT_TIMEOUT => 30,
				  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
				  CURLOPT_CUSTOMREQUEST => "POST",
				  CURLOPT_POSTFIELDS => "origin=$asal&destination=$dest[0]&weight=$berat&courier=$kurir",
				  CURLOPT_HTTPHEADER => array(
				    "content-type: userlication/x-www-form-urlencoded",
				    "key: ".$api->api_key
				  ),
				));

				$response = curl_exec($curl);
				$err = curl_error($curl);

				curl_close($curl);

				if ($err) {
				  	echo "cURL Error #:" . $err;
				} else {
				  	$data = json_decode($response, TRUE);

				  	echo '<option value="" selected disabled>Layanan yang tersedia</option>';

				  	for ($i=0; $i < count($data['rajaongkir']['results']); $i++) {

						for ($l=0; $l < count($data['rajaongkir']['results'][$i]['costs']); $l++) {

							echo '<option value="'.$this->encryption->encrypt($data['rajaongkir']['results'][$i]['costs'][$l]['cost'][0]['value'].','.$data['rajaongkir']['results'][$i]['costs'][$l]['service']).'">';
							echo $data['rajaongkir']['results'][$i]['costs'][$l]['service'].'('.$data['rajaongkir']['results'][$i]['costs'][$l]['description'].')</option>';

						}

				  	}
			  	}
			}
		}
	}

	public function cost()
	{
		if (!$this->input->is_ajax_request()) {

			redirect('checkout');

		} else {

			$this->load->library('form_validation');

			$this->form_validation->set_rules('layanan', 'Layanan', 'required');

			if ($this->form_validation->run() == TRUE)
			{
				$biaya = explode(',', $this->encryption->decrypt($this->input->post('layanan', TRUE)));
				$total = $this->cart->total() + $biaya[0];

				echo $biaya[0].','.$total;
			}
		}
	}
}
