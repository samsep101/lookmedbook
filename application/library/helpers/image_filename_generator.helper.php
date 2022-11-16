<?php
    class ImageFilenameGeneratorHelper
    {
        public static function getFilename($src, $alias_value)
        {
            if(!$alias_value)
            {
                $filename = time() . '-' . StringGeneratorHelper::generate(10) .'.' .$src;
            } else {
                $filename = StringTransliterationHelper::translit($alias_value);

                /**
                 * @var ImageManager $image_manager
                 */
                $image_manager = ModelManagerFactory::getByName('image');
                $filename = $temp_filename = $filename.'.'.$src;

                /*$counter = 0; while ($result = $image_manager->getOneByFilename($temp_filename)) {
                    
                    $counter++;
                    $temp_filename = $filename.'-'.$counter .'.' .$src;
                }*/

                // получаем последнюю картинку одним запросом без цикла
                $temp_filename = $image_manager->getOneLastFilename($temp_filename, false);
                if(!empty($temp_filename['filename'])){
                    $filename = $temp_filename['filename'];
                }
            }

            return $filename;
        }
    }