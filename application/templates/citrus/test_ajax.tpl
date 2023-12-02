    <h3><?php echo $href; ?></h3>
    <h3><?php echo $param1; ?></h3>
    <h3><?php echo $param2->name; ?></h3>
    
    <?php foreach($arr2 as $i => $v){ ?>
        <p><?php echo $v->name.' : '.$v->text; ?></p>
    <?php } ?>   