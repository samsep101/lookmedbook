<?php
	/**
	 * @property int $id
	 * @property string $name
	 *
	 */
    class TaskStatusModel extends DynamicModel {
		const IN_QUEUE = 1;
		const PROCCESS = 2;
		const DONE = 3;
		const ERROR = 4;
	}