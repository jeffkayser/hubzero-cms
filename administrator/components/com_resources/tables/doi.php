<?php
/**
 * HUBzero CMS
 *
 * Copyright 2005-2011 Purdue University. All rights reserved.
 *
 * This file is part of: The HUBzero(R) Platform for Scientific Collaboration
 *
 * The HUBzero(R) Platform for Scientific Collaboration (HUBzero) is free
 * software: you can redistribute it and/or modify it under the terms of
 * the GNU Lesser General Public License as published by the Free Software
 * Foundation, either version 3 of the License, or (at your option) any
 * later version.
 *
 * HUBzero is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @author    Shawn Rice <zooley@purdue.edu>
 * @copyright Copyright 2005-2011 Purdue University. All rights reserved.
 * @license   http://www.gnu.org/licenses/lgpl-3.0.html LGPLv3
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

/**
 * Short description for 'ResourcesDoi'
 * 
 * Long description (if any) ...
 */
class ResourcesDoi extends JTable
{

	/**
	 * Description for 'local_revision'
	 * 
	 * @var unknown
	 */
	var $local_revision = NULL;  // @var int(11) Primary key

	/**
	 * Description for 'doi_label'
	 * 
	 * @var unknown
	 */
	var $doi_label      = NULL;  // @var int(11)

	/**
	 * Description for 'rid'
	 * 
	 * @var unknown
	 */
	var $rid            = NULL;  // @var int(11)

	/**
	 * Description for 'versionid'
	 * 
	 * @var unknown
	 */
	var $versionid      = NULL;  // @var int(11)

	/**
	 * Description for 'alias'
	 * 
	 * @var unknown
	 */
	var $alias          = NULL;  // @var varchar(30)

	/**
	 * Description for 'doi'
	 * 
	 * @var unknown
	 */
	var $doi            = NULL;  // @var varchar(100) - NEW

	//-----------

	/**
	 * Short description for '__construct'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown &$db Parameter description (if any) ...
	 * @return     void
	 */
	public function __construct( &$db )
	{
		parent::__construct( '#__doi_mapping', 'rid', $db );
	}

	/**
	 * Short description for 'check'
	 * 
	 * Long description (if any) ...
	 * 
	 * @return     boolean Return description (if any) ...
	 */
	public function check()
	{
		if (trim( $this->rid ) == '') {
			$this->setError( JText::_('Your entry must have a resource ID.') );
			return false;
		}
		return true;
	}

	/**
	 * Short description for 'getDoi'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      string $id Parameter description (if any) ...
	 * @param      string $revision Parameter description (if any) ...
	 * @param      mixed $versionid Parameter description (if any) ...
	 * @param      integer $get_full_doi Parameter description (if any) ...
	 * @return     mixed Return description (if any) ...
	 */
	public function getDoi( $id = NULL, $revision = NULL, $versionid = 0, $get_full_doi = 0 )
	{
		if ($id == NULL) {
			$id = $this->rid;
		}
		if ($id == NULL) {
			return false;
		}
		if ($revision == NULL) {
			$revision = $this->local_revision;
		}
		if ($revision == NULL && !$versionid) {
			return false;
		}

		$query  = $get_full_doi ? "SELECT doi " : "SELECT d.doi_label as doi ";
		$query .= "FROM $this->_tbl as d ";
		$query .= "WHERE d.rid='".$id."' ";
		$query .= $revision ? "AND d.local_revision='".$revision."' LIMIT 1" : "AND d.versionid='".$versionid."' LIMIT 1" ;

		$this->_db->setQuery( $query );
		return $this->_db->loadResult();
	}

	/**
	 * Short description for 'getLatestDoi'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      string $id Parameter description (if any) ...
	 * @param      integer $get_full_doi Parameter description (if any) ...
	 * @return     mixed Return description (if any) ...
	 */
	public function getLatestDoi( $id = NULL, $get_full_doi = 0 )
	{
		if ($id == NULL) {
			$id = $this->rid;
		}
		if ($id == NULL) {
			return false;
		}

		$query  = $get_full_doi ? "SELECT doi " : "SELECT d.doi_label as doi ";
		$query .= "FROM $this->_tbl as d ";
		$query .= "WHERE d.rid='".$id."' ORDER BY d.doi_label DESC LIMIT 1";

		$this->_db->setQuery( $query );
		return $this->_db->loadResult();
	}

