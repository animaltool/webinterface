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

class IfActionViewHelper extends \TYPO3\Fluid\ViewHelpers\Link\ActionViewHelper {

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
	 * @param boolean $condition if($condition) link else content/alternateLink
	 * @param sting $elseAction Action to use in else case    
	 * @param sting $elseController Controller to use in else case (only used when $elseAction is set)    
	 * @param array $elseArguments ...
	 * @param string $elsePackage ...
	 * @param string $elseSubpackage ...
	 * @param string $elseSection ...
	 * @param string $elseFormat ...
	 * @param array $elseAdditionalParams ...
	 * @param boolean $elseAddQueryString ...
	 * @param array $elseArgumentsToBeExcludedFromQueryString ...
	 * @param boolean $elseUseParentRequest ...
	 * @param boolean $elseAbsolute ...
	 * @return string The rendered link
	 */
	public function render($action, $arguments = array(), $controller = NULL, $package = NULL, $subpackage = NULL, $section = '', $format = '',  array $additionalParams = array(), $addQueryString = FALSE, array $argumentsToBeExcludedFromQueryString = array(), $useParentRequest = FALSE, $absolute = TRUE, $condition = TRUE, $elseAction = NULL, $elseController = NULL, $elseArguments = array(), $elsePackage = NULL, $elseSubpackage = NULL, $elseSection = '', $elseFormat = '', array $elseAdditionalParams = array(), $elseAddQueryString = FALSE, array $elseArgumentsToBeExcludedFromQueryString = array(), $elseUseParentRequest = FALSE, $elseAbsolute = TRUE) {
    if($condition){
  		return parent::render($action, $arguments, $controller, $package, $subpackage, $section, $format, $additionalParams, $addQueryString, $argumentsToBeExcludedFromQueryString, $useParentRequest, $absolute);
    }elseif($elseAction) {
  		return parent::render($elseAction, $elseArguments, $elseController, $elsePackage, $elseSubpackage, $elseSection, $elseFormat, $elseAdditionalParams, $elseAddQueryString, $elseArgumentsToBeExcludedFromQueryString, $elseUseParentRequest, $elseAbsolute);
    } else {
      return $this->renderChildren();
    }
	}

}
