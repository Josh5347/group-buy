<?php if($errors AND count($errors)){?>
    <div class="alert alert-dismissible alert-danger" role="alert">
        <ul>
            <?php foreach($errors as $err){ ?>
                <li> <?=$err ?> </li>
            <?php }?>
        </ul>
    </div>
<?php } ?>