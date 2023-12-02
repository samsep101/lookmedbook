<?php
    class WordHelper
    {
        public static function declineToDative($word)
        {
            return WordDeclination::getInstance()->toDative($word);
        }
    }