<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="x_panel">
   <div class="x_title">
      <h2>Detail Transaksi</h2>
     <div class="clearfix"></div>
   </div>
   <?php
   $user = $data->row();
   ?>
   <div class="row">
      <div class="col-md-2 col-sm-4" style="text-align:right">
         Nama Pemesan :
      </div>
      <div class="col-md-10 col-sm-8">
         <?= $user->fullname; ?>
      </div>
   </div>
   <div class="row">
      <div class="col-md-2 col-sm-4" style="text-align:right">
         Tanggal Pesan :
      </div>
      <div class="col-md-10 col-sm-8">
         <?= date('d M Y', strtotime($user->tgl_pesan)); ?>
      </div>
   </div>
   <div class="row">
      <div class="col-md-2 col-sm-4" style="text-align:right">
         Alamat :
      </div>
      <div class="col-md-10 col-sm-8">
         <?= $user->tujuan.', '.$user->kota; ?>
      </div>
   </div>
   <div class="row">
      <div class="col-md-2 col-sm-4" style="text-align:right">
         Kurir / Service :
      </div>
      <div class="col-md-10 col-sm-8">
         <?= $user->kurir.' / '.$user->service; ?>
      </div>
   </div>
   <div class="row">
      <div class="col-md-2 col-sm-4" style="text-align:right">
         Status :
      </div>
      <div class="col-md-10 col-sm-8">
         <?= ucfirst($user->status_proses); ?>
      </div>
   </div>
   <div class="row">
      <div class="col-md-2 col-sm-4" style="text-align:right">
         Bukti Pembayaran :
      </div>
      <div class="col-md-10 col-sm-8">
         <?= '<a style="color:#2196f3" href="'.base_url().'assets/bukti/'.$user->bukti.'" target="_blank">'.$user->bukti.'</a>'; ?>
      </div>
   </div>
   <div class="row">
      <div class="col-md-2 col-sm-4" style="text-align:right">
         Bukti Pengiriman :
      </div>
      <div class="col-md-10 col-sm-8">
         <?= '<a style="color:#2196f3" href="'.base_url().'assets/bukti/'.$user->pengiriman.'" target="_blank">'.$user->pengiriman.'</a>'; ?>
      </div>
   </div>
   <br />
   <div class="x_content">
      <div class="row">
         <div class="col-md-8 col-sm-12">
            <table class="table table-striped">
               <tr>
                  <th>#</th>
                  <th>Nama Item</th>
                  <th>Jumlah</th>
                  <th>Biaya</th>
               </tr>

               <?php
               $i = 1;
               $ongkir = $user->total;
               foreach ($data->result() as $key):
                  $ongkir -= $key->biaya;
                  ?>
                  <tr>
                     <td><?= $i++; ?></td>
                     <td><?=$key->nama_item?></td>
                     <td><?=$key->qty?></td>
                     <td style="text-align:right"><?=number_format($key->biaya, 0, ',','.')?></td>
                  </tr>
               <?php endforeach; ?>
               <tr>
                  <td colspan="3">Ongkir</td>
                  <td style="text-align:right"><?=number_format($ongkir, 0, ',','.')?></td>
               </tr>
               <tr>
                  <td colspan="3">Total</td>
                  <td style="text-align:right"><?=number_format($user->total, 0, ',','.')?></td>
               </tr>
            </table>
            <a href="#" class="btn btn-default" onclick="window.history.go(-1)">Kembali</a>
         </div>
      </div>
   </div>
</div>
