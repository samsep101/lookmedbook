<?php
	class ZipArchiveHelper
	{
		public static function ZipFull($src_dir, $archive_path) {
			$zip = new ZipArchive();
			if ($zip->open($archive_path, ZIPARCHIVE::CREATE) !== true) {
				return false;
			}
			$zip = self::ZipDirectory($src_dir,$zip);
			$zip->close();
			return true;
		}

		private static  function ZipDirectory($src_dir, ZipArchive $zip, $dir_in_archive='') {
			$dirHandle = opendir($src_dir);
			while (false !== ($file = readdir($dirHandle))) {
				if (($file != '.')&&($file != '..')) {
					if (!is_dir($src_dir.$file)) {
						$zip->addFile($src_dir.$file, $dir_in_archive.$file);
					} else {
						$zip->addEmptyDir($dir_in_archive.$file);
						$zip = self::ZipDirectory($src_dir.$file.DIRECTORY_SEPARATOR,$zip,$dir_in_archive.$file.DIRECTORY_SEPARATOR);
					}
				}
			}
			return $zip;
		}

	}