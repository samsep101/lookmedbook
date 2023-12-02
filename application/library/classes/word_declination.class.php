<?php
    require_once(ABS_ROOT . '/application/library/third_party/PhpMorphy/common.php');

    class WordDeclination
    {
        private $morphy;


        private static $instance;

        private function __construct()
        {
            $opts = array(
                // storage type, follow types supported
                // PHPMORPHY_STORAGE_FILE - use file operations(fread, fseek) for dictionary access, this is very slow...
                // PHPMORPHY_STORAGE_SHM - load dictionary in shared memory(using shmop php extension), this is preferred mode
                // PHPMORPHY_STORAGE_MEM - load dict to memory each time when phpMorphy intialized, this useful when shmop ext. not activated. Speed same as for PHPMORPHY_STORAGE_SHM type
                'storage' => PHPMORPHY_STORAGE_FILE,
                // Extend graminfo for getAllFormsWithGramInfo method call
                'with_gramtab' => FALSE,
                // Enable prediction by suffix
                'predict_by_suffix' => TRUE,
                // Enable prediction by prefix
                'predict_by_db' => TRUE
            );

            $dir =  ABS_ROOT . '/application/library/third_party/PhpMorphy/dicts';

            $dict_bundle = new phpMorphy_FilesBundle($dir, 'rus');

            $this->morphy = new phpMorphy($dict_bundle, $opts);
        }


        public static function getInstance()
        {
            if (self::$instance == NULL)
                self::$instance = new WordDeclination();

            return self::$instance;
        }

		public function getAllForms($word)
		{
			$word = mb_strtoupper($word, 'utf-8');
			$forms = $this->morphy->getAllForms($word);

			return $forms;
		}


		public function toNominative($word)
		{
			$forms = $this->getAllForms($word);
			return $this->format($forms[0]);
		}

		public function toGenitive($word)
		{
			$forms = $this->getAllForms($word);
			return $this->format($forms[1]);
		}

		public function toDative($word)
		{
			$forms = $this->getAllForms($word);
			return $this->format($forms[2]);
		}

		public function toAccusative($word)
		{
			$forms = $this->getAllForms($word);
			return $this->format($forms[2]);
		}

		public function toAblative($word)
		{
			$forms = $this->getAllForms($word);
			return $this->format($forms[3]);
		}

		public function toPrepositional($word)
		{
			$forms = $this->getAllForms($word);
			return $this->format($forms[4]);
		}

		public function toPlural($word)
		{
			$forms = $this->getAllForms($word);
			return $this->format($forms[5]);
		}

		private function format($word)
		{
			return mb_strtolower($word, 'utf-8');
		}
    }