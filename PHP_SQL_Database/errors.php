<?php
  //error_reporting(E_ERROR | E_PARSE);
  if(count($errors)>0) :
?>

    <div>
      <?php foreach($errors as $error) : ?>

        <p><?php echo $error ?></p>

      <?php endforeach ?>
    </div
  <?php endif ?>
