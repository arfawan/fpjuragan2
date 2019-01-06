<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
      <img src="<?= base_url('assets/baner/juragan.jpg'); ?>" style="width:100%;" >
</div>
<div class="row">

   <div class="col s12 l3 m12">
      <ul class="collection with-header">

            <!-- <form action="<?=site_url('home/price');?>" method="post"> -->
               <div class="row">

                  <img src="<?= base_url('assets/baner/juraganicon.jpg'); ?>" style="width:100%;" >

                  <ul id="kat1" class="genre">
                     <li><h5 style="color:#de6600"><b>KATEGORI</b></h5></li>
                     <?php foreach ($kategori->result() as $kat): ?>
                        <li>
                           <a href="<?=site_url('home/kategori/'.$kat->url);?>"> <?=$kat->kategori;?></a>
                        </li>
                     <?php endforeach; ?>
                     <style>
.genre {
  list-style-type: none;
  margin: 0;
  padding: 0;
  width: 100%;
  /*background-color: #f1f1f1;*/
}

.genre li a {
  display: block;
  color: #000;
  padding: 8px 16px;
  text-decoration: none;
}

/* Change the link color on hover */
.genre li a:hover {
  background-color: #de6600;
  color: white;
}
</style>
                  </ul> 


<!--                   <div class="input-field col s12 m12 l12">
                     <input type="text" class="validate uang" name="min" min="0" value="0" onfocus="this.value = ''">
                     <label>Low Price</label>
                  </div>
                  <div class="input-field col s12 m12 l12">
                     <input type="text" class="validate uang" name="max" min="0" value="0" onfocus="this.value = ''">
                     <label>Hight Price</label>
                  </div>
                  <button type="submit" name="submit" class="waves-effect waves-light btn-flat blue white-text" value="Filter">Filter</button> -->
               </div>
            <!-- </form> -->

      </ul>
   </div>
   <div class="col s12 l9 m12 content">
      <?php
      //tampilkan pesan gagal
      if ($this->session->flashdata('alert'))
      {
         echo '<div class="alert alert-danger alert-message">';
         echo '<center>'.$this->session->flashdata('alert').'</center>';
         echo '</div>';
      }
      //tampilkan pesan success
      if ($this->session->flashdata('success'))
      {
         echo '<div class="alert alert-success alert-message">';
         echo '<center>'.$this->session->flashdata('success').'</center>';
         echo '</div>';
      }
      //tampilkan header pencarian
      if (isset($search) && $search != null)
      {
         echo '<h4>Hasil Pencarian dari "'.$search.'"</h4>';
      }
      //tampilkan header kategori
      if ($data->num_rows() > 0)
      {
         if (isset($url)) {
            echo '<h4>List Item Pada Kategori "'.$url.'"</h4><hr />';
         }
      ?>
      <div class="row">
        
         <?php foreach($data->result() as $key) : ?>
            <div class="col s12 m6 l4">
               <div class="card medium">
                  <div class="card-image">
                     <a href="<?= site_url('home/detail/'.$key->link); ?>">
                        <img src="<?= base_url('assets/upload/'.$key->gambar); ?>" class="responsive-img">
                     
                     </a>

                  </div>
                 <p class="price">
                     Rp. <?= number_format($key->harga, 0, ',', '.'); ?>,-
                  </p>
                  <div class="card-action center">

                     <form action="<?= site_url('cart/add/'.$key->link); ?>" method="post">
                        
                        <div class="row">
                        <div class="left">
                           <?php
                           if ($this->cart->contents())
                           {
                              foreach ($this->cart->contents() as $cart) {
                                 $stok = ($cart['id'] == $key->id_item) ? ($key->stok - $cart['qty']) : $key->stok;
                              }
                           } else {
                              $stok = $key->stok;
                           }

                           if ($stok >= 10)
                           {
                              echo 'Stok : <span class="badge green white-text">'.$stok.'</span>';
                           } elseif ($stok < 10 && $stok > 0) {
                              echo 'Stok : <span class="badge orange white-text">'.$stok.'</span>';
                           } else {
                              echo 'Stok : <span class="badge red white-text">Habis</span>';
                           }
                           ?>
                        </div>

                     </div>
                     <h6 style="color: #003f5a;"><b><?= $key->nama_item; ?></b></h6>
                        <!-- <input type="number" name="qty" value="1" min="1" max="<?= $key->stok; ?>" <?php if ($stok < 1) { echo 'disabled'; }?>> -->

                        <a href="<?= site_url('home/detail/'.$key->link); ?>" class="waves-effect waves-light btn rounded  white-text tooltipped" data-position="bottom" data-delay="50" data-tooltip="Lihat Detail">
                           <i class="fa fa-search-plus"></i>
                        </a>



                  <?php if ($this->session->userdata('user_login')) { ?>
                        <input type="hidden" name="qty" value="1" min="1" max="<?= $key->stok; ?>" <?php if ($stok < 1) { echo 'disabled'; }?>>
                        <button  type="submit" class="rounded waves-effect waves-light btn white-text tooltipped" name="submit" value="Submit" <?php if ($key->stok < 1) { echo 'disabled'; }  ?> data-position="bottom" data-delay="50" data-tooltip="Tambah ke Keranjang" style="background-color: #003f5a">
                           <i class="fa fa-shopping-cart">     </i>
                        </button>
                  <?php } else { ?>
                  <?php } ?>

                     <p class="price" style="font-size: 18px;">
                     Rp. <?= number_format($key->harga, 0, ',', '.'); ?>,-
                  </p>
                     </form>
                  </div>
               </div>
            </div>
         <?php endforeach; ?>
      </div>
      <?= $link; ?>
      <?php
      } else {
         if (isset($url)) {
            echo '<h5>Kategori "'.$url.'" Masih Kosong</h5><hr />';
         } else {
            echo '<h5>Item tidak ditemukan....</h5>';
         }
      }
      ?>
   </div>
</div>
