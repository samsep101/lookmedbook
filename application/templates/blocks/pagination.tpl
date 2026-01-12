<?php

    $pageCount = ceil($total / 10);
    //я знаю что это лютый костыль но иначе придется перерывать тонны непонятного кода и может что-то отвалиться
    $pageCount = $pageCount < 1000 ? $pageCount : 999;




    $activePage = isset($_GET['page']) ? $_GET['page'] : 1;
    $request = $_REQUEST;
    unset($request['page']);

    $request = http_build_query($request);
    $request = $request != '' ? $request.'&' : '';


    $startUrl = 'https://lookmedbook.ru'.$_SERVER['REDIRECT_URL'].'?'.$request;


    $offsetLeft = 2;
    $offsetRight = 2;
    $pages = [];


    $pages[] = 1;
    $pages[] = $pageCount;



    if ($activePage == 1){
        $offsetLeft = 0;
        $offsetRight = 4;
    }else if ($activePage == $pageCount) {
        $offsetLeft = 4;
        $offsetRight = 0;
    }

    if ($activePage  == 2){
        $offsetLeft--;
        $offsetRight++;
    }else if($activePage + 1 == $pageCount){
        $offsetLeft++;
        $offsetRight--;
    }


    for ($i = ($activePage - $offsetLeft); $i <= ($activePage + $offsetRight) && $i <= $pageCount && $i > 0; $i++) {
        $pages[] = $i;
    }


    $delimitor = 10;



    if ($pageCount < 100){
        for ($i = 1; $i <= 10; $i++){
            if ($i * $delimitor >= $pageCount)
                break;
            $pages[] = $i * $delimitor;
        }
    }else {
        $removeList = [];
        if ($activePage != 2)
            $removeList[] = $activePage -1;
        if ($activePage +1 != $pageCount)
            $removeList[] = $activePage +1;

        $pages = array_diff($pages, $removeList );
    }

    $pages = array_unique($pages, SORT_NUMERIC);
    sort($pages, SORT_NUMERIC);
    //построение пагинации
    $pageLater = 0;
    ?>
    <ul class="page_pagination">
        <?php if ($activePage != 1 ) { ?>
        <li class="page_pagination--item">
            <a href="<?=  $page == 2? $startUrl :$startUrl.'page='.($activePage - 1)?>"> < </a>
        </li>
        <?php } ?>
        <?php foreach($pages as $page) { ?>
            <li class="page_pagination--item">
                <?php
                if (++$pageLater != $page) {
                    $pageLater = $page;
                    echo "<span>...</span></li><li class=\"page_pagination--item\"> ";
                }
                if ($page == $activePage){
                    echo "<span> ${page}</span>";
                }else {  ?>
                    <a href="<?= $page == 1? $startUrl : $startUrl.'page='.$page?>" ><?=$page?></a>
                <?php } ?>
            </li> <?php
            } ?>

        <?php if ($activePage != $pageCount) { ?>
        <li class="page_pagination--item">
            <a href="<?=$startUrl.'page='.($activePage + 1)?>"> > </a>
        </li>
        <?php } ?>
    </ul>
<style>
    .page_pagination {
        list-style: none;
        display: flex;
        flex-direction: row;
        margin: auto;
        width: 100%;
        justify-content: center;
        column-gap: 15px;
    }

    .page_pagination--item {
        min-width: 10px;
        font-size: 16px;
        height: 20px;
        line-height: 16px;
    }
    .page_pagination--item a {
        text-decoration: none;
        /*background-color: red;*/
    }
    .page_pagination--item a:hover {
        text-decoration: underline;
    }
</style>