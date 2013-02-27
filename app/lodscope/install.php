<?php

class LODScopeModuleInstall extends ModuleInstaller
{
	public $canBeSole = true;
	
	public function writeAppConfig($file, $isSoleWebModule = false, $chosenSoleWebModule = null)
	{
		$this->writeWebRoute($file, $isSoleWebModule);
	}
}
