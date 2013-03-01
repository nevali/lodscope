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

	public function __construct()
	{
		parent::__construct();
		RDF::registerPrefix('og', 'http://ogp.me/ns#');
		RDF::registerPrefix('fb', 'http://ogp.me/ns/fb#');
		RDF::registerPrefix('user', 'http://graph.facebook.com/schema/user#');
		RDF::registerPrefix('schema', 'http://schema.org/');
		RDF::registerPrefix('wo', 'http://purl.org/ontology/wo/');
		RDF::registerPrefix('po', 'http://purl.org/ontology/po/');
		RDF::registerPrefix('cc', 'http://creativecommons.org/ns#');
		RDF::registerPrefix('ccweb', 'http://web.resource.org/cc/');
		RDF::registerPrefix('skos2004', 'http://www.w3.org/2004/02/skos/core#');
		RDF::registerPrefix('bibo', 'http://purl.org/ontology/bibo/');
		RDF::registerPrefix('doi', 'http://dx.doi.org/');
		RDF::registerPrefix('nature', 'http://ns.nature.com/terms/');
		RDF::registerPrefix('prism', 'http://prismstandard.org/namespaces/basic/2.1/');
		RDF::registerPrefix('ma', 'http://www.w3.org/TR/2010/WD-mediaont-10-20100608/');
		RDF::registerPrefix('dbprop', 'http://dbpedia.org/property/');
		RDF::registerPrefix('adms', 'http://www.w3.org/ns/adms#');
		RDF::registerPrefix('vann', 'http://purl.org/vocab/vann/');
		RDF::registerPrefix('relators', 'http://id.loc.gov/vocabulary/relators/');
		RDF::registerPrefix('mads', 'http://www.loc.gov/mads/rdf/v1#');
		RDF::registerPrefix('voaf', 'http://purl.org/vocommons/voaf#');
		RDF::registerPrefix('sws', 'http://www.w3.org/2003/06/sw-vocab-status/ns#');
		RDF::registerPrefix('wot', 'http://xmlns.com/wot/0.1/');
	}
	
	protected function getObject()
	{
		if($this->object === null)
		{
			if(isset($this->request->query['uri']))
			{
				$this->uri = trim($this->request->query['uri']);
			}
			if(strlen($this->uri))
			{
				$this->object = RDF::documentFromURL($this->uri);
			}
		}
		if($this->object !== null)
		{
			$serialisations = $this->object->serialisations();
			foreach($serialisations as $key => $info)
			{
				if(is_numeric($key))
				{
					$this->supportedTypes[] = $info;
				}
				else
				{
					$this->supportedTypes[$key] = $info;
				}
			}
		}
		return true;
	}
	
	protected function predicateName($target)
	{
		if(!defined('LODSCOPE_FETCH_VOCABULARIES'))
		{
			return null;
		}
		$curl = new CurlCache();
		$curl->followLocation = true;
		$curl->autoReferrer = true;
		$curl->unrestrictedAuth = true;
		$curl->httpAuth = Curl::AUTH_ANYSAFE;
		$curl->cacheTime = 28 * 24 * 60 * 60;
		$curl->timeout = 10;
		set_time_limit(30);
		$doc = RDF::documentFromURL($target, $curl);
		if($doc === null)
		{
			return null;
		}
		if(isset($doc[$target]))
		{
			return $doc[$target]->title();
		}
		return null;
	}
	
	public /*callback*/ function linkFilter($target, $text, $predicate, $doc, $subj)
	{
		if($predicate === null && !strcmp($target, URI::rdf.'about'))
		{
			return 'Universal identifier';
		}
		if($predicate === null && !strcmp($target, URI::rdf.'type'))
		{
			return 'Type';
		}
		if(!strlen($target) || substr($target, 0, 1) == '#')
		{
			return '<a class="local" href="' . _e($target) . '">' . _e($text) . '</a>';
		}
		if($predicate === null || !strcmp($predicate, URI::rdf.'type'))
		{
			$predicateName = $this->predicateName($target);
			if($predicateName !== null)
			{				
				$link = '<a title="' . _e($text) . '" href="' . _e($this->request->base . '?uri=' . urlencode($target)) . '">' . _e($predicateName) . '</a>';
			}
		}
		else
		{
			$predicateName = null;
		}
		if($predicateName === null)
		{
			$link = '<a class="predname" href="' . _e($this->request->base . '?uri=' . urlencode($target)) . '">' . _e($text) . '</a>';
		}
		if(!strcmp($target, $text) && strlen($predicate))
		{
			$link .= ' (<a class="browse" href="' . _e($target) . '">Visit page</a>)';
		}
		return $link;
	}
	
	protected function assignTemplate()
	{
		parent::assignTemplate();
		$serialisations = array();
		unset($this->supportedTypes['application/x-xmp+xml']['hide']);
		foreach($this->supportedTypes as $k => $info)
		{
			if(!is_array($info))
			{
				$info = array('type' => $info);
			}
			$resource = $this->request->resource;
			if(isset($this->uri))
			{
				$query = '?uri=' . urlencode($this->uri);
			}
			else
			{
				$query = '';
			}
			if(!isset($info['type'])) $info['type'] = $k;
			if(!empty($info['hide'])) continue;
			if($info['type'] == 'text/html') continue;
			$location = $resource . MIME::extForType($info['type']) . $query;
			$link = array('rel' => 'alternate', 'type' => $info['type'], 'href' => $location);
			if(isset($info['title']))
			{
				$link['title'] = $info['title'];
				$serialisations[$location] = $info;
				$serialisations[$location]['alt'] = array();
			}
			$this->links[] = $link;
			if(isset($info['alt']))
			{
				foreach($info['alt'] as $mime => $title)
				{
					$altloc = $resource . MIME::extForType($mime) . $query;
					if(isset($info['title']))
					{
						$serialisations[$location]['alt'][$altloc] = array('title' => $title);
						$title = $info['title'] . ' (' . $title . ')';
					}
					$link = array('rel' => 'alternate', 'type' => $mime, 'title' => $title, 'href' => $altloc);
					$this->links[] = $link;
				}
			}
		}
		$this->vars['uri'] = $this->uri;
		$this->vars['serialisations'] = $serialisations;
		if(isset($this->object))
		{
			$this->object->linkFilter = array($this, 'linkFilter');
		}
	}
}
