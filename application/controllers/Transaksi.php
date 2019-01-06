<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Transaksi extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->library('template');
      $this->load->model('admin');
	}

   public function index()
   {
	

      $select = ['id_order', 'tgl_pesan', 'bts_bayar', 'fullname', 'o.status_proses AS status'];
      $table = "t_order o JOIN t_users u ON (o.email = u.email)";

		$data['data'] = $this->admin->select_all($select, $table);

		$this->template->admin('admin/transaksi', $data);
   }

   
   public function konfirmasi()
   {
      $this->cek_login();

      if (!is_numeric($this->uri->segment(3)))
      {
         redirect('transaksi');
      }

		$select = [
						'o.id_order AS id_order',
						'tgl_pesan',
						'bts_bayar',
						'nama_pemesan',
						'status_proses',
						'pos',
						'service',
						'kota',
						'tujuan',
						'total',
						'biaya',
						'kurir',
						'nama_item',
						'qty',
						'email'
					];

      $table = "t_order o
						JOIN t_detail_order do ON (o.id_order = do.id_order)
						JOIN t_items i ON (do.id_item = i.id_item)";

		$detail = $this->admin->select_where($select, $table, ['o.id_order' => $this->uri->segment(3)]);
		$profil = $this->admin->get_where('t_profil', ['id_profil' => 1])->row();

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
									
		$table = '';
		$no = 1;
		$biaya = 0;
		foreach ($detail->result() as $item) {
			$table .= '<tr><td>'.$no++.'</td><td>'.$item->nama_item.'</td><td>'.$item->qty.'</td><td style="text-align:right">'.number_format($item->biaya, 0, ',', '.').'</td></tr>';

			$biaya += $item->biaya;
		}

		$info_detail = $detail->row();
		$ongkir = $info_detail->total - $biaya;

		$this->email->from($profil->email_toko, $profil->title);
		$this->email->to($info_detail->email);
		$this->email->subject('Status Pemesanan');
		$this->email->message(
			'Terima Kasih telah melakukan pemesanan di toko kami, Saat ini Pesanan Anda dengan detail sebagai berikut :<br/><br/>
			Id Order : '.$info_detail->id_order.' ('.$info_detail->tgl_pesan.')<br/><br/>
			<table border="1" style="width: 80%">
			<tr><th>#</th><th>Nama Barang</th><th>Jumlah</th><th>Harga</th></tr>
			'.$table.'
			<tr><td colspan="3">Ongkos Kirim</td><td style="text-align:right">'.number_format($ongkir, 0, ',', '.').'</td></tr>
			<tr><td colspan="3">Total</td><td style="text-align:right">'.number_format($info_detail->total, 0, ',', '.').'</td></tr>
			</table><br />Sedang kami Proses.
			'
		);

		if ($this->email->send())
		{
			$this->admin->update('t_order', ['status_proses' => 'proses'], ['id_order' => $this->uri->segment(3)]);

		} else {
			echo $this->email->print_debugger(array('header'));
		}

		redirect('transaksi');
   }

   public function delete()
   {
      $this->cek_login();

      if (!is_numeric($this->uri->segment(3)))
      {
         redirect('transaksi');
      }

      $this->admin->delete(['t_order', 't_detail_order'], ['id_order' => $this->uri->segment(3)]);

      redirect('transaksi');
   }

   public function detail()
   {
      $this->cek_login();

      if (!is_numeric($this->uri->segment(3)))
      {
         redirect('transaksi');
      }

      $select = [
						'o.id_order AS id_order',
						'tgl_pesan',
						'bts_bayar',
						'nama_pemesan as fullname',
						'status_proses',
						'pos',
						'service',
						'kota',
						'tujuan',
						'total',
						'biaya',
						'kurir',
						'nama_item',
						'qty',
						'bukti',
						'pengiriman'
					];

      $table = "t_order o
						JOIN t_detail_order do ON (o.id_order = do.id_order)
						JOIN t_items i ON (do.id_item = i.id_item)";

      $data['data'] = $this->admin->select_where($select, $table, ['o.id_order' => $this->uri->segment(3)]);

      $this->template->admin('admin/detail_transaksi', $data);
   }

	public function up_bukti()
	{
		//validasi uri
		if (!$this->uri->segment(3)) { redirect('transaksi'); }

		if ($this->input->post('submit', TRUE) == 'Submit') {
			//load libarary form validation
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('trans', 'Id Transaksi', 'required|numeric');

			if ($this->form_validation->run() == TRUE)
			{
				$config['upload_path'] = './assets/bukti/';
				$config['allowed_types'] = 'jpg|png|jpeg';
				$config['max_size'] = '2048';
				$config['file_name'] = 'bukti'.time();

				$this->load->library('upload', $config);

				if ($this->upload->do_upload('img'))
				{
					$gbr = $this->upload->data();
					//proses update data
					$this->admin->update('t_order', ['pengiriman' => $gbr['file_name']], ['id_order' => $this->input->post('trans', TRUE)]);

					redirect('transaksi');

				} else {
					$this->session->set_flashdata('alert', 'anda belum memilih foto');
				}

			}
		}

		$data['id_trans']	=	$this->uri->segment(3);

		$this->template->admin('admin/up_bukti', $data);
	}

	public function report()
	{
		$this->load->library('form_validation');
		$this->cek_login();

		if ($this->input->post('submit', TRUE) == 'Submit')
		{
			$this->form_validation->set_rules('bln', 'Bulan', 'required|numeric');
			$this->form_validation->set_rules('thn', 'Tahun', 'required|numeric');

			if ($this->form_validation->run() == TRUE)
			{
				$bln = $this->input->post('bln', TRUE);
				$thn = $this->input->post('thn', TRUE);
			}

		} else {
			$bln = date('m');
			$thn = date('Y');
		}
		//YYYY-mm-dd
		//2017-04-31
		$awal  = $thn.'-'.$bln.'-01';
		$akhir = $thn.'-'.$bln.'-31';

		$where = ['tgl_pesan >=' => $awal, 'tgl_pesan <=' => $akhir, 'status_proses !=' => 'belum'];

		$data['data'] 	= $this->trans->report($where);
		$data['bln'] 	= $bln;
		$data['thn']	= $thn;

		$this->template->admin('admin/laporan', $data);
	}

	public function cetak()
	{
		$this->cek_login();
		if (!is_numeric($this->uri->segment(3)) || !is_numeric($this->uri->segment(4)) )
		{
			redirect('transaksi');
		}

		$bln 	= $this->uri->segment(3);
		$thn 	= $this->uri->segment(4);
		$awal = $thn.'-'.$bln.'-01';
		$akhir= $thn.'-'.$bln.'-31';

		$where = ['tgl_pesan >=' => $awal, 'tgl_pesan <=' => $akhir, 'status_proses !=' => 'belum'];

		$data['data'] 	= $this->trans->report($where);
		$data['bln'] 	= $bln;
		$data['thn'] 	= $thn;

		$this->load->view('admin/cetak', $data);
	}

	function cek_login()
	{
		if (!$this->session->userdata('admin'))
		{
			redirect('login');
		}
	}
}