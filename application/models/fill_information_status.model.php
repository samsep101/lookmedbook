<?php
	/**
	 * @var string $name
	 */
	class FillInformationStatusModel extends DynamicModel
	{
		const BY_NAME_AND_DOSAGE_FORM = 1;
		const BY_NAME = 2;
		const BY_NAME_PART = 3;
		const BY_NAME_AND_UNIT_SIZE = 7;

		const NOT_FOUND = 4;
		const IN_QUEUE = 5;
		const OK = 6;
		const NOT_IN_VIDAL = 7;
	}