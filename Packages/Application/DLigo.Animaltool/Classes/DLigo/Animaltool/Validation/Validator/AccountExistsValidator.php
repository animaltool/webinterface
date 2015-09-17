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
 * Validator for accounts
 */
class AccountExistsValidator extends \TYPO3\Flow\Validation\Validator\AbstractValidator {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\AccountRepository
	 */
	protected $accountRepository;

	/**
	 * @param mixed $value The value that should be validated
	 * @return void
	 * @throws \TYPO3\Flow\Validation\Exception\InvalidSubjectException
	 */
	protected function isValid($value) {
		if (!is_array($value)) {
			throw new \TYPO3\Flow\Validation\Exception\InvalidSubjectException('The given account identifier was not a string.', 1325155784);
		}
    if(empty($value['new'])) $this->addError('This property is required', 1354192543);

    $account=null;
    if($value['new']!=$value['old']) $account = $this->accountRepository->findByAccountIdentifierAndAuthenticationProviderName($value['new'], 'defaultProvider');

		if ($account!=null) {
			$this->addError('The username is already in use.', 9994);
		}
	}

}
