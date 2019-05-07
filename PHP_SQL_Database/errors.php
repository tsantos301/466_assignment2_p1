<?php include('server.php') ?>
<?php
  //error_reporting(E_ERROR | E_PARSE);
  if(count($errors)>0) :
?>

    <div>
      <?php foreach($errors as $error) : ?>

        <h3 class = "error"><?php echo $error ?></h3>

      <?php endforeach ?>
    </div
  <?php endif ?>
