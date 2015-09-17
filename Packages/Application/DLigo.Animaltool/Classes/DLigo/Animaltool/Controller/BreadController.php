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

class BreadController extends AbstractController {

	/**
	 * @Flow\Inject
	 * @var \DLigo\Animaltool\Domain\Repository\SpeciesRepository
	 */
	protected $speciesRepository;

	/**
	 * @Flow\Inject
	 * @var \DLigo\Animaltool\Domain\Repository\BreadRepository
	 */
	protected $breadRepository;

	/**
	 * @return void
	 */
	public function indexAction() {
		$this->view->assign('speciess', $this->speciesRepository->findAll());
	}

	/**
	 * @return void
	 */
	public function newAction() {
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Species $newSpecies
	 * @param string $breads   
	 * @return void
	 */
	public function createAction(\DLigo\Animaltool\Domain\Model\Species $newSpecies,$breads) {
    $this->replaceBreads($newSpecies,$breads);
		$this->speciesRepository->add($newSpecies);
		$this->addFlashMessage('Created a new species.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.species.new');
		$this->redirect('index');
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Species $species
	 * @return void
	 */
	public function editAction(\DLigo\Animaltool\Domain\Model\Species $species) {
		$this->view->assign('species', $species);
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Species $species
	 * @param string $breads   
	 * @return void
	 */
	public function updateAction(\DLigo\Animaltool\Domain\Model\Species $species,$breads) {
    $this->replaceBreads($species,$breads);
		$this->speciesRepository->update($species);
		$this->addFlashMessage('Updated the species.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.species.update');
		$this->redirect('index');
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Species $species
	 * @return void
	 */
	public function deleteAction(\DLigo\Animaltool\Domain\Model\Species $species) {
		$this->speciesRepository->remove($species);
		$this->addFlashMessage('Deleted a species.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.species.delete');
		$this->redirect('index');
	}
  
  /**
   * Set Breads fron String
   *
	 * @param \DLigo\Animaltool\Domain\Model\Species $species
   * @param string $breads
   * @return void
   */
  private function replaceBreads($species,$breads) {
    $newBreads=new \Doctrine\Common\Collections\ArrayCollection;
    $old=clone $species->getBreads();
    foreach(explode("\n",$breads) as $breadname){
      $breadname=trim($breadname);
      if(empty($breadname)) continue;
      $bread=null;
      $bread=$this->breadRepository->findOneByName($breadname);
      if(empty($bread)){
        $bread=new \DLigo\Animaltool\Domain\Model\Bread();
        $bread->setName($breadname);
        $bread->setSpecies($species);
        $this->breadRepository->add($bread);
      };
      if($old) $old->removeElement($bread);
      $newBreads->add($bread);
    }   
    $species->setBreads($newBreads);
    foreach($old as $bread){
      try {
        $bread->setSpecies(null);
        $this->breadRepository->remove($bread);
      }catch(Exception $e){
        $bread->setSpecies($pecies);
      }
    }
  }
  

}