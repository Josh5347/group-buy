<?php require_once 'common/phpHeader.php'; ?>

<?php require_once 'Classes/Functions.php';?>
<?php use Classes\Functions; ?>

<?php 
  unset($_SESSION['username']);
  header("Location:". Functions::redirect("/index.php") );
?>