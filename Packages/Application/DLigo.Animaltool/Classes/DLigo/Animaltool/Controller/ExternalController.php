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
use DLigo\Animaltool\Domain\Model\External;
use DLigo\Animaltool\Domain\Model\Animal;

class ExternalController extends AbstractController {

	/**
	 * @Flow\Inject
	 * @var \DLigo\Animaltool\Domain\Repository\ExternalRepository
	 */
	protected $externalRepository;

	/**
	 * @Flow\Inject
	 * @var \DLigo\Animaltool\Domain\Repository\AnimalRepository
	 */
	protected $animalRepository;

  /**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\inject
	 */
	protected $persistenceManager;

	/**
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('externals', $this->externalRepository->findAll());
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\External $external
	 * @return void
	 */
	public function editAction(External $external) {
		$this->view->assign('external', $external);
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\External $external
	 * @return void
	 */
	public function updateAction(External $external) {
    if($external->getExternalType()==External::SHELTER) $external->setIsPermanent(false);
    if($external->getExternalType()==External::ADOPTION) $external->setIsPermanent(true);
    if($external->getIsPermanent()) $external->getAnimal()->setStayStatus(\DLigo\Animaltool\Domain\Model\Animal::ADOPTED);
		$this->externalRepository->update($external);
		$this->addFlashMessage('Updated the clinic/shelter/adoption.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.external.update');
		$this->redirect('index');
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\External $external
	 * @return void
	 */
	public function deleteAction(External $external) {
		$this->externalRepository->remove($external);
		$this->addFlashMessage('Deleted a clinic/shelter/adoption.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.external.delete');
		$this->redirect('index');
	}
  
	/**
	 * @param \DLigo\Animaltool\Domain\Model\Animal $animal
	 * @return void
	 */
	public function openAction(\DLigo\Animaltool\Domain\Model\Animal $animal) {
    $external=$animal->getOpenExternal();
    if(empty($external)) {
      $external=new \DLigo\Animaltool\Domain\Model\External;
    };    
		$this->view->assign('external', $external);
		$this->view->assign('animal', $animal);
	}
  
	/**
	 * @param \DLigo\Animaltool\Domain\Model\External $external
	 * @param \DLigo\Animaltool\Domain\Model\Animal $animal
	 * @return void
	 */
	public function sendAction(External $external,\DLigo\Animaltool\Domain\Model\Animal $animal) {
    if($external->getExternalType()==External::CLINIC) $external->setIsPermanent(false);
    if($external->getExternalType()==External::ADOPTION) $external->setIsPermanent(true);
    $animal->setStayStatus(Animal::EXTERNAL);
    $animal->setTherapyStatus(Animal::READY);
    if($external->getIsPermanent()){
      $animal->setStayStatus(\DLigo\Animaltool\Domain\Model\Animal::ADOPTED);
      $external->stopExternal();
    }
    $external->setAnimal($animal);
    $start=$external->getStartDate();
    if(empty($start))$external->setStartDate(new \DateTime('now')); 
    $animal->getExternals()->add($external);
    $this->animalRepository->update($animal);
    //$this->externalRepository->update($external);
    
		$this->addFlashMessage('Send to clinic/shelter/adoption.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.external.send');
		$this->redirect('index','Animal');
	}

}