<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$prof = $profil->row();

?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">

      <title> <?= $prof->title; ?></title>
      <!-- Materialize Css -->
      <link rel="stylesheet" href="<?= base_url('assets/css/materialize.min.css'); ?>">
      <!-- Font-Awesome -->
      <link rel="stylesheet" href="<?= base_url('admin_assets/font-awesome/css/font-awesome.min.css'); ?>">
      <!-- customCss -->
      <link rel="stylesheet" href="<?= base_url('assets/css/custom.css?ver='.filemtime('assets/css/custom.css')); ?>">
   </head>
   <body>
      <header>
         <nav class="darken-2" style="background-color: #003f5a;">
            <div class="nav-wrapper">
               <a href="#" data-activates="slide-out" class="button-collapse"><i class="fa fa-bars"></i></a>
               <a href="<?=site_url('home');?>" class="brand-logo"><?= $prof->title; ?> <i class=" fa fa-fort-awesome"></i> </a>
               <ul id="nav-mobile" class="right hide-on-med-and-down" style="max-width:45%">
                  

                  <?php if ($this->session->userdata('user_login')) { ?>


                     <li><a href="#" class="dropdown-button" data-activates="drop1"><i class="fa fa-user"></i> <?= ucwords($this->session->userdata('name')); ?><i class="fa fa-caret-down right"></i></a></li>

                     <ul class="dropdown-content" id="drop1">
                        <li><a href="<?= site_url('home/profil'); ?>"><i class="fa fa-user"></i> Profil</a></li>
                        <li><a href="<?= site_url('home/password'); ?>"><i class="fa fa-key"></i> Ubah Password</a></li>
                        <li><a href="<?= site_url('home/transaksi'); ?>"><i class="fa fa-exchange"></i> Transaksi</a></li>
                        <li><a href="<?= site_url('home/upload_bukti'); ?>"><i class="fa fa-upload"></i> Upload Pembayaran</a></li>
                        <li><a href="<?= site_url('home/logout'); ?>"><i class="fa fa-sign-out"></i> logout</a></li>
                     </ul>

                     <li>
                     <a href="<?= site_url('cart'); ?>">
                        <i class="fa fa-shopping-cart"></i>
                        <?php
                        if ($this->cart->total() > 0) {
                           echo 'Rp. '.number_format($this->cart->total(), 0, ',', '.');
                        } else {
                           echo 'cart';
                        }
                        ?>
                     </a>
                  </li>
                  <?php } else { ?>
                     <li><a href="#" class="dropdown-button" data-activates="drop3"><i class="fa fa-user"></i> Akun<i class="fa fa-caret-down right"></i></a></li>

                     <ul class="dropdown-content" id="drop3">
                        <li><a href="<?= site_url('home/login'); ?>"><i class="fa fa-sign-in"></i> login</a></li>
                        <li><a href="<?= site_url('home/registrasi'); ?>"><i class="fa fa-edit"></i> Registrasi</a></li>
                     </ul>

                  <?php } ?>



               </ul>
            </div>
        
         </nav>
      </header>

      <main>

         <div class="cont">
                  <div class="item">
                  <?= $content; ?>
                  </div>

            <footer class="page-footer darken-3" style="background-color: #007a7a;">
               <div class="container">
                  <div class="row">
                     <div class="col l6 s12">

                     </div>
                     <div class="col l5 offset-l1 s12">

                     </div>
                  </div>
               </div>
               <div class="footer-copyright" style="background-color: #003f5a">
                  <div class="container">
                     <!-- Copyright © <?= date('Y').' '.$prof->title; ?>. All Rights Reserved. -->
                     
                  </div>
               </div>
            </footer>
         </div>
      </main>

      <a href="" class="btn-floating btn-large waves-effect waves-light back-top"><i class="fa fa-angle-double-up"></i></a>

      <!-- Jquery -->
      <script type="text/javascript" src="<?= base_url('admin_assets/js/jquery.min.js'); ?>"></script>
      <!-- materialize -->
      <script type="text/javascript" src="<?= base_url('assets/js/materialize.min.js'); ?>"></script>
      <script type="text/javascript" src="<?= base_url('assets/js/custom.js'); ?>"></script>
      <script type="text/javascript" src="<?= base_url('assets/js/jquery.mask.min.js'); ?>"></script>
      <!-- custom -->
      <?php if(strtolower($this->uri->segment(1)) == 'checkout') { ?>

         <script type="text/javascript">

            $(document).ready(function() {
               function convertToRupiah(angka)
               {

                  var rupiah = '';
                  var angkarev = angka.toString().split('').reverse().join('');

                  for(var i = 0; i < angkarev.length; i++)
                    if(i%3 == 0) rupiah += angkarev.substr(i,3)+'.';

                  return rupiah.split('',rupiah.length-1).reverse().join('');

               }

               $('#prov').change(function() {
                  var prov = $('#prov').val();

                  $.ajax({
                     url: "<?=base_url();?>checkout/city",
                     method: "POST",
                     data: { prov : prov },
                     success: function(obj) {
                        $('#kota').html(obj);
                     }
                  });
               });

               $('#kota').change(function() {
                  var dest = $('#kota').val();
                  var kurir = $('#kurir').val()

                  $.ajax({
                     url: "<?=base_url();?>checkout/getcost",
                     method: "POST",
                     data: { dest : dest, kurir : kurir },
                     success: function(obj) {
                        $('#layanan').html(obj);
                     }
                  });
               });

               $('#kurir').change(function() {
                  var dest = $('#kota').val();
                  var kurir = $('#kurir').val()

                  $.ajax({
                     url: "<?=base_url();?>checkout/getcost",
                     method: "POST",
                     data: { dest : dest, kurir : kurir },
                     success: function(obj) {
                        $('#layanan').html(obj);
                     }
                  });
               });

               $('#layanan').change(function() {
                  var layanan = $('#layanan').val();

                  $.ajax({
                     url: "<?=base_url();?>checkout/cost",
                     method: "POST",
                     data: { layanan : layanan },
                     success: function(obj) {
                        var hasil = obj.split(",");

                        $('#ongkir').val(convertToRupiah(hasil[0]));
                        $('#total').val(convertToRupiah(hasil[1]));
                     }
                  });
               });
            });
         </script>

      <?php } ?>

      <script type="text/javascript">
         $(".button-collapse").sideNav();
         $(".modal").modal();
         $('.carousel').carousel();

         $(document).ready(function() {
            $(".uang").mask("00,000.000.000", {reverse:true});

            $(window).scroll(function(){
               if ($(this).scrollTop() > 100) {
                  $('.back-top').fadeIn();
               } else {
                  $('.back-top').fadeOut();
               }
            });
            $('.back-top').click(function(){
               $("html, body").animate({ scrollTop: 0 }, 600);
               return false;
            });
         });
         $('.alert-message').alert().delay(3000).slideUp('slow');
      </script>
   </body>
</html>