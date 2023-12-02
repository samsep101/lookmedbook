<?php
/**
 * @var View $this
 * @var SpecialtyModel|null $specialty
 * @var DoctorModel[] $doctors
 */
$offers = [];
if ($doctors) {
    $maxPrice = 0;
    $minPrice = PHP_INT_MAX;
    foreach ($doctors as $doctor) {
        if ($doctor->clinic) {
            if (isset($doctor->first_visit_price) && $doctor->first_visit_price > 0) {
                $price = $doctor->first_visit_price;
            } else {
                $price = $doctor->getFirstVisitPrice($doctor->clinic->id);
            }
            if ($price > $maxPrice) {
                $maxPrice = $price;
            }
            if ($price < $minPrice && $price) {
                $minPrice = $price;
            }
            $offers[] = [
                '@type' => 'Offer',
                'availability' => 'https://schema.org/InStock',
                'url' => DoctorPageLinkViewHelper::getLink($doctor),
                'price' => $price,
                'priceCurrency' => 'RUB',
                'name' => $doctor->full_name,
            ];
        }
    }
    if ($offers) {
        ?>
      <script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"name": <?= json_encode($h1) ?>,
      "@type": "Product",
			"offers": {
				"@type": "AggregateOffer",
				"highPrice": "<?= $maxPrice ?>",
				"lowPrice": "<?= $minPrice ?>",
				"offerCount": "<?= count($doctors) ?>",
				"priceCurrency": "RUB",
				"offers": <?= json_encode($offers) ?>
      }
		}

      </script>
    <?php }
} ?>