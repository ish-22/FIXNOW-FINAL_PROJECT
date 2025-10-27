<?php 
//authorisation-access control
if(!isset($_SESSION['user'])){ 
  header('Location:'.SITEURL.'admin/admin-login.php?status=error&action=no-login-message');

}