<?php

class ModelSearchCriteria
{
    public $page;
    public $by_page;
    /**
     * Если необходимо выбрать на 1 элемент больше (чтобы проверить, есть ли данные для
     * следующей страницы)
     *
     * @var bool
     */
    public $get_extra_item;
    public $sort_by;
    /**
     * @var SearchParams;
     */
    protected $search_params;

    public function getSearchParams()
    {
        return $this->search_params;
    }

    /**
     * @return array
     */
    public function getDirectIgnoredKeys()
    {
        return [];
    }

    /**
     * Метод необходим для установки других критериев поиска (в админке)
     *
     * @param SearchParams $search_params
     */
    public function setSearchParams(SearchParams $search_params)
    {
        $this->search_params = $search_params;
    }

    public function getParamsHash($self = null)
    {
        return md5(SITE_URL . json_encode($self ?: $this));
    }
}
