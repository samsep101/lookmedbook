<?php
    class SimpleImage
    {

        var $image;
        var $image_type;

        function load($filename)
        {
            $image_info = getimagesize($filename);
            $this->image_type = $image_info[2];
            if ($this->image_type == IMAGETYPE_JPEG) {
                $this->image = imagecreatefromjpeg($filename);
            } elseif ($this->image_type == IMAGETYPE_GIF) {
                $this->image = imagecreatefromgif($filename);
            } elseif ($this->image_type == IMAGETYPE_PNG) {
                $this->image = imagecreatefrompng($filename);
	            imagealphablending($this->image, FALSE);
	            imagesavealpha($this->image, TRUE);
            }
        }

        function save($filename, $image_type = IMAGETYPE_JPEG, $compression = 85)
        {
            if ($image_type == IMAGETYPE_JPEG) {
                imagejpeg($this->image, $filename, $compression);
            } elseif ($image_type == IMAGETYPE_GIF) {
                imagegif($this->image, $filename);
            } elseif ($image_type == IMAGETYPE_PNG) {
                imagepng($this->image, $filename);
            }
        }

        function output($image_type = IMAGETYPE_JPEG)
        {
            if ($image_type == IMAGETYPE_JPEG) {
                imagejpeg($this->image);
            } elseif ($image_type == IMAGETYPE_GIF) {
                imagegif($this->image);
            } elseif ($image_type == IMAGETYPE_PNG) {
                imagepng($this->image);
            }
        }

        function getWidth()
        {
            return imagesx($this->image);
        }

        function getHeight()
        {
            return imagesy($this->image);
        }

        function resizeToHeight($height)
        {
            $ratio = $height / $this->getHeight();
            $width = $this->getWidth() * $ratio;
            $this->resize($width, $height);
        }

        function resizeToWidth($width)
        {
            $ratio = $width / $this->getWidth();
            $height = $this->getheight() * $ratio;
            $this->resize($width, $height);
        }

        function scale($scale)
        {
            $width = $this->getWidth() * $scale / 100;
            $height = $this->getheight() * $scale / 100;
            $this->resize($width, $height);
        }

        function resize($width, $height)
        {
            $new_image = imagecreatetruecolor($width, $height);
            //$white = imagecolorallocate($new_image, 255, 255, 255);
            //imagefill($new_image, 0, 0, $white);

	        imagealphablending($new_image, FALSE);
	        imagesavealpha($new_image, TRUE);

            imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
            $this->image = $new_image;
        }

        function crop($width, $height)
        {
            if (empty($this->image)) {
                return null;
            }
            $new_image = imagecreatetruecolor($width, $height);
	        imagealphablending($new_image, FALSE);
	        imagesavealpha($new_image, TRUE);
            $width_ratio = $this->getWidth() / $width;
            $height_ratio = $this->getHeight() / $height;

            $ratio = min($width_ratio, $height_ratio);

            if ($width_ratio < $height_ratio) {
                $x_offset = 0;
                $y_offset = (int)($this->getHeight() - $height * $ratio) / 2;
            } elseif ($height_ratio <= $width_ratio) {
                $y_offset = 0;
                $x_offset = (int)($this->getWidth() - $width * $ratio) / 2;
            }

            $src_w = $this->getWidth() - $x_offset;
            $src_h = $this->getHeight() - $y_offset;

            imagecopyresampled($new_image, $this->image, 0, 0, 0 + $x_offset, 0, $width, $height, $width * $ratio, $height * $ratio);
            $this->image = $new_image;
        }


        function cropXY($width, $height, $x, $y)
        {
            $new_image = imagecreatetruecolor($width, $height);
            imagecopyresampled($new_image, $this->image, 0, 0, $x, $y, $width, $height, $width, $height);
            $this->image = $new_image;
        }

        function resizeWithRatio($width, $height)
        {
            $width_ratio = $this->getWidth() / $width;
            $height_ratio = $this->getHeight() / $height;

            $ratio = max($width_ratio, $height_ratio);

            if ($ratio > 1) {
                $height = (int)($this->getHeight() / $ratio);
                $width = (int)($this->getWidth() / $ratio);
                $this->resize($width, $height);
            } else {

            }

        }
    }