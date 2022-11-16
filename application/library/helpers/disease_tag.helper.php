<?php

    class DiseaseTagHelper
    {
        //todo: разделение (если точки или др символы между словами)
        public function tagSeparation($tags)
        {
            $tags = trim($tags);
            $tags_array = preg_split('~\s*[,.-]\s*~', $tags);
            return $tags_array;
        }

    }