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
use DLigo\Animaltool\Domain\Model\Location;

class LocationController extends AbstractController {

	/**
	 * @Flow\Inject
	 * @var \DLigo\Animaltool\Domain\Repository\LocationRepository
	 */
	protected $locationRepository;

	/**
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('locations', $this->locationRepository->findAll());
	}

	/**
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Location $newLocation
	 * @return void
	 */
	public function createAction(Location $newLocation) {
		$this->locationRepository->add($newLocation);
		$this->addFlashMessage('Created a new location.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.location.new');
		$this->redirect('index');
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Location $location
	 * @return void
	 */
	public function editAction(Location $location) {
		$this->view->assign('location', $location);
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Location $location
	 * @return void
	 */
	public function updateAction(Location $location) {
		$this->locationRepository->update($location);
		$this->addFlashMessage('Updated the location.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.location.update');
		$this->redirect('index');
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Location $location
	 * @return void
	 */
	public function deleteAction(Location $location) {
		$this->locationRepository->remove($location);
		$this->addFlashMessage('Deleted a location.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.location.delete');
		$this->redirect('index');
	}

  public function selectAction() {
		$this->view->assign('session', $this->session);
  }

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Location $location
	 * @return void
	 */
  public function selectedAction(Location $location=null) {
    $this->session->setLocation($location);
  	$this->redirect('index', 'Animal');
  }

}