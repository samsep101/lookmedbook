<?php
	class PhpHeaderHelper
	{
		public static function csv($filename = 'file.csv')
		{
			header('Content-type: text/csv');
			header('Content-disposition: attachment; filename='.$filename);
		}

        public static function word2007($filename = 'file.docx', $file_size = null)
        {
            header('Content-Description: File Transfer');
			header('Content-Type: application/msword');
            header("Content-Disposition: attachment; filename='$filename");
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');

            if ($file_size)
                header('Content-Length: ' . $file_size);
        }

		public static function status404()
		{
			header("HTTP/1.0 404 Not Found");
			header('Status: 404 Not Found');
			header('HTTP/1.0 404 Not Found');
		}

        public static function status423()
        {
            header('HTTP/1.0 423 Locked');
            header('Status: 423 Locked');
            header('HTTP/1.0 423 Locked');
        }
	}