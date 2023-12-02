<?php

class ImageWatermarkHelper
{
    public static function setWatermark($path)
    {
		ini_set("gd.jpeg_ignore_warning", 1);

		if(!file_exists($path))
		{
			return;
		}

		$image = null;

		$image_info = getimagesize($path);
		$image_type = $image_info[2];

		if ($image_type == IMAGETYPE_JPEG) {
			$image = imagecreatefromjpeg($path);
		} elseif ($image_type == IMAGETYPE_GIF) {
			$image = imagecreatefromgif($path);
		} elseif ($image_type == IMAGETYPE_PNG) {
			$image = imagecreatefrompng($path);
			imagealphablending($image, FALSE);
			imagesavealpha($image, TRUE);
		}

		if(!$image)
		{
			return false;
		}
        // получаем размерность изображения
        $size = getimagesize($path);

       	// создание водяного знака в формате png
		$watermark = imagecreatefrompng(ABS_ROOT . '/media/images/watermark.png');
		// получаем ширину и высоту

		$watermark_width = imagesx($watermark);
		$watermark_height = imagesy($watermark);

		$dst_width = $watermark_width;
		$dst_height = $watermark_height;

		if(($size[1]/$watermark_height) < 4)
		{
			$coef = $size[1]/$watermark_height/4;
			$dst_height *= $coef;
			$dst_width *= $coef;
		}

		imagealphablending($image, true);
        imagealphablending($watermark, true);

		// помещаем водяной знак в нижней части справа. Делаем отступ в 5px
		$dest_x = $size[0] - $dst_width;
		$dest_y = $size[1] - $dst_height;

        // создаём новое изображение
        imagecopyresampled($image, $watermark, $dest_x, $dest_y, 0, 0, $dst_width, $dst_height, $watermark_width, $watermark_height);


		if ($image_type == IMAGETYPE_JPEG) {
			imagejpeg($image, $path, 90);
		} elseif ($image_type == IMAGETYPE_GIF) {
			imagegif($image, $path);
		} elseif ($image_type == IMAGETYPE_PNG) {
			imagepng($image, $path);
		}
    }
}