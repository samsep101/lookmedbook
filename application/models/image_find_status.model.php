<?php
    class ImageFindStatusModel extends DynamicModel
    {
        const UPLOADED = 1;
        const IN_QUEUE = 2;
        const NOT_FOUND = 3;
        const OK = 4;
		const AUTO = 7;
        const ERROR = 5;

		const FIND_IN_VIDAL = 6;
    }