<?php
namespace DLigo\Animaltool\ViewHelpers;

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

use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3\Flow\Error\Result;
use TYPO3\Flow\Reflection\ObjectAccess;


class FormValueViewHelper extends AbstractViewHelper {
	/**
	 * @param $parameter string Form Parameter
	 * @param $alt string alternative value
	 * @return string last submitted value if validation error   
	 */	 	
	public function render($parameter,$alt='') {
  	if ($this->hasMappingErrorOccurred()) {
				$value = $this->getLastSubmittedFormData($parameter);
		} else {
			$value = $this->getPropertyValue($parameter);
      if(empty($value))$value=$alt;
		}
		return $value;
	}

	protected function getRequest() {
		return $this->controllerContext->getRequest();
	}

	protected function hasMappingErrorOccurred() {
		/** @var $validationResults Result */
		$validationResults = $this->getRequest()->getInternalArgument('__submittedArgumentValidationResults');
		return ($validationResults !== NULL && $validationResults->hasErrors());
	}

	protected function getLastSubmittedFormData($parameter) {
		$value = NULL;
		$submittedArguments = $this->getRequest()->getInternalArgument('__submittedArguments');
		if ($submittedArguments !== NULL) {
			$value = ObjectAccess::getPropertyPath($submittedArguments, $parameter);
      if(is_array($value) && count($value)==1 && isset($value['__identity'])) $value=$value['__identity'];
		}
		return $value;
	}

	protected function getPropertyValue($parameter) {
		return ObjectAccess::getPropertyPath($this->templateVariableContainer, $parameter);
	}
}
?>