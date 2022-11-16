<?php
/**
 * @var View $this
 * @var int $city_id
 * @var float $latitude
 * @var float $longitude
 * @var StreetModel|null $street
 * @var DistrictModel|null $district
 * @var RegionModel|null $region
 * @var CityModel|DistrictModel|RegionModel|StreetModel $address_object
 * @var MetroStationModel $metro_station
 * @var int $doctorTotalCount
 * @var bool $nextPageFlag
 * @var string $doctorsSearchErrorBlock
 * @var SpecialtyModel|null $specialty
 * @var DoctorModel[] $doctors
 */
$offers = [];
if ($doctors) {
    $maxPrice = 0;
    $minPrice = PHP_INT_MAX;
    foreach ($doctors as $doctor) {
        if ($specialty) {
            $clinics = $doctor->getClinicsBySpecialtyId($specialty->id);
        } else {
            $clinics = $doctor->clinics;
        }
        if ($clinics) {
            $clinic = $clinics[0];
            if (isset($doctor->first_visit_price) && $doctor->first_visit_price > 0) {
                $price = $doctor->first_visit_price;
            } else {
                $price = $doctor->getFirstVisitPrice($clinic->id);
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
			"name": <?= json_encode(SeoTextViewHelper::getSpecialtyH1($specialty, $address_object)) ?>,
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