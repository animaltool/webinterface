<?php
namespace DLigo\Animaltool\ViewHelpers\Link;

/*************************************************************************
*  Copyright notice
*
*  (c) 2015 [d] Ligo design+development - All rights reserved
*  (Details on https://github.com/animaltool)
*
*  This script belongs to the TYPO3 Flow package "DLigo.Animaltool".
*  The DLigo Animaltool project is free software; you can redistribute
*  it and/or modify it under the terms of the GNU Lesser General Public
*  License (GPL) as published by the Free Software Foundation; either
*  version 3 of the License, or  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
*************************************************************************/

class ActionViewHelper extends \TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper {


	/**
	 * Render the link.
	 *
	 * @param string $action Target action
	 * @param array $arguments Arguments
	 * @param string $controller Target controller. If NULL current controllerName is used
	 * @param string $package Target package. if NULL current package is used
	 * @param string $subpackage Target subpackage. if NULL current subpackage is used
	 * @param string $section The anchor to be added to the URI
	 * @param string $format The requested format, e.g. ".html"
	 * @param array $additionalParams additional query parameters that won't be prefixed like $arguments (overrule $arguments)
	 * @param boolean $addQueryString If set, the current query parameters will be kept in the URI
	 * @param array $argumentsToBeExcludedFromQueryString arguments to be removed from the URI. Only active if $addQueryString = TRUE
	 * @param boolean $useParentRequest If set, the parent Request will be used instead of the current one
	 * @param boolean $absolute By default this ViewHelper renders links with absolute URIs. If this is FALSE, a relative URI is created instead
	 * @param string mode 'hide' (dont render at all), 'nolink' (default - render only the content), 'class' (add a class)
	 * @param string currentClass additional CSS Class for 'class' mode (default:'cur')     
	 * @param string before put in front of the link      
	 * @param string after put behind the link      
	 * @param string beforeCur put in front of the current link      
	 * @param string afterCur put behind the current link      
	 * @return string The rendered link
	 */
	public function render($action, $arguments = array(), $controller = NULL, $package = NULL, $subpackage = NULL, $section = '', $format = '',  array $additionalParams = array(), $addQueryString = FALSE, array $argumentsToBeExcludedFromQueryString = array(), $useParentRequest = FALSE, $absolute = TRUE, $mode = 'nolink', $currentClass = 'cur', $before = '',$after='',$beforeCur='',$afterCur='') {
    if($this->isCurrent($action,$controller)){
      switch($mode){
        case 'hide':
          return $beforeCur."".$afterCur;
          break;
        case 'class':
          $arguments['class'].=' '.$currentClass;
          $before=$beforeCur;
          $after=$afterCur;
          break;
        default:
          return $beforeCur.$this->renderChildren().$afterCur;
          break;
      };
    };
		return $before.parent::render($action, $arguments, $controller, $package, $subpackage, $section, $format, $additionalParams, $addQueryString, $argumentsToBeExcludedFromQueryString, $useParentRequest, $absolute).$after;
	}

  protected function isCurrent($action,$controller=null){
    $curcontroller=strtolower($this->controllerContext->getRequest()->getControllerName());
    $curaction=strtolower($this->controllerContext->getRequest()->getControllerActionName());
    $controller=strtolower($controller);
    $action=strtolower($action);
    if($action==$curaction && ($controller==$curcontroller || $curcontroller===null)){
      return true;
    } else {
      return false;
    }
  }

}
