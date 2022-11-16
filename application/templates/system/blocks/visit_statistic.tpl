<?php
    /**
     *	@var int $this_year;
     */
?>

<div class="visit-date-pick">
    <span>С</span>
    <select class="visit-month-from">
        <option value="1">Января</option>
        <option value="2">Февраля</option>
        <option value="3">Марта</option>
        <option value="4">Апреля</option>
        <option value="5">Мая</option>
        <option value="6">Июня</option>
        <option value="7">Июля</option>
        <option value="8">Августа</option>
        <option value="9">Сентября</option>
        <option value="10">Октября</option>
        <option value="11">Ноября</option>
        <option value="12">Декабря</option>
    </select>
    <select class="visit-year-from">
        <?php for ($i = 2013; $i<=$this_year; $i++):?>
            <option value="<?php echo $i;?>"><?php echo $i;?></option>
        <?php endfor;?>
    </select>
</div>
<div class="visit-date-pick">
    <span>По</span>
    <select class="visit-month-to">
        <option value="1">Январь</option>
        <option value="2">Февраль</option>
        <option value="3">Март</option>
        <option value="4">Апрель</option>
        <option value="5">Май</option>
        <option value="6">Июнь</option>
        <option value="7">Июль</option>
        <option value="8">Август</option>
        <option value="9">Сентябрь</option>
        <option value="10">Октябрь</option>
        <option value="11">Ноябрь</option>
        <option value="12">Декабрь</option>
    </select>
    <select class="visit-year-to">
        <?php for ($i = 2013; $i<=$this_year; $i++):?>
            <option value="<?php echo $i;?>"><?php echo $i;?></option>
        <?php endfor;?>
    </select>
</div>
<a style="text-decoration:none;" href="/system/generateVisitStatisticXls?month_from=" class="visit-statistic-xls"><input type="button" value="Скачать"></a>