<?php require_once 'common/phpHeader.php'; ?>

<?php require_once 'Classes/Functions.php';?>
<?php use Classes\Functions; ?>

<?php 
  unset($_SESSION['username']);
  echo '<script> location.replace("/index.php"); </script>';
?>