<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
   <div class="col m10 s12 offset-m1">
      <h4 style="color:#003f5a"><i class="fa fa-upload"></i> Upload Bukti Pembayaran</h4>
      <hr />
      <br />
      <?= validation_errors('<p style="color:red">', '</p>'); ?>
      <form action="" method="post" enctype="multipart/form-data">
         <div class="col m10 s12">
            <div class="row">
               <div class="input-field col s11 offset-s1">
                  <input id="no_invoice" type="text" class="validate" required value="<?= $id_invoice; ?>" name="id_invoice">
                  <label for="no_invoice">No. Invoice / Id Pemesanan</label>
               </div>
            </div>

            <div class="row">
               <div class="file-field input-field col s11 offset-s1">
                  <div class="btn waves-effect waves-light">
                     <span>File</span>
                     <input type="file" name="bukti">
                  </div>
                  <div class="file-path-wrapper">
                     <input class="file-path validate" type="text">
                  </div>
                  <i class="help-text">* Gunakan Format JPG, JPEG, PNG</i>
               </div>
            </div>

            <br />

            <div class="row right">
               <button type="submit" name="submit" value="Submit" class="btn rounded waves-effect waves-light" style="background-color: #003f5a;width: 130px;">Submit <i class="fa fa-paper-plane"></i></button>
               <button type="button" onclick="window.history.go(-1)" class="btn rounded waves-effect waves-light" style="background-color: #007a7a;width: 130px;">Kembali</button>
            </div>

            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

         </div>
      </form>
   </div>
</div>
