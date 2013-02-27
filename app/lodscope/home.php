<?php

uses('rdf');

class LODScopeHomepage extends Page
{
	protected $defaultSkin = 'lodscope';
	protected $templateName = 'home.phtml';
	protected $uri = null;
	
	protected function getObject()
	{
		if(isset($this->request->query['uri']))
		{
			$this->uri = trim($this->request->query['uri']);
		}
		if(strlen($this->uri))
		{			
			$this->object = RDF::documentFromURL($this->uri);			
		}
		return true;
	}
	
	public /*callback*/ function linkFilter($target, $text, $predicate, $doc, $subj)
	{
		if(!strlen($target) || substr($target, 0, 1) == '#')
		{
			return '<a class="local" href="' . _e($target) . '">' . _e($text) . '</a>';
			return null;
		}
		$link = '<a href="' . _e($this->request->base . '?uri=' . urlencode($target)) . '">' . _e($text) . '</a>';
		if(!strcmp($target, $text) && strlen($predicate))
		{
			$link .= ' (<a class="browse" href="' . _e($target) . '">Visit page</a>)';
		}
		return $link;
	}
	
	protected function assignTemplate()
	{
		parent::assignTemplate();
		$this->vars['uri'] = $this->uri;
		if(isset($this->object))
		{
			$this->object->linkFilter = array($this, 'linkFilter');
		}
	}
}