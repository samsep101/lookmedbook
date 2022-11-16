<?php
	interface IHtmlCache
	{
		/**
		 * @param $cache_id
		 * @param $tags
		 *
		 * @return bool
		 */
		public function start($cache_id, $tags = array());

		/**
		 * @param $cache_id
		 *
		 * @return bool
		 */
		public function delete($cache_id);

		/**
		 * @param $tag
		 *
		 * @return bool
		 */
		public function deleteGroup($tag);

		/**
		 * @return void
		 */
		public function end();

		public function enable();

		public function disable();

		public function clearAll();
	}