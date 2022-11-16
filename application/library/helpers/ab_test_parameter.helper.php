<?php
    class AbTestParameterHelper
    {
        public static function getPageCodeBySpecialtyAlias($specialty_alias)
        {
            switch ($specialty_alias) {
                case 'oftalmolog':
                    return 'LandOcul';
                case 'allergolog-immunolog':
                    return 'LandAlergol';
                case 'nevrolog' :
                    return 'LandNevrol';
                case 'otolaringolog' :
                    return 'LandOtolor';
            }
        }
    }