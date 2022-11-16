<?php
    class GeoPoint
    {
        private $latitude;
        private $longitude;

        public function __construct($latitude, $longitude)
        {
            $this->latitude = $latitude;
            $this->longitude = $longitude;
        }

        public function getLatitude()
        {
            return $this->latitude;
        }

        public function getLongitude()
        {
            return $this->longitude;
        }

        public static function getDistance($lat1, $lon1, $lat2, $lon2) {
            $lat1 *= M_PI / 180;
            $lat2 *= M_PI / 180;
            $lon1 *= M_PI / 180;
            $lon2 *= M_PI / 180;

            $d_lon = $lon1 - $lon2;

            $slat1 = sin($lat1);
            $slat2 = sin($lat2);
            $clat1 = cos($lat1);
            $clat2 = cos($lat2);
            $sdelt = sin($d_lon);
            $cdelt = cos($d_lon);

            $y = pow($clat2 * $sdelt, 2) + pow($clat1 * $slat2 - $slat1 * $clat2 * $cdelt, 2);
            $x = $slat1 * $slat2 + $clat1 * $clat2 * $cdelt;

            return (int)(atan2(sqrt($y), $x) * 6372795);
        }

        public static function getMetroStationIdAndMinDistanceToMetroStationByStartCoordinates($metro_stations, $start_latitude, $start_longitude,$min_radius_for_search,$max_radius_for_search,$step)
        {
            $distances = array();
            $i = 0;
            for ($m = $min_radius_for_search; $m <= $max_radius_for_search; $m+=$step) {
                foreach ($metro_stations as $metro_station) {
                    $distance = GeoPoint::getDistance($start_latitude, $start_longitude, $metro_station->latitude, $metro_station->longitude);

                    if ($distance < $m) {
                        $distances[$i]['distance'] = $distance;
                        $distances[$i]['metro_station_id'] = $metro_station->getId();
                    }
                    $i++;
                }

                if (count($distances))
                    break;
            }

            $out_distance = array();
            foreach ($distances as $key => $value) {
                $out_distance[] = $value;
            }
            $distances = new ArrayObject($out_distance);
            $distances->asort();

            foreach ($distances as $distance){
                $out_distance = $distance;
                break;
            }

            return $out_distance;
        }
    }