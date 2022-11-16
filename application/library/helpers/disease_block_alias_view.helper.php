<?php
    class DiseaseBlockAliasViewHelper
    {
        public static function getAlias($disease_block_type_id)
        {
            switch($disease_block_type_id)
            {
                case 1:
                    return 'symptoms';
                case 2:
                    return 'incubation';
                case 3:
                    return 'forms';
                case 4:
                    return 'reasons';
                case 5:
                    return 'diagnosis';
                case 6:
                    return 'cure';
                case 7:
                    return 'complications';
                case 8:
                    return 'prevention';
                case 9:
                    return 'extra';
                default:
                    return '';
            }
        }
    }