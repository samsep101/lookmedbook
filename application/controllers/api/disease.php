<?php
    class DiseaseApiController extends ApiController
    {
        public $layout = 'ajax';

        public function getCachedMethods()
        {
            return array(
                'getOneById' => array(
                    'tags' => array(
                        'disease:%disease_id%',
                        'disease'
                    ),
                ),
                'getList' => array(
                    'tags' => array(
                        'disease:list',
                        'disease'
                    )
                ),
            );
        }

        // получение полной информации о заболевании
        public function getOneById()
        {
            /**
            * @var DiseaseManager $disease_manager
            * @var DiseaseModel $disease
            * @var DiseaseAltNameManager $disease_alt_name_manager
            * @var DiseaseAltNameModel $alt_name
            */

            $disease_id = $this->request('disease_id');

            if (!$disease_id)
                ApiHeader::error(ApiRequestErrors::INCORRECT_PARAMS);

            $disease_manager = new DiseaseManager();
            $disease = $disease_manager->getOneById($disease_id);

            if (!$disease)
                ApiHeader::error(ApiRequestErrors::DISEASE_NOT_EXIST);

            $disease_alt_name_manager = ModelManagerFactory::getByName('disease_alt_name');
            $disease_alt_name = array();

            $disease_alt_names = $disease_alt_name_manager->getListByDiseaseId($disease->getId());
            if ($disease_alt_names){
                foreach ($disease_alt_names as $alt_name) {
                    $disease_alt_name[] = $alt_name->alt_name;
                }
            }

            $adult_block = $this->getSpecialtyToDiseaseByFlag($disease->getId(), 'adult');
            $male_block = $this->getSpecialtyToDiseaseByFlag($disease->getId(), 'male');
            $female_block = $this->getSpecialtyToDiseaseByFlag($disease->getId(), 'female');
            $children_block = $this->getSpecialtyToDiseaseByFlag($disease->getId(), 'children');
            $newborn_block = $this->getSpecialtyToDiseaseByFlag($disease->getId(), 'newborn');
            $pregnant_block = $this->getSpecialtyToDiseaseByFlag($disease->getId(), 'pregnant');

            $result = array(
                'disease_id' => $disease->getId(),
                'title' => $disease->title,
                'alternate_names' => $disease_alt_name,
                'extended_content' => strip_tags($this->replaceTags($disease->extended_content)),
                'content' => strip_tags($this->replaceTags($disease->content)),
                'disease_blocks' => array(
                    'adult' => $adult_block,
                    'male' => $male_block,
                    'female' => $female_block,
                    'childern' => $children_block,
                    'newborn' => $newborn_block,
                    'pregnant' => $pregnant_block
                )
            );

            ApiHeader::response($result, $this->e_tag);
        }

        // получение списка заболеваний
        public function getList()
        {
            /**
             * @var DiseaseManager $disease_manager
             * @var DiseaseModel $disease
             * @var DiseaseAltNameManager $disease_alt_name_manager
             * @var DiseaseAltNameModel $alt_name
             */

            $page = $this->request('page');
            $by_page = $this->request('by_page');

            $page = ($page) ? $page : null;
            $by_page = ($by_page) ? $by_page : null;

            $disease_manager = ModelManagerFactory::getByName('disease');
            $diseases = $disease_manager->getActiveListByPage($page, $by_page);

            $result = array();

            if (!$diseases)
                ApiHeader::error(ApiRequestErrors::DISEASES_NOT_EXIST);

            foreach ($diseases as $disease) {
                $disease_alt_name_manager = ModelManagerFactory::getByName('disease_alt_name');
                $disease_alt_name = array();

                $disease_alt_names = $disease_alt_name_manager->getListByDiseaseId($disease->getId());
                if ($disease_alt_names){
                    foreach ($disease_alt_names as $alt_name) {
                        $disease_alt_name[] = $alt_name->alt_name;
                    }
                }

                $result[] = array(
                    'disease_id' => $disease->getId(),
                    'title' => $disease->title,
                    'alternate_names' => $disease_alt_name,
                );
            }

            ApiHeader::response($result, $this->e_tag);
        }

        // получение специализации и блоков для конкретной вкладки заболевания
        private function getSpecialtyToDiseaseByFlag($disease_id, $flag)
        {
            $specialty_to_disease_manager = new SpecialtyToDiseaseManager();

            $result = new ArrayIterator();

            $specialty_to_disease = $specialty_to_disease_manager->getOneByDiseaseIdAndMainFlagAndFlag($disease_id, $flag);
            $content = $this->getDiseaseBlockByFlag($disease_id, $flag);

            if ($specialty_to_disease && $content) {
                $result = array(
                    'specialty_id' => $specialty_to_disease->specialty_id,
                    'specialty_name' => $specialty_to_disease->specialty->name,
                    'content' => $content,
                );
            }

            return $result;
        }

        // получение блоков для конкретной вкладки заболевания
        private function getDiseaseBlockByFlag($disease_id, $flag)
        {
            $disease_block_manager = new DiseaseBlockManager();

            $result = array();

            $disease_blocks = $disease_block_manager->getActiveListByDiseaseIdAndFlag($disease_id, $flag);

            if (count($disease_blocks)) {
                $diseases_blocks = array();
                foreach ($disease_blocks as $disease_block) {
                    $diseases_blocks['disease_block_type_id'] = $disease_block->disease_block_type_id;
                    $diseases_blocks['disease_block_type_name'] = $disease_block->disease_block_type->name;
                    $diseases_blocks['content'] = strip_tags($this->replaceTags($disease_block->content));

                    $result[] = $diseases_blocks;
                }
            }

            return $result;
        }

        private function replaceTags($text)
        {
            $search = array("'<[\/\!]*?[^<>]*?>'si", // Вырезает HTML-теги
                "'([\r\n])[\s]+'", // Вырезает пробельные символы
                "'&(quot|#34);'i", // Заменяет HTML-сущности
                "'&(amp|#38);'i",
                "'&(lt|#60);'i",
                "'&(gt|#62);'i",
                "'&(nbsp|#160);'i",
                "'&(iexcl|#161);'i",
                "'&(cent|#162);'i",
                "'&(pound|#163);'i",
                "'&(copy|#169);'i",
                "'&#(\d+);'e",
                "'\r'",
                "'<br \/>'",
                "'&(ndash|#8211);'i",
                "'&(mdash|#8212);'i",
                "'&(laquo|#171);'i",
                "'&(raquo|#171);'i",
                "'&(times|#215);'i"); // интерпретировать как php-код

            $replace = array("",
                "\\1",
                "\"",
                "&",
                "<",
                ">",
                " ",
                chr(161),
                chr(162),
                chr(163),
                chr(169),
                "chr(\\1)",
                " ",
                "\n",
                "–",
                "–",
                "«",
                "»",
                "×");

            return preg_replace($search, $replace, $text);
        }
    }