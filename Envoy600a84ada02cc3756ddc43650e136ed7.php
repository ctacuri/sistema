<?php $on = isset($on) ? $on : null; ?>
<?php $app_dir = isset($app_dir) ? $app_dir : null; ?>
<?php $branch = isset($branch) ? $branch : null; ?>
<?php $origin = isset($origin) ? $origin : null; ?>
<?php $__container->servers(['aws' => 'ubuntu@18.218.17.126']); ?>

 <?php require_once('vendor/autoload.php'); ?>

<?php
    $origin = 'git@github.com:ctacuri/sistema';
    $branch = isset($branch) ? $branch : 'master';
    $app_dir = '/var/www/html'

    if (!isset($on)){
        throw new Exception('La variable --on no esta definida')
    }
?>

<?php $__container->startTask('test'); ?>
    echo <?php echo $branch; ?>

<?php $__container->endTask(); ?>

<?php $__container->startTask('ls', ['on' => $on]); ?>
    cd <?php echo $app_dir; ?>

    ls -la
<?php $__container->endTask(); ?>