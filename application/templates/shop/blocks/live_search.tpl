<?php
    /**
     * @var string $pattern
     */
?>

<script type="text/javascript">
    $(document).ready(function() {
        var liveSearchController = new ProductLiveSearchController('<?php echo $pattern; ?>');
        liveSearchController.init();
    });
;</script>

<div class="search-block magazine">
    <form action="/shop/catalog/search" method="GET">
        <label>Лекарства</label>
        <input type="text" class="txt" autocomplete="off" name="products_query" placeholder="Введите название...">
        <input type="submit" class="btn-1" value="Искать">
        <ul class="drop-menu" style="width: 430px; left: 209px">
        </ul>
    </form>
</div>