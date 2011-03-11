<?php
/**
 * @package		HUBzero CMS
 * @author		Nicholas J. Kisseberth <nkissebe@purdue.edu>
 * @copyright	Copyright 2005-2009 by Purdue Research Foundation, West Lafayette, IN 47906
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 *
 * Copyright 2005-2009 by Purdue Research Foundation, West Lafayette, IN 47906.
 * All rights reserved.
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License,
 * version 2 as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die( 'Restricted access' );

jimport('joomla.document.document');

class Hubzero_Document
{
	public function addComponentStylesheet($component, $stylesheet = '', $type = 'text/css', $media = null, $attribs = array(), $augment = false)
	{
		$mainframe =& JFactory::getApplication();

		$jdocument = &JFactory::getDocument();

		$template  = $mainframe->getTemplate();
		
		if (empty($stylesheet)) {
			$stylesheet = substr($component,4) . '.css';
		}

		$templatecss = DS . 'templates' . DS . $template . DS . 'html' . DS . $component . DS . $stylesheet;

		$componentcss = DS . 'components' . DS . $component . DS . $stylesheet;

		if (file_exists(JPATH_SITE . $templatecss))
		{
			if ($augment && file_exists(JPATH_SITE . $componentcss) ) {
				$this->addStyleSheet($componentcss, $type, $media, $attribs);
			}
			
			$jdocument->addStyleSheet($templatecss, $type, $media, $attribs);
        }
		else if (file_exists(JPATH_SITE . $componentcss)) {
		    $jdocument->addStyleSheet($componentcss, $type, $media, $attribs);
		}
	}

	public function getComponentImage($component, $image)
	{
		$mainframe =& JFactory::getApplication();

		$template  = $mainframe->getTemplate();

		$templateimage = DS . 'templates' . DS . $template . DS . 'html' . DS . $component . DS . 'images' . DS . $image;
        
		$componentimage = DS . 'components' . DS . $component . DS . 'images' . DS . $image;

		if (file_exists(JPATH_SITE . $templateimage)) {
			return $templateimage;
		} else {
			return $componentimage;
		}
	}

	//-----------

	public function getComponentStylesheet($component, $stylesheet)
	{
		$mainframe =& JFactory::getApplication();

		$template  = $mainframe->getTemplate();

		$templateimage = DS . 'templates' . DS . $template . DS . 'html' . DS . $component . DS . 'css' . DS . $stylesheet;
        
		$componentimage = DS . 'components' . DS . $component . DS . 'css' . DS . $stylesheet;

		if (file_exists(JPATH_SITE . $templateimage)) {
			return $templateimage;
		} else {
			return $componentimage;
		}
	}

	//-----------

	public function getModuleImage($module, $image)
	{
		$mainframe =& JFactory::getApplication();

		$template  = $mainframe->getTemplate();

		$templateimage = DS . 'templates' . DS . $template . DS . 'html' . DS . $module . DS . 'images' . DS . $image;
        
		$moduleimage = DS . 'modules' . DS . $module . DS . 'images' . DS . $image;
		
		if (file_exists(JPATH_SITE . $templateimage)) {
			return $templateimage;
		} else {
			return $moduleimage;
		}
	}

	//-----------

	public function getHubImage($image)
	{
		$mainframe =& JFactory::getApplication();

		$template  = $mainframe->getTemplate();

		$templateimage = DS . 'templates' . DS . $template . DS . 'images' . DS . $image;
        
		$hubimage =  DS . 'components' . DS . 'com_hub'  . DS . 'images' . DS . $image;
		
		if (file_exists(JPATH_SITE . $templateimage)) {
			return $templateimage;
		} else {
			return $hubimage;
		}
	}

	//-----------

	public function addModuleStyleSheet($module, $stylesheet = '', $type = 'text/css', $media = null, $attribs = array(), $augment = false)
	{
		$mainframe =& JFactory::getApplication();

		$jdocument = &JFactory::getDocument();

		$template  = $mainframe->getTemplate();
		
		if (empty($stylesheet)) {
			$stylesheet = $module . '.css';
		}

		$templatecss = DS . 'templates' . DS . $template . DS . 'html' . DS . $module . DS . $stylesheet;

		$modulecss = DS . 'modules' . DS . $module . DS . $stylesheet;

		if (file_exists(JPATH_SITE . $templatecss)) {
			if ($augment) {
				$this->addStyleSheet($modulecss, $type, $media, $attribs);
			}
			$jdocument->addStyleSheet($templatecss, $type, $media, $attribs);
        } else {
			$jdocument->addStyleSheet($modulecss, $type, $media, $attribs);
		}
	}
	
	//-----------
	
	public function addPluginStyleSheet($plugin_group, $plugin, $stylesheet = '', $type = 'text/css', $media = null, $attribs = array(), $augment = false)
	{
		$mainframe =& JFactory::getApplication();

		$jdocument = &JFactory::getDocument();

		$template  = $mainframe->getTemplate();
		
		if (empty($stylesheet)) {
			$stylesheet = $plugin . '.css';
		}
		$templatecss = DS . 'templates' . DS . $template . DS . 'html' . DS . 'plg_'.$plugin_group.'_'.$plugin . DS . $stylesheet;

		$plugincss = DS . 'plugins' . DS . $plugin_group . DS . $plugin . DS . $stylesheet;

		if (file_exists(JPATH_SITE . $templatecss)) {
			if ($augment) {
				$this->addStyleSheet($plugincss, $type, $media, $attribs);
			}
			$jdocument->addStyleSheet($templatecss, $type, $media, $attribs);
        } else {
			$jdocument->addStyleSheet($plugincss, $type, $media, $attribs);
		}
	}
}
