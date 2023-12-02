<?php
	class FileHelper
	{
		public static function getFoldersList($folder)
		{
			$folder = trim($folder, '/');


			$f = opendir($folder);

			$folders = array();
			while($file = readdir($f))
			{
				if (($file == '.') || ($file == '..'))
					continue;

				if (is_dir($folder.'/'.$file))
				{
					$folders[] = $file;
				}
			}

			return $folders;
		}

        public static function createFolder($folder_name)
        {
            mkdir($folder_name);
            chmod($folder_name, 0777);
        }

		public static function copyFolder($d1, $d2, $upd = true, $force = true)
		{
			if(is_dir($d1) && (strpos($d1, '.svn') === FALSE))
			{
				$d2 = self::mkdir_safe($d2, $force);
				if(!$d2)
				{
					return false;
				}
				$d = dir($d1);
				while(false !== ($entry = $d->read()))
				{
					if($entry != '.' && $entry != '..' && (strpos($entry, '.svn') === FALSE))
					{
						self::copy_folder("$d1/$entry", "$d2/$entry", $upd, $force);
					}
				}
				$d->close();

				return true;
			}
			else
			{
				return self::copy_safe($d1, $d2, $upd);
			}
		}


		private static function copy_safe($f1, $f2, $upd)
		{
			$time1 = filemtime($f1);
			if(file_exists($f2))
			{
				$time2 = filemtime($f2);
				if($time2 >= $time1 && $upd)
				{
					return false;
				}
			}
			$ok = copy($f1, $f2);
			if($ok)
			{
				touch($f2, $time1);
			}
			return $ok;
		}


		private static function mkdir_safe($dir, $force)
		{
			if(file_exists($dir))
			{
				if(is_dir($dir))
				{
					return $dir;
				}
				else if(!$force)
				{
					return false;
				}
				unlink($dir);
			}
			return (mkdir($dir, 0777, true)) ? $dir : false;
		}


		private static function copy_folder($d1, $d2, $upd = true, $force = true)
		{
			if(is_dir($d1))
			{
				$d2 = self::mkdir_safe($d2, $force);
				if(!$d2)
				{
					return false;
				}
				$d = dir($d1);
				while(false !== ($entry = $d->read()))
				{
					if($entry != '.' && $entry != '..' && (strpos($entry, '.svn') === FALSE))
					{
						self::copy_folder("$d1/$entry", "$d2/$entry", $upd, $force);
					}
				}
				$d->close();

				return true;
			}
			else
			{
				return self::copy_safe($d1, $d2, $upd);
			}
		}

        public static function deleteFolder( $path )
        {
            if ( $content_del_cat = glob( $path.'/*') )
            {
                foreach ( $content_del_cat as $object )
                {
                    if ( is_dir( $object ) ) {
                        self::deleteFolder( $object );
                    }
                    else {
                        @chmod( $object, 0777 );
                        unlink( $object );
                    }
                }
            }
            @chmod( $object, 0777 );
            @rmdir( $path );
        }

        public static function deleteFile($file_path)
        {
            unlink($file_path);
        }
	}