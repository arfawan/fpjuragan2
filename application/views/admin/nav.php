<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$profile = $profil->row();
?>
<div class="col-md-3 left_col" style="background-color: #303030">
   <div class="left_col scroll-view"  style="background-color: #303030">
      <div class="navbar nav_title" style="border: 0; background-color: #303030" >
         <a href="<?= base_url(); ?>administrator" class="site_title"> <span > <?= $profile->title; ?></span></a>
      </div>

      <div class="clearfix"></div>

      <br />

      <!-- sidebar menu -->
      <div id="sidebar-menu" class="main_menu_side hidden-print main_menu" style="background-color: #e74c3c" >
         <div class="menu_section" style="background-color: #303030">
            <ul class="nav side_menu ">
               <li >
                  <a href="<?= site_url('administrator'); ?>" style="color:  #fdfefe "><i class="fa fa-home"style="color:  #fdfefe "></i> Home</a>
               </li>
               <li >
                  <a href="<?= site_url('item'); ?>"style="color:  #fdfefe "><i class="fa fa-cubes"style="color:  #fdfefe "></i> Item</a>
               </li>
               <li>
                  <a href="<?= site_url('item/tag'); ?>"style="color:  #fdfefe "><i class="fa fa-tags"style="color:  #fdfefe "></i> Kategori</a>
               </li>
               <li>
                  <a href="<?= site_url('transaksi'); ?>" style="color:  #fdfefe "><i class="fa fa-exchange"style="color:  #fdfefe "></i> Transaksi</a>
               </li>
               <li>
                  <a href="<?= site_url('user'); ?>"style="color:  #fdfefe "><i class="fa fa-users"style="color:  #fdfefe "></i> User</a>
               </li>
<!--                <li>
                  <a href="<?= site_url('transaksi/report'); ?>"style="color:  #fdfefe "><i class="fa fa-book"style="color:  #fdfefe "></i> Laporan</a>
               </li> -->

<!--                <li>
                  <a href="<?= site_url('setting'); ?>"style="color:  #fdfefe "><i class="fa fa-cogs"style="color:  #fdfefe "></i> Setting</a>
               </li> -->
               
            </ul>
         </div>

      </div>
         <!-- /sidebar menu -->

   </div>
</div>

   <!-- top navigation -->
   <div class="top_nav">
      <div class="nav_menu">
         <nav class="" role="navigation">
            <div class="nav toggle">
               <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>

            <ul class="nav navbar-nav navbar-right">
               <li class="">
                  <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                     <i class="fa fa-user"></i> Administrator
                     <span class=" fa fa-angle-down"></span>
                  </a>
                  <ul class="dropdown-menu dropdown-usermenu pull-right">
                     <li>
                        <a href="<?= site_url('administrator/edit_profil'); ?>">
                           Update Profile
                        </a>
                     </li>
                     <li>
                        <a href="<?= site_url('administrator/update_password'); ?>">
                           Ganti Password
                        </a>
                     </li>
                     <li>
                        <a href="<?= site_url('login/logout'); ?>">
                           <i class="fa fa-sign-out pull-right"></i> Log Out
                        </a>
                     </li>
                  </ul>
               </li>
            </ul>
         </nav>
      </div>
   </div>
<!-- /top navigation -->
