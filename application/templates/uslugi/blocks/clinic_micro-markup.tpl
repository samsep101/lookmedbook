<?php
/**
 * @var View $this
 * @var ClinicModel[] $clinics
 */
$offers = [];
if ($clinics) {
    $maxPrice = 0;
    $minPrice = PHP_INT_MAX;
    foreach ($clinics as $clinic) {
        $price = $clinic->service_price;
        if ($price > $maxPrice) {
            $maxPrice = $price;
        }
        if ($price < $minPrice && $price) {
            $minPrice = $price;
        }
        $offers[] = [
            '@type' => 'Offer',
            'availability' => 'https://schema.org/InStock',
            'url' => ClinicPageLinkViewHelper::getLink($clinic),
            'price' => $price,
            'priceCurrency' => 'RUB',
            'name' => $clinic->full_name,
        ];
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
				"offerCount": "<?= count($clinics) ?>",
				"priceCurrency": "RUB",
				"offers": <?= json_encode($offers) ?>
      }
		}


      </script>
    <?php }
} ?>