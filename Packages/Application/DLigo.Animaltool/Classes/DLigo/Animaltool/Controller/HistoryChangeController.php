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
use DLigo\Animaltool\Domain\Model\HistoryChange;

class HistoryChangeController extends AbstractController {

	/**
	 * @Flow\Inject
	 * @var \DLigo\Animaltool\Domain\Repository\HistoryChangeRepository
	 */
	protected $historyChangeRepository;


	public function initializeAction() {
    if (isset($this->arguments['from'])) {
      $this->arguments['from']
        ->getPropertyMappingConfiguration()
        ->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\DateTimeConverter', \TYPO3\Flow\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'Y-m-d' );
      }    
    if (isset($this->arguments['to'])) {
      $this->arguments['to']
        ->getPropertyMappingConfiguration()
        ->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\DateTimeConverter', \TYPO3\Flow\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'Y-m-d' );
      }    
   }  
    

	/**
	 * @param \DateTime $from
	 * @param \DateTime $to
	 * @return void
	 */
	public function indexAction(\DateTime $from=null,\DateTime $to=null) {
    $history=null;
    if($from)$history=$this->historyChangeRepository->findFrom($from,$to);
		$this->view->assign('from', $from);
		$this->view->assign('to', $to);
		$this->view->assign('history', $history);
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Animal $animal
	 * @param \DateTime $to
	 * @return void
	 */
	public function animalAction(\DLigo\Animaltool\Domain\Model\Animal $animal) {
    $history=$this->historyChangeRepository->findByAnimal($animal);
		$this->view->assign('animal', $animal);
		$this->view->assign('history', $history);
	}
}