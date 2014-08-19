<?php
abstract class KWController {
	/**
	 * 
	 * @var string $view
	 * If you don't specify any, it will load the default file "linked" with controller (views/modelName/controllerName/action.php)
	 * If is null, it don't show ANY, only echo's from controller
	 * You can specify a file if it exist in (views/modelName/controllerName/$view.php)
	 */
	public $module;
	public $controller;
	public $view;
	
	/**
	 * Constructor
	 * @param string $defView Default view to show if user don't change it
	 */
	function __construct($module, $controller, $action = null) {
		$this->module = $module;
		$this->controller = $controller;
		$this->view = $action;
	} 
	
	
	/**
	 * @name showContent
	 * This function show the content (action)
	 * It will be called in the template
	 */
	public function showContent() {
		$f = kw::$app_dir . 'private/modules/'.$this->module.'/views/'.$this->controller.'/' . $this->view . '.php';
		if (is_file($f)) return include $f;
		return false; 
	}
	
	/**
	 *  @name view 
	 *  This function load the template file
	 */
	public function loadView() {
		if ($this->view !== null) {
			// Load template
			include kw::$app_dir . 'public/templates/' . app::$template . '/index.php';
		}
	}
}