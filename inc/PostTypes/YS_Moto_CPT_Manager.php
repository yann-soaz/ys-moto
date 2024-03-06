<?php
include 'YS_Moto_CPT.php';

class YS_Moto_CPT_Manager {

	private static ?YS_Moto_CPT_Manager $instance = null;
	private array $CPT_Collection = [];

	/**
	 * Constructor is private to do not multiply the number of instances of this class.
	 */
	private function __construct (bool $auto_init = false)
	{
		if ($auto_init)
			add_action('init', [$this, 'init']);
	}

	/**
	 * function who get the manager instance if exist or create a new one.
	 * @param bool $auto_init - define if init action must be added or not. init method can be called mannualy if needed.
	 */
	public static function use (bool $auto_init = false): YS_Moto_CPT_Manager {
		if (is_null(self::$instance)) {
			self::$instance = new self($auto_init);
		}
		return self::$instance;
	}

	/**
	 * Add a custom post type to wordpress
	 * @param string $slug - slug du type de contenu
	 * @param callable $cpt_settings - fonction permettant d'agir sur le cpt
	 * @return YS_Moto_CPT_Manager;
	 */
	public function add_post_type (string $slug, callable $cpt_settings): YS_Moto_CPT_Manager 
	{
		$cpt = new YS_Moto_CPT($slug);
		call_user_func_array($cpt_settings, [&$cpt]);
		$this->CPT_Collection[sanitize_title($slug)] = $cpt;
		return $this;
	}

	/**
	 * Edit a custom post type registered in the manager
	 * @param string $slug - slug du type de contenu
	 * @param callable $cpt_settings - fonction permettant d'agir sur le cpt
	 * @return YS_Moto_CPT_Manager;
	 */
	public function edit_post_type (string $slug, callable $cpt_settings): YS_Moto_CPT_Manager
	{
		if (!empty($this->CPT_Collection[sanitize_title($slug)])) {
			call_user_func(
				$cpt_settings, 
				$this->CPT_Collection[sanitize_title($slug)]
			);
		}
		return $this;
	}


	/**
	 * Launch the registration of content created in the manager.
	 */
	public function do_init ()
	{
		foreach ($this->CPT_Collection as $slug => $cpt) {
			$cpt->register();
		}
	}

	/**
	 * Add a custom post type to the list of ones must be registered
	 */

}