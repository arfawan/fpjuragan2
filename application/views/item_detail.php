<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<h4><i class="fa fa-search-plus"></i> Detail Item</h4>
<hr />
<br />
<?php
$key = $data->row();
?>

<div class="row">
   <div class="col l5 m12 s12 offset-l1">
      <!-- Gambar Item -->
      <div class="product-image">
         <img id="myimg" src="<?= base_url('assets/upload/'.$key->gambar); ?>" alt="<?= $key->gambar; ?>" class="img-responsive">
      </div>
      <div>
         <h5>Deskripsi</h5>
         <p><?= ucfirst(nl2br($key->deskripsi)); ?></p>
      </div>
   </div>
   <div class="col l4 m12 s12 offset-l1 detail">
      <!-- Detail Item -->
      <div class="item-title">
         <h4><?= ucfirst($key->nama_item); ?></h4>
      </div>
      <span>
         <i class="fa fa-tags"></i>
         <?php
         $val = '';
         foreach ($kat->result() as $value) {
            $val .= ', <a href="'.site_url('home/kategori/'.$value->url).'">'.$value->kategori.'</a>';
         }

         echo trim($val, ', ');
         ?>
      </span>
      <br />
      
      <!-- <span class="sub">Berat</span> -->
      <p>Berat : <?= number_format($key->berat, 0, ',', '.').' gr'; ?></p>
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
      <div class="clearfix"></div>
      <div class="price">
         <?= 'IDR. '.number_format($key->harga, 0, ',', '.').',-'; ?>
      </div>

                  <?php if ($this->session->userdata('user_login')) { ?>
                      <div class="card-action center">
                     <form action="<?= site_url('cart/add/'.$key->link); ?>" method="post">
                        <div>
                           <div class="left">
                              <input type="number" name="qty" value="1" min="1" max="<?= $key->stok; ?>" <?php if ($stok < 1) { echo 'disabled'; }?>>
                           </div>
                           <button type="submit" name="submit" value="Submit" class="btn rounded waves-effect waves-light" style="background-color: #003f5a" 
                        <?php  if($key->stok < 1)  { echo 'disabled'; } ?>><i class="fa fa-shopping-cart"> 
                        </i> Add to Cart
                         </button>
                        </div>
                     </form>
      </div>
                  <?php } else { ?>
                  <?php } ?> 

     
   </div>


</div>
