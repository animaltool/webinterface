<?php
namespace DLigo\Animaltool\Command;

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
 * The setup controller for the Blog package, for setting up some
 * data to play with.
 *
 * @Flow\Scope("singleton")
 */
class SetupCommandController extends \TYPO3\Flow\Cli\CommandController {


	/**
	 * @Flow\Inject
	 * @var \DLigo\Animaltool\Domain\Repository\UserRepository
	 */
	protected $userRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\AccountRepository
	 */
	protected $accountRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\AccountFactory
	 */
	protected $accountFactory;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
	 */
	protected $authenticationManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Policy\PolicyService
	 */
	protected $policyService;

	/**
	 * Create an admin account
	 *
	 * Creates a new account which has admin rights.
	 *
	 * @param string $identifier Account identifier, aka "user name"
	 * @param string $password Plain text password for the new account
	 * @param string $firstName First name of the account's holder
	 * @param string $lastName Last name of the account's holder
	 * @return void
	 */
	public function createAccountCommand($identifier, $password, $firstName, $lastName) {
    $this->initRoles();
    
		$account = $this->accountFactory->createAccountWithPassword($identifier, $password, array('DLigo.Animaltool:Admin'));
		$accountApp = $this->accountFactory->createAccountWithPassword($identifier, $password, array('DLigo.Animaltool:Admin'),'AppProvider');

		$user = new \DLigo\Animaltool\Domain\Model\User();
		$user->setFirstname($firstName);
		$user->setLastname($lastName);
    $user->setPhone('');
    $user->setStreet('');
    $user->setNumber('');
    $user->setZipCode('');
    $user->setCity('');
    $user->setRegion('');
    $user->setCountry('');
    $user->setComment('');
    $user->setLastBoxID(0);
		$this->userRepository->add($user);
    $account->setParty($user);    
    $accountApp->setParty($user);    
		$this->accountRepository->add($account);
		$this->accountRepository->add($accountApp);

		$this->outputLine('The account "%s" was created.', array($identifier));
	}
  
  private function initRoles(){
    if(!$this->policyService->hasRole('DLigo.Animaltool:Admin')) $this->policyService->createRole('DLigo.Animaltool:Admin');
    if(!$this->policyService->hasRole('DLigo.Animaltool:Catcher')) $this->policyService->createRole('DLigo.Animaltool:Catcher');
    if(!$this->policyService->hasRole('DLigo.Animaltool:Doctor')) $this->policyService->createRole('DLigo.Animaltool:Doctor');
  }

}
?>