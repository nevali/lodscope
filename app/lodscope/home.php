<?php

/*
 * Copyright 2013 Mo McRoberts.
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *  limitations under the License.
 */

uses('rdf');

class LODScopeHomepage extends Page
{
	protected $defaultSkin = 'lodscope';
	protected $templateName = 'home.phtml';
	protected $uri = null;

	protected $supportedTypes = array(
		'text/html',
		'text/turtle',
		'application/rdf+xml',
		'text/n3',
		'application/json',
		);
	
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
