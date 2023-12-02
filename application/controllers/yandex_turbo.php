<?php

use sokolnikov911\YandexTurboPages\Channel;
use sokolnikov911\YandexTurboPages\Feed;
use sokolnikov911\YandexTurboPages\helpers\Content;
use sokolnikov911\YandexTurboPages\Item;

class YandexTurboController extends Controller
{
    const MAX_CHANNELS = 4;

    /** @var SpecialtyManager */
    private $specialtyManager;

    public function __construct()
    {
        parent::__construct();
        $this->specialtyManager = ModelManagerFactory::getByName('specialty');
    }

    public function beforeRender()
    {
        exit(0);
    }

    public function index()
    {
        /** @var DiseaseManager $diseaseManager */
        $diseaseManager = ModelManagerFactory::getByName('disease');
        /** @var DiseaseBlockManager $diseaseBlockManager */
        $diseaseBlockManager = ModelManagerFactory::getByName('disease_block');
        /** @var DiseaseModel[] $diseaseList */
        $diseaseList = $diseaseManager->getList();

        $feeds = [];
        $channels = [];
        for ($i = 0; $i < self::MAX_CHANNELS; $i++) {
            $feeds[$i] = new Feed();
            $channels[$i] = (new Channel())
                ->title("Lookmedbook disease channel {$i}")
                ->link('https://lookmedbook.ru')
                ->description("Lookmedbook disease channel {$i}")
                ->language('ru')
                ->appendTo($feeds[$i]);
        }

        // добавляем первую турбо-страницу с активированным турбо-режимом, необходимым описанием, и прикрепляем ее к каналу
        foreach ($diseaseList as $disease) {
            // выбор вкладки
            $flags = $diseaseBlockManager->getActiveDiseaseTabsFlagsByDiseaseId($disease->getId());
            $flag = null;
            foreach ($flags as $key => $v) {
                if ($v) {
                    $flag = $key;
                    break;
                }
            }
            if ($flag) {
                $specialty = $this->getSpecialty($disease, $flag);

                $content = $this->prepareContent($disease->content);
                $blocks = $diseaseBlockManager->getActiveListByDiseaseIdAndFlag($disease->id, $flag);
                $accordionArray = array_map([$this, 'toAccordion'], $blocks);
                if ($disease->sources) {
                    $accordionArray[] = ['title' => 'Авторы', 'text' => $this->prepareContent($disease->sources)];
                }
                if ($disease->extended_content) {
                    $accordionArray[] = [
                        'title' => 'Расширенное описание',
                        'text' => $this->prepareContent($disease->extended_content),
                    ];
                }
                $content .= Content::accordion($accordionArray);
                if ($specialty) {
                    $this->view->disease = $disease;
                    $this->view->specialty = $specialty;
                    $content .= $this->renderBlockInString('yandex_turbo/disease-specialty');
                }
                $item = new Item($disease->is_active);
                $item
                    ->title($disease->title)
                    ->link(SITE_URL."/disease/{$disease->alias}")
                    ->turboContent($content)
                    ->pubDate(null)
                    ->appendTo($channels[$disease->id % self::MAX_CHANNELS]);
            }
        }

        for ($i = 0; $i < self::MAX_CHANNELS; $i++) {
            file_put_contents($this->getFileName("feed{$i}.xml"), $feeds[$i]->render());
        }
    }

    /**
     * @param DiseaseModel $disease
     * @param              $card
     *
     * @return SpecialtyModel
     */
    protected function getSpecialty(DiseaseModel $disease, $card)
    {
        $specialties = $this->specialtyManager->getMainListByDiseaseId($disease->getId());

        foreach ($specialties AS $dsKey => $dsValue) {
            $fieldName = "is_$card";
            // 4466 Быстрохак для отсечения специальностей, не относящихся к текущей вкладке
            if (!$dsValue->$fieldName) {
                unset($specialties[$dsKey]);
                continue;
            }
            $specialties[$dsKey]->specialtyUrl = SITE_URL.'/doctor/'.$dsValue->alias;
        }

        return reset($specialties);
    }

    protected function prepareContent($content)
    {
        $content = preg_replace('/(<br\s*\/>)/', '', $content);

        return html_entity_decode($content, ENT_COMPAT, 'UTF-8');
    }

    protected function getFileName($file)
    {
        return __DIR__.'/../../public/yandex-turbo/'.$file;
    }

    protected function toAccordion(DiseaseBlockModel $block)
    {
        return ['title' => $block->disease_block_type->name, 'text' => $this->prepareContent($block->content)];
    }
}