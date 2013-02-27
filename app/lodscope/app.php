<?php

class LODScopeApp extends App
{
	public function __construct()
	{
		parent::__construct();
		$this->routes['__NONE__'] = array('file' => 'home.php', 'class' => 'LODScopeHomePage');
	}
}
