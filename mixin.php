<?php

	/**
	 * @author  mail@platdesign.de
	 * Useage:
	 * - Create a directory called: 'mixins'
	 * - Create a file called: 'title.php' with following content
	 * 		<h1><?= $title; ?></h1>
	 * - Register the directory with: mixin::__registerDir('./mixins');
	 * - Render the mixin through:
	 * 		echo mixin::title([
	 *   			'title' => 'Hello World!'
	 *     		 ]);
	 */
	final class mixin {

		/**
		 * List of directories
		 * @var Array
		 */
		private static $mixins = [];





		/**
		 * Scans a directory for mixin-files and registers them
		 * @param  [String] $dirname [description]
		 * @return NULL
		 */
		public static function registerDir($path, $namespace=NULL) {

			if( file_exists($path) ) {
				foreach (new DirectoryIterator($path) as $fileInfo) {
					if($fileInfo->isFile()) {

						if( isset($namespace) ) {
							$name = $namespace.':';
						} else  {
							$name = '';
						}

						$name .= $fileInfo->getBasename('.'.$fileInfo->getExtension());
						$file = $fileInfo->getPathname();

						self::$mixins[$name] = function()use($file) {
							$closure = include $file;

							return self::__callMixinClosure($closure, func_get_args());
						};
					}
				}
			}


		}



		public static function create($name, $closure) {
			self::$mixins[$name] = function() use ($closure){
				return self::__callMixinClosure($closure, func_get_args());
			};
		}


		public static function __callMixinClosure($closure, $globals) {
			ob_start();

				// Execute closure
				if( ($result = call_user_func_array($closure, $globals)) !== NULL ) {
					$fileContent = $result;
				} else {
					$fileContent = ob_get_contents();
				}

			ob_end_clean();
			return $fileContent;
		}

		/**
		 * Handles calls for mixin-files
		 * @param  [String] $name
		 * @param  [Array] $args
		 * @return [String] The mixin-markup
		 */
		public static function __callStatic($name, $args) {
			$name = str_replace('_', ':', $name);
			return self::render($name, $args);
		}



		public static function render($name, $globals) {

			if( isset( self::$mixins[$name] ) ) {
				return call_user_func_array(self::$mixins[$name], $globals);
			} else {
				return "Mixin '$name' not found!";
			}
		}

	}










	/**
	 * Helper function for mixin::render($name, $globals);
	 * @param  [type] $name    [description]
	 * @param  [type] $globals [description]
	 * @return [type]          [description]
	 */
	function mixin($name, $globals=[]) {
		$args = func_get_args();
		if( count($args) > 2 || isset($globals) && !is_array($globals)) {
			$globals = array_splice($args, 1);
		}
		return mixin::render($name, $globals);
	}

?>