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

/**
 * A controller which allows for logging into the backend
 *
 */
class LoginController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
	 */
	protected $authenticationManager;

	/**
	 * @Flow\Inject
	 * @var \DLigo\Animaltool\Domain\Model\Session
	 */
	protected $session;

	/**
	 * Authenticates an account by invoking the Provider based Authentication Manager.
	 *
	 * On successful authentication redirects to the list of posts, otherwise returns
	 * to the login screen.
	 *
	 * @return void
	 * @throws \TYPO3\Flow\Security\Exception\AuthenticationRequiredException
	 */
	public function authenticateAction() {
		try {
			$this->authenticationManager->authenticate();
      $this->session->start();
			if($this->authenticationManager->getSecurityContext()->hasRole('DLigo.Animaltool:Admin')) { 
         $this->redirect('index', 'Animal');
      } else {
         $this->redirect('select','location');
      }
		} catch (\TYPO3\Flow\Security\Exception\AuthenticationRequiredException $exception) {
			$this->addFlashMessage('Wrong username or password.', '',\TYPO3\Flow\Error\Message::SEVERITY_ERROR,array(),'flash.password');
			throw $exception;
		}
	}

	/**
	 *
	 * @return void
	 */
	public function indexAction() {
		$this->authenticationManager->logout();
    $this->session->clear();
  }

	/**
	 *
	 * @return void
	 */
	public function logoutAction() {
		$this->authenticationManager->logout();
    $this->session->clear();
		$this->addFlashMessage('Successfully logged out.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.logout');
		$this->redirect('index', 'Login');
	}
}  
?>