<?php

namespace app\library\classes\MapData;

use RuntimeException;

class MapDataFile
{
    private $root = null;
    private $path;

    /**
     * @param string $fileName
     */
    public function __construct($fileName)
    {
        $this->root = ABS_ROOT . '/media/map/';
        $this->path = $this->root . $fileName;
    }

    /**
     * @return bool
     */
    public function exists()
    {
        return file_exists($this->path) && filesize($this->path) > 0;
    }

    /**
     * @return string
     * @throws RuntimeException
     */
    public function getContents()
    {
        $data = file_get_contents($this->path);
        if ($data === false) {
            throw new RuntimeException("Failed to read from $this->path");
        }
        return $data;
    }

    /**
     * @param string $data
     * @throws RuntimeException
     */
    public function putContents($data)
    {
        if (file_put_contents($this->path, $data) === false) {
            throw new RuntimeException("Failed to write to $this->path");
        }
    }
}
