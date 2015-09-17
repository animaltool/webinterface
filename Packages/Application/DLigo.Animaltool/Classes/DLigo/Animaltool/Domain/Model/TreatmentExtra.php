<?php
namespace DLigo\Animaltool\Domain\Model;

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
use Doctrine\ORM\Mapping as ORM;

/**
 * @Flow\Entity
 */
class TreatmentExtra {

	/**
	 * @var string
	 */
	protected $value;

  /**
	 * @var \DLigo\Animaltool\Domain\Model\Therapy
   * @ORM\ManyToOne
	 */
	protected $therapy;


	/**
	 * @var \DLigo\Animaltool\Domain\Model\Treatment
   * @ORM\ManyToOne
	 */
	protected $treatment;


	/**
	 * @return \string
	 */
	public function getValue() {
		return $this->value;
	}

	/**
	 * @param string $value
	 * @return void
	 */
	public function setValue($value) {
		$this->value = $value;
	}

	/**
	 * @return \DLigo\Animaltool\Domain\Model\Therapy
	 */
	public function getTherapy() {
		return $this->therapy;
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Therapy $therapy
	 * @return void
	 */
	public function setTherapy(\DLigo\Animaltool\Domain\Model\Therapy $therapy) {
		$this->therapy = $therapy;
	}

	/**
	 * @return \DLigo\Animaltool\Domain\Model\Treatment
	 */
	public function getTreatment() {
		return $this->treatment;
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Treatment $treatment
	 * @return void
	 */
	public function setTreatment(\DLigo\Animaltool\Domain\Model\Treatment $treatment) {
		$this->treatment = $treatment;
	}

	/**
	 * @return \DLigo\Animaltool\Domain\Model\Animal
	 */
	public function getAnimal() {
		$tr=$this->treatment;
    if(!empty($tr)) return $tr->getAnimal();
    return null;
	}
  
  public function __toString() {
    return $this->value;
  }
  
}