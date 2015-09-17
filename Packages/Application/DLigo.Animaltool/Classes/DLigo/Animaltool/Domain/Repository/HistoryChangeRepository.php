<?php
namespace DLigo\Animaltool\Domain\Repository;

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
use TYPO3\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class HistoryChangeRepository extends Repository {

  /**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\inject
	 */
	protected $persistenceManager;

  protected $defaultOrderings = array('time' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_DESCENDING);

  /**
   * @param \DateTime $from
   * @param \DateTime $to
   */     
	public function findFrom($from,$to=null){
    $from->setTime(0,0,0);
    $query = $this->createQuery();
    if($to){
      $to2=clone $to;
      $to2->modify('+1 day');
      $query->matching(
        $query->logicalAnd(
          $query->greaterThanOrEqual('time', $from),
          $query->lessThan('time', $to2)
        )
      );
    } else {
      $query->matching(
        $query->greaterThanOrEqual('time', $from)
      );
    };
    $query->setOrderings(array('time' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_DESCENDING));
    return $query->execute();                  
  }                            

  /**
	 * @param \DLigo\Animaltool\Domain\Model\Animal $animal
   */     
	public function findByAnimal($animal){
    $id=$this->persistenceManager->getIdentifierByObject($animal);
    $query = $this->createQuery();
    $query->matching(
      $query->equals('animalId', $id)
    )->setOrderings(array('time' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_DESCENDING));
    return $query->execute();                  
    
  }

}