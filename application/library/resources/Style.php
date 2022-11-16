<?php

namespace app\library\resources;

class Style
{
    const TYPE_SIMPLE = 'simple';
    const TYPE_LAZY = 'lazy';
    /**
     * @var string
     */
    private $url;
    /**
     * @var string
     */
    private $type = self::TYPE_SIMPLE;

    public function __construct($url)
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Style
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }
}