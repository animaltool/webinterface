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
class Action {

	/**
	 * @var float
	 */
	protected $lat;

	/**
	 * @var float
	 */
	protected $lng;

	/**
	 * @var \DateTime
	 */
	protected $date;
  
	/**
	 * @var \DateTime
   * @ORM\Column(nullable=true)
	 */
	protected $releaseDate;

  /**
   * @var \DLigo\Animaltool\Domain\Model\User
   * @ORM\ManyToOne
   * 
   */
  protected $team;  
  
  /**
   * @var string
   * 
   */
  protected $boxID;                  

	/**
	 * @var \DLigo\Animaltool\Domain\Model\Location
   * @ORM\ManyToOne
	 *    
	 */
	protected $location;

  /**
   * @var \DLigo\Animaltool\Domain\Model\Animal
   * @ORM\ManyToOne(inversedBy="actions")
   * @ORM\Column(nullable=true)
   */
  protected $animal;     
  
	/**
	 * @var \DLigo\Animaltool\Domain\Model\Animal
	 * @Flow\Transient   
	 */
	protected $oldAnimal;

	/**
	 * @return float
	 */
	public function getLat() {
		return $this->lat;
	}

	/**
	 * @param float $lat
	 * @return void
	 */
	public function setLat($lat) {
		$this->lat = $lat;
	}

	/**
	 * @return float
	 */
	public function getLng() {
		return $this->lng;
	}

	/**
	 * @param float $lng
	 * @return void
	 */
	public function setLng($lng) {
		$this->lng = $lng;
	}

	/**
	 * @return string
	 */
	public function getBoxID() {
		return $this->boxID;
	}

	/**
	 * @param string $boxID
	 * @return void
	 */
	public function setBoxID($boxID) {
    $box=explode("-",$boxID);
    if($this->team)$this->team->setLastBoxID($box[1]);
		$this->boxID = $boxID;
	}

	/**
	 * @return \DateTime
	 */
	public function getDate() {
		return $this->date;
	}

	/**
	 * @param \DateTime $date
	 * @return void
	 */
	public function setDate(\DateTime $date) {
		$this->date = $date;
	}

	/**
	 * @return \DateTime
	 */
	public function getReleaseDate() {
		return $this->releaseDate;
	}

	/**
	 * @param \DateTime $date
	 * @return void
	 */
	public function setReleaseDate(\DateTime $date) {
		$this->releaseDate = $date;
	}

	/**
	 * @return \DLigo\Animaltool\Domain\Model\Location
	 */
	public function getLocation() {
		return $this->location;
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Location $location
	 * @return void
	 */
	public function setLocation(\DLigo\Animaltool\Domain\Model\Location $location) {
		$this->location = $location;
	}

	/**
	 * @return \DLigo\Animaltool\Domain\Model\Animal
	 */
	public function getAnimal() {
		return $this->animal;
	}

	/**
	 * @return \DLigo\Animaltool\Domain\Model\Animal
	 */
	public function getOldAnimal() {
		return $this->oldAnimal;
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Animal $animal
	 * @return void
	 */
	public function setAnimal(\DLigo\Animaltool\Domain\Model\Animal $animal = NULL) {
		$this->oldAnimal = $this->animal;
		$this->animal = $animal;
	}

	/**
	 * @return \DLigo\Animaltool\Domain\Model\User
	 */
	public function getTeam() {
		return $this->team;
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\User $team
	 * @return void
	 */
	public function setTeam(\DLigo\Animaltool\Domain\Model\User $team) {
    $box=explode("-",$this->boxID);
    if(isset($box[1]) && $box[1]>0)$team->setLastBoxID($box[1]);
		$this->team = $team;
	}
  
  /**
   * return boolean
   */
  public function getHasGPS(){
    return !(empty($this->lat) && empty($this->lng));
  }     

}