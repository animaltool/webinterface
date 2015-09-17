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
use DLigo\Animaltool\Domain\Model\Therapy;

class TherapyController extends ActionController {

	/**
	 * @Flow\Inject
	 * @var \DLigo\Animaltool\Domain\Repository\TherapyRepository
	 */
	protected $therapyRepository;

	/**
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('therapies', $this->therapyRepository->findAll());
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Therapy $therapy
	 * @return void
	 */
	public function showAction(Therapy $therapy) {
		$this->view->assign('therapy', $therapy);
	}

	/**
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Therapy $newTherapy
	 * @return void
	 */
	public function createAction(Therapy $newTherapy) {
		$this->therapyRepository->add($newTherapy);
		$this->addFlashMessage('Created a new therapy.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.therapy.new');
		$this->redirect('index');
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Therapy $therapy
	 * @return void
	 */
	public function editAction(Therapy $therapy) {
		$this->view->assign('therapy', $therapy);
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Therapy $therapy
	 * @return void
	 */
	public function updateAction(Therapy $therapy) {
		$this->therapyRepository->update($therapy);
		$this->addFlashMessage('Updated the therapy.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.therapy.update');
		$this->redirect('index');
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Therapy $therapy
	 * @return void
	 */
	public function deleteAction(Therapy $therapy) {
		$this->therapyRepository->remove($therapy);
		$this->addFlashMessage('Deleted a therapy.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.therapy.delete');
		$this->redirect('index');
	}

}