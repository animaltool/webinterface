<?php
namespace DLigo\Animaltool\Controller;

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
use TYPO3\Flow\Mvc\Controller\ActionController;
use DLigo\Animaltool\Domain\Model\User;

class UserController extends AbstractController {

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
	 * @var \DLigo\Animaltool\Domain\Repository\LocationRepository
	 */
	protected $locationRepository;

	/**
	 * @var \TYPO3\Flow\Security\Policy\PolicyService
	 * @Flow\Inject
	 */
	protected $policyService;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\AccountFactory
	 */
	protected $accountFactory;

	/**
	 * @var \TYPO3\Flow\Security\Cryptography\HashService
	 * @Flow\Inject
	 */
	protected $hashService;

	/**
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('users', $this->userRepository->findAll());
	}

	/**
	 * @return void
	 */
	public function newAction() {
  	$this->view->assign('roles', $this->getRoles());
  	$this->view->assign('allLocations', $this->locationRepository->findAll());
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\User $newUser
	 * @param array $username
	 * @Flow\Validate(argumentName="$username", type="notEmpty")   
	 * @param array $password
	 * @param string $role
	 * @Flow\Validate(argumentName="$password", type="\DLigo\Animaltool\Validation\Validator\PasswordValidator")   
	 * @Flow\Validate(argumentName="$newUser", type="UniqueEntity")
	 * @Flow\Validate(argumentName="$username", type="\DLigo\Animaltool\Validation\Validator\AccountExistsValidator")   
	 * @return void
	 */
	public function createAction(User $newUser,$username,$password,$role) {
		$account = $this->accountFactory->createAccountWithPassword($username['new'], $password[0], array($role));
		$accountApp = $this->accountFactory->createAccountWithPassword($username['new'], $password[0], array($role),'AppProvider');
    $account->setParty($newUser);    
    $accountApp->setParty($newUser);    
		$this->accountRepository->add($account);
		$this->accountRepository->add($accountApp);
		$this->userRepository->add($newUser);
		$this->addFlashMessage('Created a new user.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.user.new');
		$this->redirect('index');
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\User $user
	 * @return void
	 */
	public function editAction(User $user) {
 		$this->view->assign('roles', $this->getRoles());
		$this->view->assign('user', $user);
  	$this->view->assign('allLocations', $this->locationRepository->findAll());
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\User $user
	 * @param array $username
	 * @Flow\Validate(argumentName="$username", type="notEmpty")   
	 * @param array $password
	 * @param string $role
	 * @Flow\Validate(argumentName="$password", type="\DLigo\Animaltool\Validation\Validator\PasswordValidator", options={"allowEmpty"=true})   
	 * @Flow\Validate(argumentName="$username", type="\DLigo\Animaltool\Validation\Validator\AccountExistsValidator")   
	 * @return void
	 */
	public function updateAction(User $user,$username,$password=null,$role=null) {
    if($role) {
      $roleObj=$this->policyService->getRole($role);
      foreach($user->getAccounts() as $account){
        $account->setRoles(array($role=>$roleObj));
        $account->setAccountIdentifier($username['new']);
    		$account->setCredentialsSource($this->hashService->hashPassword($password[0], 'default'));
        $this->accountRepository->update($account);
      }
    };
		$this->userRepository->update($user);
		$this->addFlashMessage('Updated the user.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.user.update');
		$this->redirect('index');
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\User $user
	 * @return void
	 */
	public function deleteAction(User $user) {
    foreach($user->getAccounts() as $account){
  		$this->accountRepository->remove($account);
    };
		$this->userRepository->remove($user);
		$this->addFlashMessage('Deleted a user.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.user.delete');
		$this->redirect('index');
	}

  private function getRoles(){
    $roles=array(
      "DLigo.Animaltool:Admin"=>"Admin",
      "DLigo.Animaltool:Doctor"=>"Doctor",
      "DLigo.Animaltool:Catcher"=>"Catcher"
    );
    return $roles;  
  }

}