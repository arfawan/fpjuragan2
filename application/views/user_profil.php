<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<div class="row">
   <div class="col m10 s12 offset-m1">
      <h4 style="color:#007a7a"><i class="fa fa-user"></i> User Profil</h4>
      <hr />
      <br />
      <?= validation_errors('<p style="color:red">', '</p>'); ?>
      <form action="" method="post">
         <div class="col m10 s12">
            <div class="row">
               <div class="input-field col m6 s12">
                  <input id="first_name" type="text" class="validate" name="nama1" value="<?= $nama1; ?>">
                  <label for="first_name">Nama Depan</label>
               </div>
               <div class="input-field col m6 s12">
                  <input id="last_name" type="text" class="validate" name="nama2" value="<?= $nama2; ?>">
                  <label for="last_name">Nama Belakang</label>
               </div>
            </div>

            <div class="row">
               <div class="input-field col m8 s12">
                  <input type="text" id="username" class="validate" name="user" value="<?= $user; ?>" readonly="readonly">
                  <label for="username">Username</label>
               </div>
            </div>

            <div class="row">
               <div class="input-field col m8 s12">
                  <input type="email" id="email" class="validate" name="email" value="<?= $email; ?>" readonly="readonly">
                  <label for="email">E-Mail</label>
               </div>
            </div>

            <div class="row">
               <div class="input-field col s12">
                  <label>Jenis Kelamin</label>
                  <br/>
                  <p>
                     <input class="with-gap" value="L" type="radio" id="lk" name="jk" <?php if ($jk == 'L') { echo 'checked'; } ?>/>
                     <label for="lk">Laki-laki</label>
                     <input class="with-gap" value="P" type="radio" id="pr" name="jk" <?php if ($jk == 'P') { echo 'checked'; } ?>/>
                     <label for="pr">Perempuan</label>
                  </p>
               </div>
            </div>

            <div class="row">
               <div class="input-field col m8 s12">
                  <input id="telp" type="number" class="validate" name="telp" value="<?= $telp; ?>">
                  <label for="telp">Telp</label>
               </div>
            </div>

            <div class="row">
               <div class="input-field col s12">
                  <textarea class="materialize-textarea" id="alamat" name="alamat"><?= $alamat; ?></textarea>
                  <label for="alamat">Alamat</label>
               </div>
            </div>

            <div class="row">
               <div class="input-field col m8 s12">
                  <input id="password" type="password" class="validate" name="pass">
                  <label for="password">Masukkan Password Anda</label>
               </div>
            </div>

            <div class="row right">
               <button type="button" onclick="window.history.go(-1)" class="btn rounded waves-effect waves-light" style="background-color: #007a7a;width: 130px;">Kembali</button>
               <button type="submit" name="submit" value="Submit" class="btn rounded waves-effect waves-light" style="background-color: #003f5a;width: 130px;">Submit <i class="fa fa-paper-plane"></i></button>
            </div>
         </div>
      </form>
   </div>
</div>
