<?php

require_once(ABS_ROOT . '/application/library/third_party/PhpExcel/PHPExcel.php');

class ExcelReadIterator implements  Iterator
{
	protected $filepath;
	protected $sheet_name;

	protected $start_line = 1;

	protected $current_line = 1;

	protected $chunk_size = 200;

	protected $free_counter = 0;

	protected $obj_reader;
	protected $obj_phpExcel;


	protected $obj_sheet;

	protected $read_filter;

	protected $column_count;

	public function __construct($filepath, $sheet_name = '')
	{
		$this->filepath = $filepath;
		$this->sheet_name = $sheet_name;

		new PhpExcel();

		$this->obj_reader = PHPExcel_IOFactory::createReaderForFile($this->filepath);
		$this->obj_reader->setReadDataOnly(TRUE);

		$this->read_filter = new ExcelReadFilter();
		$this->obj_reader->setReadFilter($this->read_filter);
		$this->readDiapazon();
	}

	private function readDiapazon()
	{
		$this->read_filter->setRows($this->start_line, $this->chunk_size);
		$this->obj_phpExcel = $this->obj_reader->load($this->filepath);

		if ($this->sheet_name)
			$this->obj_sheet = $this->obj_phpExcel->getSheetByName($this->sheet_name);
		else
			$this->obj_sheet = $this->obj_phpExcel->getActiveSheet();

		$this->column_count = $this->getColumnCountByHighestColumnLetters($this->obj_sheet->getHighestColumn());
	}

	public function rewind()
	{
		$this->current_line = 1;
	}

	public function current()
	{
		if (($this->current_line < $this->start_line) || ($this->current_line > ($this->start_line + $this->chunk_size)))
		{
			$this->obj_phpExcel->disconnectWorksheets();
			unset($this->obj_phpExcel);
			$this->start_line = (int)($this->current_line/$this->chunk_size) * $this->chunk_size;
			$this->readDiapazon();
		}

		$cell = $this->obj_sheet->getCellByColumnAndRow(0, $this->current_line);

		if (!$cell->getValue()
			&& !$this->obj_sheet->getCellByColumnAndRow(1, $this->current_line)->getValue()
			&& !$this->obj_sheet->getCellByColumnAndRow(3, $this->current_line)->getValue()
			&& !$this->obj_sheet->getCellByColumnAndRow(4, $this->current_line)->getValue()
			&& !$this->obj_sheet->getCellByColumnAndRow(2, $this->current_line)->getValue()
		) {
			$this->free_counter++;
		} else {
			$this->free_counter = 1;
		}

		$row = array();

		for ($t = 0; $t < $this->column_count; $t++) {
			$row[] = trim(htmlspecialchars($this->obj_sheet->getCellByColumnAndRow($t, $this->current_line)->getValue()));
		}

		return $row;
	}

	public function key()
	{
		return $this->current_line;
	}

	public function next()
	{
		$this->current_line++;
	}

	public function valid()
	{
		return ($this->free_counter < 15);
	}

	private function getColumnCountByHighestColumnLetters($letters)
	{
		$rank = 0;

		$count = 0;
		for ($i = strlen($letters) - 1; $i >= 0; $i--) {
			$count += (ord($letters[$i]) - ord('A') + 1) * pow(32, $rank);
			$rank++;
		}

		return $count;
	}
}