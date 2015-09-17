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
class HistoryChange {

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")   
	 */
	protected $objectType;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")   
	 */
	protected $id;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")   
	 */
	protected $action;

	/**
	 * @var string
	 * @ORM\Column(type="text")   
	 */
	protected $properties;

	/**
	 * @var string
   * @ORM\Column(nullable=true)
	 */
	protected $animalId;

	/**
	 * @var string
   * @ORM\Column(nullable=true)
	 */
	protected $animalLabel;

	/**
	 * @var string
   * @ORM\Column(nullable=true)
	 */
	protected $byId;

	/**
	 * @var string
   * @ORM\Column(nullable=true)
	 */
	protected $byLabel;

	/**
	 * @var \DateTime
	 * @Flow\Validate(type="NotEmpty")   
	 */
	protected $time;


	/**
	 * @return string
	 */
	public function getObjectType() {
		return $this->objectType;
	}

	/**
	 * @param string $objectType
	 * @return void
	 */
	public function setObjectType($objectType) {
		$this->objectType = $objectType;
	}

	/**
	 * @return string
	 */
	public function getId() {
		return $this->id;
	}

	/**
	 * @param string $id
	 * @return void
	 */
	public function setId($id) {
		$this->id = $id;
	}

	/**
	 * @return string
	 */
	public function getAction() {
		return $this->action;
	}

	/**
	 * @param string $action
	 * @return void
	 */
	public function setAction($action) {
		$this->action = $action;
	}

	/**
	 * @return string
	 */
	public function getProperties() {
		return $this->properties;
	}

	/**
	 * @param string $properties
	 * @return void
	 */
	public function setProperties($properties) {
		$this->properties = $properties;
	}

	/**
	 * @return string
	 */
	public function getAnimalId() {
		return $this->animalId;
	}

	/**
	 * @param string $animalId
	 * @return void
	 */
	public function setAnimalId($animalId) {
		$this->animalId = $animalId;
	}

	/**
	 * @return string
	 */
	public function getAnimalLabel() {
		return $this->animalLabel;
	}

	/**
	 * @param string $animalLabel
	 * @return void
	 */
	public function setAnimalLabel($animalLabel) {
		$this->animalLabel = $animalLabel;
	}

	/**
	 * @return string
	 */
	public function getById() {
		return $this->byId;
	}

	/**
	 * @param string $byId
	 * @return void
	 */
	public function setById($byId) {
		$this->byId = $byId;
	}

	/**
	 * @return string
	 */
	public function getByLabel() {
		return $this->byLabel;
	}

	/**
	 * @param string $byLabel
	 * @return void
	 */
	public function setByLabel($byLabel) {
		$this->byLabel = $byLabel;
	}

	/**
	 * @return string
	 */
	public function getTime() {
    if($this->time==null)$this->time=new \DateTime('now');
		return $this->time;
	}

	/**
	 * @param string $time
	 * @return void
	 */
	public function setTime($time) {
    if(empty($time))$time=new \DateTime('now');
		$this->time = $time;
	}
}