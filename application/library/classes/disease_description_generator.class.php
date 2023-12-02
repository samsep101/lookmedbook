<?php
    class DiseaseDescriptionGenerator
    {
        public function generate(DiseaseModel $disease)
        {
            $description = 'Лечение '.trim($disease->genitive_name).': информация о симптомах, инкубационном периоде, причинах и последствиях. Своевременно записывайтесь к врачам на диагностику, чтобы избежать осложнений и получить информацию о профилактике '.trim($disease->genitive_name);

            return $description;
        }
    }