<?php
	class SeimeLT {
		private $JSVariables = array();
		private $JSFiles = array();
		private $charts = array();

		public function __construct($DB, $type, $id = NULL) {
			$class = $type . 'Charts';
			if ($id !== NULL)	$this->ChartGenerator = new $class($DB, $this, $id);
			else $this->ChartGenerator = new $class($DB, $this);
			$this->ChartGeneratorClass = $class;
		}

		public function registerJS($file, $path, $variable_name, $data) {
			$this->JSFiles[$file] = $path;
			$this->JSVariables[$variable_name] = json_encode($data);
		}

		public function registerChart($type, $data) {
			$this->charts[$type] = $data;
		}

		public function populateAll() {
			$Reflection = new ReflectionClass($this->ChartGeneratorClass);
	
			foreach ($this->charts as $type => $data) {
				if ($Reflection->hasMethod($type)) {
					array_unshift($data, $type);
					call_user_func_array(array($this->ChartGenerator, $type), $data);
				}
			}
		
			if (!empty($this->JSVariables)) {
				echo '<script type="text/javascript">' . "\n";
			
				foreach ($this->JSVariables as $var => $data) {
					echo "var $var = $data; \n \n";
				}
		
				echo '</script>' . "\n";
			}
		
			if (!empty($this->JSFiles)) {
				foreach ($this->JSFiles as $file => $path) {
					$real_path = dirname(__FILE__) . '/../' . $path . $file . '.js';
	
					if (file_exists($real_path)) {
						echo '<script type="text/javascript" src="' . $path . $file . '.js"></script>' . "\n";
					}
				}
			}
		}
	}