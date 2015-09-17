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
class TreatmentExtraRepository extends Repository {

  /**
   * @param string $therapy
   * @param \DLigo\Animaltool\Domain\Model\Treatment $treatment  
   */     
	public function findByTherapyAndTreatment($therapy,\DLigo\Animaltool\Domain\Model\Treatment $treatment){
    $query = $this->createQuery();
    $query->matching(
                $query->logicalAnd(
                  $query->equals('therapy', $therapy),
                  $query->equals('treatment', $treatment)
                )
              );
    return $query->execute();                  
  }                            

}