<style>
    div.connected-carousels.iframe div.stage div.carousel ul li {
        width: 640px;
        height: 850px;
        text-align: center;
        vertical-align: middle;
    }
    .connected-carousels.iframe div.stage{

    }
</style>
<div class="connected-carousels iframe">
    <div class="stage">
        <div class="carousel carousel-stage" style="height: 800px">
            <ul>
                <?php $i = 1; ?>
                <?php while($i < 5): ?>
                    <li><img src="/media/images/license_<?php echo $i .'.jpg'; ?>" alt="" /></li>
                    <?php $i++; ?>
                <?php endwhile; ?>
            </ul>
        </div>
        <a href="javascript:void(0);" class="prev prev-stage"><span></span></a>
        <a href="javascript:void(0);" class="next next-stage"><span></span></a>
    </div>

    <div class="navigation">
        
        <div class="carousel carousel-navigation">
            <ul>
                <?php $i = 1; ?>
                <?php while($i < 5): ?>
                    <li><img src="/media/images/license_<?php echo $i .'.jpg'; ?>" alt="" style="height: 56px;" /></li>
                    <?php $i++; ?>
                <?php endwhile; ?>
            </ul>
        </div>
    </div>
</div>