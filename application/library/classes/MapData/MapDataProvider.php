<?php

namespace app\library\classes\MapData;

class MapDataProvider
{
    private $generator;
    private $file;

    public function __construct(Generator\MapDataGenerator $generator, MapDataFile $file)
    {
        $this->generator = $generator;
        $this->file = $file;
    }

    public function getData()
    {
        if ($this->file->exists()) {
            return $this->file->getContents();
        }

        $data = $this->generator->generate();
        $this->file->putContents($data);

        return $data;
    }
}
