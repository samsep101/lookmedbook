<?php
    class DiseaseTabNameViewHelper
    {
        public static function getNameByTabFlag($flag, $atFlag = 0)
        {
            switch($flag)
            {
                case 'male':
                    return !$atFlag ? 'Мужчины' : 'Мужчин';
                case 'female':
                    return !$atFlag ? 'Женщины' : 'Женщин';
                case 'adult':
                    return !$atFlag ? 'Взрослые' : 'Взрослых';
                case 'children':
                    return !$atFlag ? 'Дети' : 'Детей';
                case 'newborn':
                    return !$atFlag ? 'Новорожденные' : 'Новорожденных';
                case 'pregnant':
                    return !$atFlag ? 'Беременные' : 'Беременных';
                default:
                    return '';
            }
        }
    }