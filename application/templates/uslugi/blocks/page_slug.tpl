<?php
$current_tree = $this->current_tree;
empty($current_tree) AND $current_tree = $this->tree;

$class_pricepage = '';
$collapsed = false;

$subslugs = current($current_tree)['subslugs'];
if (count($subslugs) > 10) {
    $class_pricepage = 'collapsed';
    $collapsed = true;
}

$renderPriceList = function ($subslugs, $level = 0) use (&$renderPriceList) {
    foreach ($subslugs as $subone) {
        if ($subone['total'] > 0) {
            $subone['title'] = 'Услугу оказывают в ' . $subone['total'] . StringHelpers\plural($subone['total'], [' клинике', ' клиниках', ' клиниках']);
            ?>
            <li class="ff-regular item-level-<?= $level ?>"><a class="hint--top-right" aria-label="<?= $subone['title'] ?>" href="/uslugi/<?= $subone['full_slug'] ?>"><?= $subone['name'] ?></a><span class="ff-medium"> от <?= isset($subone['min_price']) ? $subone['min_price'] : '' ?> руб.</span></li>
            <?php
        } else {
            ?>
            <li class="ff-regular item-level-<?= $level ?>"><a href="/uslugi/<?= $subone['full_slug'] ?>"><?= $subone['name'] ?></a></li>
            <?php
        }
        if (!empty($subone['subslugs'])) {
            $renderPriceList($subone['subslugs'], $level + 1);
        }
    }
};

$serviceId = $this->service_category['id'];
$description = null;
$fullDescription = null;
if ($this->service) {
    if ($this->service['description']) {
        $description = $this->service['description'];
        $fullDescription = $this->service['full_description'];
    }
    $serviceId = $this->service['id'];
} else if ($this->service_category['description'] != "") {
    $description = $this->service_category['description'];
    $fullDescription = $this->service_category['full_description'];
}
$blockParams = [
    'title' => $this->h1,
    'description' => $description,
    'fullDescription' => $fullDescription,
];
switch (true) {
    case !empty($description):
        $this->block(
            'uslugi/blocks/title-and-description/disease-like',
            [
                'title' => $this->h1,
                'description' => $description,
                'fullDescription' => $fullDescription,
            ]
        );
        break;
    default:
        $this->block(
            'uslugi/blocks/title-and-description/default',
            [
                'title' => $this->h1,
                'description' => $description,
                'fullDescription' => $fullDescription,
            ]
        );
        break;
}
?>

<div class="container">
    <?php
    if ($this->clinics) {
        $this->block('uslugi/blocks/clinic_map');
        $this->block('uslugi/blocks/clinic_micro-markup');
    } else {
        if ($this->doctors) {
            $this->block('uslugi/blocks/doctor_map');
            $this->block('uslugi/blocks/doctor_micro-markup');
        }
    }
    ?>
</div>

<div class="block-before">
    <?php $this->tryBlock($this->block_before) ?>
</div>

<?php if (!empty($subslugs)): ?>
<div class="hidden-map-fullscreen">
    <?php if ($this->btnslug && $this->btnback) { ?>
        <a href="<?= $this->btnslug ?>" class="btn-default"><i class="glyphicon glyphicon-menu-left"></i> <?= $this->btnback ?></a>
    <?php } else { ?>
        <a href="/uslugi" class="btn-default"><i class="glyphicon glyphicon-menu-left"></i> Список услуг</a>
    <?php } ?>


    <div class="row">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div id="pricepage" class="simple-page <?= $class_pricepage ?>">
                <ul class="pricelist">
                    <?= $renderPriceList($subslugs) ?>
                </ul>
            </div>
        </div>
        <div class="clearfix"></div>

        <?php if ($collapsed) : ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <a class="view-more-services" href="#">Показать все услуги</a>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php endif; ?>

<?php
if ($this->clinics) {
    $this->block('uslugi/blocks/clinic_list');
} else if ($this->doctors) {
    $this->block('uslugi/blocks/doctor_list');
}
?>


<?php if ($this->pagination->total > $this->pagination->perpage) : ?>
<div class="hidden-map-fullscreen">
    <?= $this->pagination->html($this->base_url); ?>
</div>
<?php endif; ?>

<script>
  $(document).ready(function(){
    var controller = new ServiceCategoryPageController();
    controller.init();
  });
</script>