	/**
	 * Short description for 'loadDoi'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      string $rid Parameter description (if any) ...
	 * @param      mixed $revision Parameter description (if any) ...
	 * @return     boolean Return description (if any) ...
	 */
	public function loadDoi( $rid = NULL, $revision = 0 )
	{
		if ($rid === NULL || !$revision ) {
			return false;
		}

		$this->_db->setQuery( "SELECT * FROM $this->_tbl WHERE rid=".$rid." AND local_revision=".$revision." LIMIT 1" );
		if ($result = $this->_db->loadAssoc()) {
			return $this->bind( $result );
		} else {
			$this->setError( $this->_db->getErrorMsg() );
			return false;
		}
	}

	/**
	 * Short description for 'saveDOI'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      mixed $revision Parameter description (if any) ...
	 * @param      mixed $newlabel Parameter description (if any) ...
	 * @param      string $rid Parameter description (if any) ...
	 * @param      string $alias Parameter description (if any) ...
	 * @param      mixed $versionid Parameter description (if any) ...
	 * @param      string $doi Parameter description (if any) ...
	 * @return     boolean Return description (if any) ...
	 */
	public function saveDOI( $revision = 0, $newlabel = 1, $rid = NULL, $alias='', $versionid = 0, $doi = '' )
	{
		if ($rid == NULL) {
			return false;
		}

		$query = "INSERT INTO $this->_tbl (local_revision, doi_label, rid, alias, versionid, doi) VALUES ('".$revision."','".$newlabel."','".$rid."','".$alias."', '".$versionid."', '".$doi."')";
		$this->_db->setQuery( $query );
		if (!$this->_db->query()) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Short description for 'registerDOI'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $config Parameter description (if any) ...
	 * @param      array $metadata Parameter description (if any) ...
	 * @param      string &$doierr Parameter description (if any) ...
	 * @return     mixed Return description (if any) ...
	 */
	public function registerDOI( $authors, $config, $metadata = array(), &$doierr='' )
	{
		if(empty($metadata)) {
			return false;
		}
		
		// Get configs
		$jconfig 	=& JFactory::getConfig();
		$shoulder   = $config->get('doi_shoulder', '10.4231' );
		$service    = $config->get('doi_newservice', 'https://n2t.net/ezid' );
		$prefix     = $config->get('doi_newprefix', '' );
		$handle     = '';
		$doi 		= '';
		
		// Collect metadata
		$metadata['publisher']  = htmlspecialchars($config->get('doi_publisher', $jconfig->getValue('config.sitename') ));
		$metadata['pubYear'] 	= isset($metadata['pubYear']) ? $metadata['pubYear'] : date( 'Y' );
		$metadata['language'] 	= 'en';

		// Clean up paths
		if (substr($service, -1, 1) == DS) {
			$service = substr($service, 0, (strlen($service) - 1));
		}
		if (substr($shoulder, -1, 1) == DS) {
			$shoulder = substr($shoulder, 0, (strlen($shoulder) - 1));
		}

		// Make service path
		$call  = $service . DS . 'shoulder' . DS . 'doi:' . $shoulder;
		$call .= $prefix ? DS . $prefix : DS;

		// Get config
		$livesite = $jconfig->getValue('config.live_site');
		if(!$livesite || !isset($metadata['targetURL']) || !isset($metadata['title'])) {
			$doierr .= 'Missing url, title or live site configuration';
			return false;
		}
		
		// Get first author / creator name
		if($authors && count($authors) > 0) {
			$creatorName = $authors[0]->name;
		}
		else {
			$creatorName = '';
		}
		
		// Format name
		$nameParts    = explode(" ", $creatorName);
		$metadata['creator']  = end($nameParts);
		$metadata['creator'] .= count($nameParts) > 1 ? ', ' . $nameParts[0] : '';	
		
		// Start input
		$input  = "_target: " . $metadata['targetURL'] ."\n";
		$input .= "datacite.creator: " . $metadata['creator'] . "\n";
		$input .= "datacite.title: ". $metadata['title'] . "\n";
		$input .= "datacite.publisher: " . $metadata['publisher'] . "\n";
		$input .= "datacite.publicationyear: " . $metadata['pubYear'] . "\n";
		$input .= "_profile: datacite";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $call);

		/* Purdue Hubzero Username/Password */
		curl_setopt($ch, CURLOPT_USERPWD, '');
		curl_setopt($ch, CURLOPT_POST, true);

		curl_setopt($ch, CURLOPT_HTTPHEADER,
		  array('Content-Type: text/plain; charset=UTF-8',
		        'Content-Length: ' . strlen($input)));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$output = curl_exec($ch);

		/*returns HTTP Code for success or fail */
		$success = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if($success === 201) {
			$out = explode('/', $output);
			$handle = trim(end($out));
		}
		else {
			$doierr = $success . $output;
			$doierr.= ' '.$call;
			$handle = 0;
		}
		
		$handle = strtoupper($handle);
		$doi = $shoulder . DS . $handle;
		curl_close($ch);
	
		// Prepare XML data
		if($handle) {
			$xdoc = new DomDocument;
			$xmlfile = $this->getXml($authors, $metadata, $doi);	
			$xmlschema = 'http://schema.datacite.org/meta/kernel-2.1/metadata.xsd';

			//Load the xml document in the DOMDocument object
			$xdoc->loadXML($xmlfile);

			//Validate the XML file against the schema
			if ($xdoc->schemaValidate($xmlschema)) {

			    /*EZID parses text received based on new lines. */
				$input  = "_target: " . $metadata['targetURL'] ."\n";
				$input .= "datacite.creator: " . $metadata['creator'] . "\n";
				$input .= "datacite.title: ". $metadata['title'] . "\n";
				$input .= "datacite.publisher: " . $metadata['publisher'] . "\n";
				$input .= "datacite.publicationyear: " . $metadata['pubYear'] . "\n";
				$input .= "_profile: datacite";

			    /*colons(:),percent signs(%),line terminators(\n),carriage returns(\r) are percent encoded for given input string  */ 
			    $input  .= 'datacite: ' . strtr($xmlfile, array(":" => "%3A", "%" => "%25", "\n" => "%0A", "\r" => "%0D")) . "\n"; 

				// Make service path
				$call  = $service . DS . 'id' . DS . 'doi:' . $doi;	

			    $ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $call);

			    /* Purdue Hubzero Username/Password */
			    curl_setopt($ch, CURLOPT_USERPWD, '');
			    curl_setopt($ch, CURLOPT_POST, true);

			    curl_setopt($ch, CURLOPT_HTTPHEADER,
			      array('Content-Type: text/plain; charset=UTF-8',
			            'Content-Length: ' . strlen($input)));
			    curl_setopt($ch, CURLOPT_POSTFIELDS, $input);
			    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			    $output = curl_exec($ch);
			    curl_close($ch);
			} else {
				$doierr .= "XML is invaild. DOI has been created but unable to upload XML as it is invalid. Please modify the created DOI with a valid XML .\n";
			}		
		}

