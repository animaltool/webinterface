<?php
namespace DLigo\Animaltool\Validation\Validator;
 
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

use TYPO3\Flow\Annotations as Flow;
 
/**
*
*/
class PasswordValidator extends \TYPO3\Flow\Validation\Validator\AbstractValidator {
	/**
	 * @var array
	 */
	protected $supportedOptions = array(
		'minimumLength' => array(5, 'The minimal length of the password', 'integer'),
		'allowEmpty' => array(false, 'The password can be empty', 'boolean'),
	);


    /**
     * @param array $value
     * @return void
     */
    protected function isValid($value) {
        $minimumLength = $this->options['minimumLength'];
        $allowEmpty = $this->options['allowEmpty'];
        if($allowEmpty && empty($value[0]) && empty($value[1])) return;
        if(strlen($value[0]) < $minimumLength) {
            $this->addError('The password is to short, minimum lenght is ' . $minimumLength, 9991,array($minimumLength));
        }
        if(strcmp($value[0], $value[1]) != 0) {
            $this->addError('The password do not match!', 9992);
        }
    }
 
}