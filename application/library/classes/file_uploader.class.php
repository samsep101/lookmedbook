<?php
    class FileUploader
    {
        public static function upload($fileData, $uploadData)
        {
            if (!preg_match('/\.([^\.]+)$/', $uploadData['name'], $matches)) {
                return FALSE;
            }
            $src = $matches[1];
            $filename = time() . '-' . StringGeneratorHelper::generate(10) . '.' . $src;

            if (isset($fileData['upload_folder'])) {
                $filepath = ABS_ROOT . MEDIA_UPLOAD_PATH . $fileData['upload_folder'] . $filename;
                if (!is_dir(ABS_ROOT . MEDIA_UPLOAD_PATH . $fileData['upload_folder'])) {
                    mkdir(ABS_ROOT . MEDIA_UPLOAD_PATH . $fileData['upload_folder'], '0755');
                }
            } else {
                $filepath = ABS_ROOT . MEDIA_UPLOAD_PATH . 'settings/' . $filename;
                if (!is_dir(ABS_ROOT . MEDIA_UPLOAD_PATH . 'settings/')) {
                    mkdir(ABS_ROOT . MEDIA_UPLOAD_PATH . 'settings/', '0755');
                }
            }

            move_uploaded_file($uploadData['tmp_name'], $filepath);

            return $filename;
        }
    }