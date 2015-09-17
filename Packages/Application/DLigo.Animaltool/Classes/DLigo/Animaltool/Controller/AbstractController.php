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
use DLigo\Animaltool\Domain\Model\Animal;

class AbstractController extends ActionController {

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
	 * @return void
	 * @throws \TYPO3\Flow\Security\Exception\AuthenticationRequiredException
	 */
	public function initializeAction() {
  	$this->authenticationManager->authenticate();
    if($this->session->getLocation()==null && $this->request->getControllerName()!='Login'  && !$this->authenticationManager->getSecurityContext()->hasRole('DLigo.Animaltool:Admin') && $this->request->getControllerActionName()!='select' && $this->request->getControllerName()!='Location')
      $this->redirect('select','location');
    if($this->request->hasArgument('cancel')) {
      if(!($this->request->getControllerActionName()=='merge' && $this->request->getControllerName()=='Animal')){
        $this->redirect('index','animal');
      ;}
    };
    $msgs = $this->flashMessageContainer->getMessagesAndFlush();
    foreach($msgs as $msg) {
      if($msg->getSeverity() == 'Error' && $msg->getTitle()=='' && $msg->getCode()===null) continue;
      $this->flashMessageContainer->addMessage($msg);  
    }
    if (isset($this->arguments['animal'])) {
      $this->arguments['animal']
        ->getPropertyMappingConfiguration()
        ->forProperty('birthday')
        ->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\DateTimeConverter', \TYPO3\Flow\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'Y-m-d' );
      }    
    if (isset($this->arguments['newAnimal'])) {
      $this->arguments['newAnimal']
        ->getPropertyMappingConfiguration()
        ->forProperty('birthday')
        ->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\DateTimeConverter', \TYPO3\Flow\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'Y-m-d' );
      }    
	}
	
  /**
	 * @param \TYPO3\Flow\Mvc\View\ViewInterface $view The view to be initialized
	 * @return void
	 */
	protected function initializeView(\TYPO3\Flow\Mvc\View\ViewInterface $view) {
    parent::initializeView($view);
    $detector=new \TYPO3\Flow\I18n\Detector();
    $locale = $detector->detectLocaleFromHttpHeader($_SERVER["HTTP_ACCEPT_LANGUAGE"]);
    $view->assign('locale', $locale);
    $view->assign('session',$this->session);
  }
 
}