		return $handle ? $handle : NULL;
	}
	
	/**
	 * Short description for 'getXml'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      array $authors Parameter description (if any) ...
	 * @param      array $metadata Parameter description (if any) ...
	 * @param      unknown $doi Parameter description (if any) ...
	 * @return     mixed Return description (if any) ...
	 */
	public function getXml( $authors, $metadata, $doi = 0)
	{
		$datePublished = isset($metadata['datePublished']) 
					? $metadata['datePublished'] : date( 'Y-m-d' );
		$dateAccepted  = date( 'Y-m-d' );
		
		$xmlfile = '<?xml version="1.0" encoding="UTF-8"?><resource xmlns="http://datacite.org/schema/kernel-2.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://datacite.org/schema/kernel-2.1 http://schema.datacite.org/meta/kernel-2.1/metadata.xsd">
	     <identifier identifierType="DOI">'.$doi.'</identifier>';
	 	$xmlfile.='<creators>';
		if($authors && count($authors) > 0) {
			foreach($authors as $author) {
				$nameParts    = explode(" ", $author->name);
				$name  = end($nameParts);
				$name .= count($nameParts) > 1 ? ', ' . $nameParts[0] : '';
				$xmlfile.='<creator>';
				$xmlfile.='	<creatorName>'.$name.'</creatorName>';
				$xmlfile.='</creator>';
			}
		}
		else {
			$xmlfile.='<creator>';
			$xmlfile.='	<creatorName>'.$metadata['creator'].'</creatorName>';
			$xmlfile.='</creator>';
		}
	    $xmlfile.='</creators>';
	    $xmlfile.='<titles>
	        <title>'.$metadata['title'].'</title>
	    </titles>
	    <publisher>'.$metadata['publisher'].'</publisher>
	    <publicationYear>'.$metadata['pubYear'].'</publicationYear>
	    <dates>
	        <date dateType="Valid">'.$datePublished.'</date>
	        <date dateType="Accepted">'.$dateAccepted.'</date>
	    </dates>
	    <language>'.$metadata['language'].'</language>';
		
		if(isset($metadata['typetitle']) && $metadata['typetitle'] != '') {
			$xmlfile.= '<resourceType resourceTypeGeneral="Image">'.$metadata['typetitle'].'</resourceType>';
		}
	    if(isset($metadata['version']) && $metadata['version'] != '') {
			$xmlfile.= '<version>'.$metadata['version'].'</version>';
		}
		if(isset($metadata['abstract']) && $metadata['abstract'] != '') {
			$xmlfile.= '<descriptions>
		        <description descriptionType="Other">';
			$xmlfile.= $metadata['abstract'];
			$xmlfile.= '</description>
			    </descriptions>';
		}
		
		$xmlfile.='</resource>';
		return $xmlfile;
	
	}

	/**
	 * Short description for 'createDOIHandle'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $url Parameter description (if any) ...
	 * @param      unknown $handle Parameter description (if any) ...
	 * @param      unknown $doiservice Parameter description (if any) ...
	 * @param      string &$err Parameter description (if any) ...
	 * @return     mixed Return description (if any) ...
	 */
	public function createDOIHandle($url, $handle, $doiservice, &$err='')
	{
		jimport('nusoap.lib.nusoap');

		$client = new nusoap_client($doiservice, 'wsdl', '', '', '', '');
		$err = $client->getError();
		if ($err) {
			$this->_error = 'Constructor error: '. $err;
			return false;
		}

		$param = array('in0'=>$url, 'in1'=>$handle);

		$result = $client->call('create', $param, '', '', false, true);

		// Check for a fault
		if ($client->fault) {
			//print_r ($result);
			$err = 'Fault: '.$result['faultstring'];
			return false;
		} else {
			// Check for errors
			$err = $client->getError();
			if ($err) {
				// Return the error
				//print_r($err);
				//$this->setError( 'Error: '. $err);		
				return false;
			} else {
				return $result;
			}
		}
	}

	/**
	 * Short description for 'deleteDOIHandle'
	 * 
	 * Long description (if any) ...
	 * 
	 * @param      unknown $url Parameter description (if any) ...
	 * @param      unknown $handle Parameter description (if any) ...
	 * @param      unknown $doiservice Parameter description (if any) ...
	 * @return     mixed Return description (if any) ...
	 */
	public function deleteDOIHandle($url, $handle, $doiservice)
	{
		jimport('nusoap.lib.nusoap');

		$client = new nusoap_client($doiservice, 'wsdl', '', '', '', '');
		$err = $client->getError();
		if ($err) {
			$this->_error = 'Constructor error: '. $err;
			return false;
		}

		$param = array('in0'=>$url, 'in1'=>$handle);

		$result = $client->call('delete', $param, '', '', false, true);

		// Check for a fault
		if ($client->fault) {
			print_r ($result);
			$this->setError( 'Fault: '.$result['faultstring']);
			return false;
		} else {
			// Check for errors
			$err = $client->getError();
			if ($err) {
				// Return the error
				print_r($err);
				$this->setError( 'Error: '. $err);
				return false;
			} else {
				return $result;
			}
		}
	}

	/*
	
	//-----------
	
	public function createDOIHandle($url, $handle, $proxyclient) 
	{
		// Retrieve some plugin parameters
		$proxy = array();
		$proxy['host']     = '';
		$proxy['port']     = '';
		$proxy['username'] = '';
		$proxy['password'] = '';
		
		if ($proxyclient===NULL) {
			$this->setError( JText::_('No web service URL found') );
			return false;
		}
		if($url===NULL or $handle===NULL) {
			$this->setError( JText::_('No handle or URL. Cannot create an empty handle.') );
			return false;
		}
			
		// Try to connect to the web service
		try {
			$client = new SoapClient($proxyclient, $proxy);
		} catch (Exception $e) {
		
			$this->setError( $e->getMessage() );
			return false;
		}
			
		// Set the array of parameters we'll be passing to the web service
		$param = array('in0'=>$url,'in1'=>$handle);

		// Try to call the web service
		try {
			$result = $client->__soapCall('create', $param, '', '', false, true);
		} catch (SoapFault $e) {
			$this->setError( JText::_('WEBSERVICE_FAULT').' '.$e );
			return false;
		}
		
		// Return the result
		return $result;
	
	}
	*/
}

