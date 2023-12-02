<?php
	class ClinicFinanceReportGenerator
	{
		/**
		 * @var ClinicModel
		 */
		private $clinic;

		/**
		 * @var date
		 */
		private $date_from;

		/**
		 * @var date
		 */
		private $date_to;


		private $dom;

		/**
		 * @var DOMElement
		 */
		private $doc;

		private $template_path = 'media/document_templates/clinic_finance_report/';

		/**
		 * @var string
		 */
		private $target_folder;

		private $file_name;

		public function __construct()
        {
            $this->template_path = ABS_ROOT . '/media/document_templates/clinic_finance_report/';
        }

        /**
		 * @param \ClinicModel $clinic
		 */
		public function setClinic($clinic)
		{
			$this->clinic = $clinic;
		}

		/**
		 * @param \date $date_from
		 */
		public function setDateFrom($date_from)
		{
			$this->date_from = $date_from;
		}

		/**
		 * @param \date $date_to
		 */
		public function setDateTo($date_to)
		{
			$this->date_to = $date_to;
		}

		public function setTemplatePath($template_path)
		{
			$this->template_path = $template_path;
		}

		public function generate($file_name)
		{
			$this->file_name = $file_name;
			$this->loadTemplate();

			$this->copyTemplate();
			$this->fillTokens();
			$this->fillTable();
			$this->saveDocxFile();

            $this->deleteTargetFolder();
			// сделать удадение папки, из которой мы делаем архив.
			// название папки должно быть каждый раз разным (применить рандом)
		}

		public function fillTable()
		{
			$visit_manager = new VisitManager();

			/**
			 * @var VisitModel[] $visits
			 */
			// тут получем нужные визиты по данной клинике
			$visits = $visit_manager->getListByClinicIdAndVisitStatusIdAndDate($this->clinic->getId(), VisitModel::VISITED, $this->date_from, $this->date_to);

			$file = file_get_contents($this->getDocumentXMLPath());

            $file = str_replace('ttsummtt', 700*count($visits), $file);

			if (preg_match_all('/<w:tr.+?<\/w:tr>/ims', $file, $matches))
			{
				$template = $matches[0][2];
				$file = str_replace($template, '%%template%%', $file);
			} else {
				return false;
			}

			$html = '';
			foreach($visits as $visit)
			{
				$new_row = $template;

				$specialty = $visit->specialty ? $visit->specialty->name  : '';

				$new_row = str_replace('ttvntt', $visit->getId(), $new_row);
				$new_row = str_replace('ttvdtt', DateHelper::format($visit->dt, 'd.m.Y'), $new_row);
				$new_row = str_replace('ttvttt', DateHelper::format($visit->dt, 'H:i'), $new_row);
				$new_row = str_replace('ttspecialtytt', StringHelper::startProposalWord($specialty), $new_row);
				$new_row = str_replace('ttpricett', 700, $new_row);
				$new_row = str_replace('ttdoctortt', $visit->doctor_name, $new_row);
				$new_row = str_replace('ttaccounttt', $visit->full_name, $new_row);

				$html .= $new_row;
			}

			$file = str_replace('%%template%%', $html, $file);

			file_put_contents($this->getDocumentXMLPath(), $file);
		}

		private function get_inner_html( $node ) {
			$innerHTML= '';
			$children = $node->childNodes;
			foreach ($children as $child) {
				$innerHTML .= $child->ownerDocument->saveXML( $child );
			}

			return $innerHTML;
		}

		private function copyTemplate()
		{
			$this->target_folder =  ABS_ROOT . '/media/reports/clinic_finance_reports/' . 'clinic'.$this->clinic->getId().'n'.rand(1,10000) . '/';
			FileHelper::copyFolder($this->template_path, $this->target_folder);
		}

		private function fillTokens()
		{
			$document_text = file_get_contents($this->getDocumentXMLPath());

			$document_text = str_replace('ttcntt', $this->clinic->contract_number, $document_text);
			$document_text = str_replace('ttcdtt', DateHelper::format($this->clinic->date_contract, 'd.m.Y'), $document_text);
			$document_text = str_replace('ttclientnamett', $this->clinic->legal_entity, $document_text);
			$document_text = str_replace('ttdatefromtt', DateHelper::format($this->date_from, 'd.m.Y'), $document_text);
			$document_text = str_replace('ttdateto', DateHelper::format($this->date_to, 'd.m.Y'), $document_text);
			$document_text = str_replace('ttdirectortt',$this->clinic->director_fio, $document_text);
			$document_text = str_replace('ttlegalentitytt', $this->clinic->legal_entity, $document_text);

			file_put_contents($this->getDocumentXMLPath(), $document_text);
		}

		private function getDocumentXMLPath()
		{
			return $this->target_folder.'word/document.xml';
		}

		private function saveDocxFile()
		{
			ZipArchiveHelper::ZipFull($this->target_folder, $this->file_name);
			@chmod($this->file_name, 0666);
		}

		public function loadTemplate()
		{
			$dom = new DOMDocument();
			$dom->load($this->template_path.'word/document.xml');

			$this->dom = $dom;
			$this->doc = $dom->documentElement;
		}

        private function deleteTargetFolder()
        {
            FileHelper::deleteFolder($this->target_folder);
        }

	}