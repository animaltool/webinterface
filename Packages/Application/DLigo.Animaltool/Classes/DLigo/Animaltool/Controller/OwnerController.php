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
use DLigo\Animaltool\Domain\Model\Owner;

class OwnerController extends AbstractController {

	/**
	 * @Flow\Inject
	 * @var \DLigo\Animaltool\Domain\Repository\OwnerRepository
	 */
	protected $ownerRepository;

	/**
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('owners', $this->ownerRepository->findAll());
	}

	/**
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Owner $newOwner
	 * @return void
	 */
	public function createAction(Owner $newOwner) {
		$this->ownerRepository->add($newOwner);
		$this->addFlashMessage('Created a new owner.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.owner.new');
		$this->redirect('index');
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Owner $owner
	 * @return void
	 */
	public function editAction(Owner $owner) {
		$this->view->assign('owner', $owner);
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Owner $owner
	 * @return void
	 */
	public function updateAction(Owner $owner) {
		$this->ownerRepository->update($owner);
		$this->addFlashMessage('Updated the owner.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.owner.update');
		$this->redirect('index');
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Owner $owner
	 * @return void
	 */
	public function deleteAction(Owner $owner) {
		$this->ownerRepository->remove($owner);
		$this->addFlashMessage('Deleted a owner.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.owner.delete');
		$this->redirect('index');
	}

}