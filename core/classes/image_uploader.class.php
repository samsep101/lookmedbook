<?php

class ImageUploader
{
  public static function upload($fileData, $uploadData, $alias_value = null)
  {
    if (!preg_match('/\.([^\.]+)$/', $uploadData['name'], $matches)) {
      return FALSE;
    }
    $src = $matches[1];

    $filename = ImageFilenameGeneratorHelper::getFilename($src, $alias_value);

    if (isset($fileData['upload_folder'])) {
      $filepath = ABS_ROOT . MEDIA_UPLOAD_PATH . $fileData['upload_folder'] . $filename;
      if (!is_dir(ABS_ROOT . MEDIA_UPLOAD_PATH . $fileData['upload_folder'])) {
        mkdir(ABS_ROOT . MEDIA_UPLOAD_PATH . $fileData['upload_folder'], '0755', true);
      }
    } else {
      $filepath = ABS_ROOT . MEDIA_UPLOAD_PATH . 'settings/' . $filename;
      if (!is_dir(ABS_ROOT . MEDIA_UPLOAD_PATH . 'settings/')) {
        mkdir(ABS_ROOT . MEDIA_UPLOAD_PATH . 'settings/', '0755', true);
      }
    }

	if (isset($uploadData['type']) && $uploadData['type'])
		move_uploaded_file($uploadData['tmp_name'], $filepath);
	else		
		rename($uploadData['tmp_name'], $filepath);

    $image_resizer = new SimpleImage();
    $image_resizer->load($filepath);
    $image_resizer->resizeWithRatio(UPLOAD_IMAGES_WIDTH, UPLOAD_IMAGES_HEIGHT);

    $image_info = getimagesize($filepath);

    $image_resizer->save($filepath, $image_info[2]);


    $image = new ImageModel();
    if (isset($fileData['upload_folder']))
      $image->folder = $fileData['upload_folder'];
    else
      $image->folder = 'settings/';

    $image->filename = $filename;

    ModelManagerFactory::getByName('image')->save($image);

    return $image->getId();
  }

  public static function loadImage($path, $upload_folder, $alias_value = null)
  {
    $src = self::is_image($path);

    if (!$src)
      return FALSE;

    $filename = ImageFilenameGeneratorHelper::getFilename($src, $alias_value);

    $filepath = ABS_ROOT . MEDIA_UPLOAD_PATH . $upload_folder . $filename;
    if (!is_dir(ABS_ROOT . MEDIA_UPLOAD_PATH . $upload_folder)) {
      mkdir(ABS_ROOT . MEDIA_UPLOAD_PATH . $upload_folder, '0777');
    }

    $result = @copy($path, $filepath);

    if (!$result) {
      $i = 0;
      do {
        sleep(5);
        $result = @copy($path, $filepath);
        $i++;
      } while (!$result && $i < 10);
    }

    if (!$result) {
      if (debug) {
        echo "Don't load image\r\n";
      }
      return null;
    }

    $image_resizer = new SimpleImage();
    $image_resizer->load($filepath);
    $image_resizer->resizeWithRatio(UPLOAD_IMAGES_WIDTH, UPLOAD_IMAGES_HEIGHT);
    $image_resizer->save($filepath);


    $image = new ImageModel();
    $image->folder = $upload_folder;
    $image->filename = $filename;
    $image->save($image);

    return $image->getId();
  }

  public static function cropImageAndSave($path, $upload_folder, $width, $height, $x, $y)
  {
    $src = self::is_image($path);
    if (!$src) {
      return FALSE;
    }

    $filename = time() . '-' . StringGeneratorHelper::generate(10) . $src;

    $filepath = ABS_ROOT . MEDIA_UPLOAD_PATH . $upload_folder . $filename;
    if (!is_dir(ABS_ROOT . MEDIA_UPLOAD_PATH . $upload_folder)) {
      mkdir(ABS_ROOT . MEDIA_UPLOAD_PATH . $upload_folder, '0755');
    }
    copy($path, $filepath);

    $image_resizer = new SimpleImage();
    $image_resizer->load($filepath);

    $image_resizer->cropXY($width, $height, $x, $y);
    $image_resizer->save($filepath);


    // Добавляем информацию в базу данных
    $data = array(
      'folder' => $upload_folder,
      'filename' => $filename
    );

    return ImageModel::add($data);
  }

  public static function loadImageAndCrop($path, $upload_folder, $width, $height, $x, $y)
  {
    $src = self::is_image($path);
    if (!$src)
      return FALSE;

    $filename = time() . '-' . StringGeneratorHelper::generate(10) . $src;

    $filepath = ABS_ROOT . MEDIA_UPLOAD_PATH . $upload_folder . $filename;
    if (!is_dir(ABS_ROOT . MEDIA_UPLOAD_PATH . $upload_folder)) {
      mkdir(ABS_ROOT . MEDIA_UPLOAD_PATH . $upload_folder, '0755');
    }
    copy($path, $filepath);

    $image_resizer = new SimpleImage();
    $image_resizer->load($filepath);
    $image_resizer->save(ABS_ROOT . MEDIA_UPLOAD_PATH . $upload_folder . '1024x600-resize-' . $filename);


    $image_resizer->cropXY($width, $height, $x, $y);
    $image_resizer->save($filepath);


    // Добавляем информацию в базу данных
    $data = array(
      'folder' => $upload_folder,
      'filename' => $filename
    );

    return ImageModel::add($data);
  }


  public static function loadMultiple($files, $upload_folder, $maxCount = 0)
  {
    $imageArr = array();
    $i = 1;
    foreach ($files["images"]["error"] as $key => $error) {
      if ($maxCount && $maxCount < $i) {
        break;
      }
      if ($error == UPLOAD_ERR_OK) {
        $name = $files["images"]["name"][$key];
        $imageArr[] = ImageUploader::loadImage($files["images"]["tmp_name"][$key], $upload_folder . '/');
      }
      $i++;
    }
    return $imageArr;
  }

  private static function is_image($filename)
  {
    $is = @getimagesize($filename);
    if (!$is) return FALSE;
    else
      switch ($is[2]) {
        case 1:
          return '.gif';
        case 2:
          return '.jpg';
        case 3:
          return '.png';
        default:
          return FALSE;
      }
  }
}
