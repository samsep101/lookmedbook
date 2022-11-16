<?php

/**
 * Сниппет пагинации
 * - можно использовать синглтон пагинатор через объект самого сниппета.
 * - синглтон сниппета будет общим для всего приложения
 * - можно также получить отдельный экземпляр пагинации, если нужно собрать на странице больше одной пагинации.
 */
class SnippetPagination {
	/** @author Playmore 2017 (playmoredevelop@gmail.com) */

	public $total = 0;
	public $perpage = 0;
	public $page = 0;
	public $offset = 0;
	public $before = 0;
	public $next = 0;
	public $last = 0;
	public $getmethod = false;
	public $requestKey = 'pages';

	public $replaces = [
		'{text.first}' => 'первая',
		'{text.prev}' => '<i class="icon-arrow-left2" style="font-size:10px"></i>',
		'{text.next}' => '<i class="icon-arrow-right2" style="font-size:10px"></i>',
		'{text.last}' => 'последняя',
		'{class.first}' => 'first',
		'{class.prev}' => 'prev',
		'{class.next}' => 'next',
		'{class.last}' => 'last',
		'{href.first}' => '',
		'{href.prev}' => '',
		'{href.next}' => '',
		'{href.last}' => '',
	];

	private $_diap = 3;
	private $_start = 1;
	private $_end = false;

	/**
	 * Вернет отдельный экземпляр пагинации у которого будет свой метод html и свои настройки
	 * @param int $total
	 * @param int $limit
	 * @param string $requestKey
	 * @return SnippetPagination
	 */
	public function make($total, $limit, $requestKey = false) {

		$pagiObject = new static;

		$pagiObject->perpage = (int)$limit;
		if($requestKey !== false){
			$this->requestKey = $requestKey;
		}
		$pagiObject->page = (int)$this->_getRequest($this->requestKey, 1, FILTER_SANITIZE_NUMBER_INT);
		$pagiObject->total = (int)$total;

		$pagiObject->offset = ($pagiObject->perpage * $pagiObject->page) - $pagiObject->perpage;

		$pagiObject->last = intval(ceil( $pagiObject->total / $pagiObject->perpage ));
		$pagiObject->before = ($pagiObject->page > 1) ? $pagiObject->page - 1 : false;
		$pagiObject->next = ($pagiObject->page < $pagiObject->last) ? $pagiObject->page + 1 : false;

		return $pagiObject;
	}

	/**
	 * Вернет верстку пагинации по заданным ранее настройкам
	 * @param string $baseuri
	 * @param string $class
	 * @param int $diap
	 * @return html
	 */
	public function html($baseuri = '/', $class = 'pagination-sm', $diap = 3) {

		$this->replaces['{baseuri}'] = rtrim($baseuri, '/');

		$this->_diap = (int)$diap;
		$this->_start = ($this->page - $this->_diap);
		$this->_end = ($this->page + $this->_diap);

		if($this->_start < 1) $this->_start = 1;
		if($this->_end > $this->last) $this->_end = $this->last;

		if($this->getmethod) {
			return $this->_gen_pagi_qstring($class);
		} else {
			return $this->_gen_pagi_slugs($class);
		}

	}
	
