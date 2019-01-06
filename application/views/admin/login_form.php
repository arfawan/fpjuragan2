<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$profil = $data->row();
?>
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="Content-Type" content="text/html">
      <!-- Meta, title, CSS, favicons, etc. -->
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">

      <title>Login Form</title>

      <!-- Bootstrap -->
      <link href="<?= base_url('admin_assets/css/bootstrap.min.css'); ?>" rel="stylesheet">
      <!-- Font Awesome -->
      <link href="<?= base_url('admin_assets/font-awesome/css/font-awesome.min.css'); ?>" rel="stylesheet">
      <style media="screen" type="text/css">
         body{
            background: #F8F9F9;
         }
         .well {
            border-radius: 0px;
            margin-top: 10%;
            color: #616161;
         }
         .well hr{
            margin: 5px;
            border-color:#0077a3;
         }
         .header {
            font-size: 40px;
            color: #f7f7f7;
         }
         .header .fa {
            border: 2px solid #fcfcfc;
            border-radius: 50%;
            padding: 5px;
         }
         .container {
            padding-top: 5%;

            border-radius: 20px;
         }
         .form-control {
            font-size: 15px;
            border-radius: 20px;
         }
         .btn {
            padding: 5px 20px;
            font-size: 16px;
            border-radius: 20px;
         }
      </style>
   </head>

   <body>
      <div class="container">
       
         <div class="col-md-4 col-sm-12 col-md-offset-4">
            <?php
            if($this->session->flashdata('alert')) {
               echo '<div class="alert alert-warning alert-message">';
               echo $this->session->flashdata('alert');
               echo '</div>';
            }
            ?>
            <form class="well" action="" method="post">

               <h3><i class="fa fa-user" style="color: #003f5a;"> Login Administrator</i> </h3>
              
               <br />

               <div class="form-group">
                  <label>Username</label>
                  <input type="text" class="form-control" placeholder="Username" name="username">
               </div>

               <div class="form-group">
                  <label>Password</label>
                  <input type="password" class="form-control" placeholder="Password" name="password">
               </div>

               <div class="form-group" style="text-align:right">
                  <button type="submit" class="btn btn-primary" name="submit" value="Submit" style="background-color: #003f5a;">MASUK</button>
                  
               </div>

            </form>

            <p style="color:grey">Lupa dengan password anda ? Silahkan klik <a href="<?= site_url('lost_admin'); ?>" style="color: #de6600"><b>disini</b></a></p>
         </div>
      </div>
      <!-- jQuery -->
      <script src="<?= base_url('admin_assets/js/jquery.min.js'); ?>"></script>
      <!-- Bootstrap -->
      <script src="<?= base_url('admin_assets/js/bootstrap.min.js'); ?>"></script>
      <script type="text/javascript">
         $('.alert-message').alert().delay(3000).slideUp('slow');
      </script>
   </body>
</html>