	private function _gen_pagi_qstring($class = '') {

		$this->replaces['{href.first}'] = $this->_getQueryStringReplace([$this->requestKey => false], false, false);
		$this->replaces['{href.next}'] = $this->_getQueryStringReplace([$this->requestKey => $this->next], false, false);
		$this->replaces['{href.last}'] = $this->_getQueryStringReplace([$this->requestKey => $this->last], false, false);

		if($this->before > 1){
			$this->replaces['{href.prev}'] = $this->_getQueryStringReplace([$this->requestKey => $this->before], false, false);
		} else {
			$this->replaces['{href.prev}'] = $this->_getQueryStringReplace([$this->requestKey => false], false, false);
		}

		$before = [];
		for($i = $this->_start; $i < $this->page; $i++){
			if($i > 1){
				$query_string = $this->_getQueryStringReplace([$this->requestKey => $i], false, false);
				$before[] = '<a href="{baseuri}'.$query_string.'">'.$i.'</a>';
			} else {
				$query_string = $this->_getQueryStringReplace([$this->requestKey => false], false, false);
				$before[] = '<a href="{baseuri}'.$query_string.'">'.$i.'</a>';
			}
		}
		$after = [];
		for($i = $this->page + 1; $i <= $this->_end; $i++){
			$query_string = $this->_getQueryStringReplace([$this->requestKey => $i], false, false);
			$after[] = '<a href="{baseuri}'.$query_string.'">'.$i.'</a>';
		}

		$template = implode(PHP_EOL, [
			'<div class="pagination '.$class.'">',
			($this->before AND $this->replaces['{text.first}']) ? '<a href="{baseuri}{href.first}" class="{class.first}">{text.first}</a>' : '',
			($this->before AND $this->replaces['{text.prev}']) ? '<a href="{baseuri}{href.prev}" class="{class.prev}">{text.prev}</a>' : '',
			implode(PHP_EOL, $before),
			'<span class="current">'.$this->page.'</span>',
			implode(PHP_EOL, $after),
			($this->next AND $this->replaces['{text.next}']) ? '<a href="{baseuri}{href.next}" class="{class.next}">{text.next}</a>' : '',
			($this->next AND $this->replaces['{text.last}']) ? '<a href="{baseuri}{href.last}" class="{class.last}">{text.last}</a>' : '',
			'</div>'
		]);

		return str_replace(array_keys($this->replaces), array_values($this->replaces), $template);
	}

	private function _gen_pagi_slugs($class = '') {

		$before = [];
		for($i = $this->_start; $i < $this->page; $i++){
			if($i == 1) {
				$before[] = '<a href="{baseuri}">'.$i.'</a>';
			} else {
				$before[] = '<a href="{baseuri}/'.$i.'">'.$i.'</a>';
			}
		}
		$after = [];
		for($i = $this->page + 1; $i <= $this->_end; $i++){
			$after[] = '<a href="{baseuri}/'.$i.'">'.$i.'</a>';
		}

		$this->replaces['{i.prev}'] = $this->before;
		$this->replaces['{i.next}'] = $this->next;
		$this->replaces['{i.last}'] = $this->last;

		$template = implode(PHP_EOL, [
			'<div class="pagination '.$class.'">',
			($this->before AND $this->replaces['{text.first}']) ? '<a href="{baseuri}" class="{class.first}">{text.first}</a>' : '',
			($this->before AND $this->replaces['{text.prev}']) ? '<a href="{baseuri}/{i.prev}" class="{class.prev}">{text.prev}</a>' : '',
			implode(PHP_EOL, $before),
			'<span class="current">'.$this->page.'</span>',
			implode(PHP_EOL, $after),
			($this->next AND $this->replaces['{text.next}']) ? '<a href="{baseuri}/{i.next}" class="{class.next}">{text.next}</a>' : '',
			($this->next AND $this->replaces['{text.last}']) ? '<a href="{baseuri}/{i.last}" class="{class.last}">{text.last}</a>' : '',
			'</div>'
		]);

		return str_replace(array_keys($this->replaces), array_values($this->replaces), $template);
	}

    private function _getRequest($name, $default = false, $filter = FILTER_SANITIZE_STRING) {

		if(array_key_exists($name, $_REQUEST)){

			if(is_array($_REQUEST[$name])){
				return filter_var_array($_REQUEST[$name], $filter);
			} else {
				return filter_var($_REQUEST[$name], $filter);
			}
		}

		return $default;
	}

    /**
	 * Вернет готовую строку запроса GET с подменой переменных
	 * - дополнительно можно отфильтровать допустимые ключи, которые могут быть в ссылке (остальные будут отброшены)
	 * - можно убрать конкретные переменные из запроса передав $replace[key => false]
	 * @param type $replace
	 * @param type $allow
	 * @return type
	 */
    private function _getQueryStringReplace($replace = [], $allow = [], $withbase = true) {

		$get = $_GET;

		if(!empty($allow)){
			$get = array_intersect_key($get, array_flip($allow));
		}

		foreach($replace as $key => $val){
			if($val === false){
				unset($get[$key]);
			} else {
				$get[$key] = $val;
			}
		}

		$url = '';
		if($withbase){
			$url = parse_url($_SERVER['REQUEST_URI']);
			$url = $url['path'];
		}

		$get = http_build_query($get);

		if(mb_strlen($get)){
			return $url.'?'.$get;
		}

		return $url;
	}

}

/* END CLASS: SnippetPagination */