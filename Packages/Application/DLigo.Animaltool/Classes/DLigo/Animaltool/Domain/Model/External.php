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
class External {
  const CLINIC = 1;
  const SHELTER = 2;
  const ADOPTION = 3;

	/**
	 * @var string
	 */
	protected $name;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")      
	 */
	protected $firstName;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")      
	 */
	protected $lastName;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")      
	 */
	protected $phone;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")      
	 */
	protected $street;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")      
	 */
	protected $houseNumber;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")      
	 */
	protected $city;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")      
	 */
	protected $zipCode;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")      
	 */
	protected $region;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")      
	 */
	protected $country;

	/**
	 * @var string
	 * @ORM\Column(type="text")   
	 */
	protected $comment;

	/**
	 * @var \DLigo\Animaltool\Domain\Model\Animal
   * @ORM\ManyToOne(inversedBy="externals")
	 */
	protected $animal;
  
	/**
	 * @var \DLigo\Animaltool\Domain\Model\Animal
	 * @Flow\Transient   
	 */
	protected $oldAnimal;
  
	/**
	 * @var integer
	 * @Flow\Validate(type="NotEmpty")      
	 */
  protected $externalType;
  
	/**
	 * @var \DateTime
	 */
	protected $startDate;

	/**
	 * @var \DateTime
   * @ORM\Column(nullable=true)  
	 */
	protected $endDate;

	/**
	 * @var boolean
	 */
  protected $isPermanent=false;
  
	/**
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return string
	 */
	public function getFirstName() {
		return $this->firstName;
	}

	/**
	 * @param string $firstName
	 * @return void
	 */
	public function setFirstName($firstName) {
		$this->firstName = $firstName;
	}

	/**
	 * @return string
	 */
	public function getLastName() {
		return $this->lastName;
	}

	/**
	 * @param string $lastName
	 * @return void
	 */
	public function setLastName($lastName) {
		$this->lastName = $lastName;
	}

	/**
	 * @return string
	 */
	public function getPhone() {
		return $this->phone;
	}

	/**
	 * @param string $phone
	 * @return void
	 */
	public function setPhone($phone) {
		$this->phone = $phone;
	}

	/**
	 * @return string
	 */
	public function getStreet() {
		return $this->street;
	}

	/**
	 * @param string $street
	 * @return void
	 */
	public function setStreet($street) {
		$this->street = $street;
	}

	/**
	 * @return string
	 */
	public function getHouseNumber() {
		return $this->houseNumber;
	}

	/**
	 * @param string $houseNumber
	 * @return void
	 */
	public function setHouseNumber($houseNumber) {
		$this->houseNumber = $houseNumber;
	}

	/**
	 * @return string
	 */
	public function getCity() {
		return $this->city;
	}

	/**
	 * @param string $city
	 * @return void
	 */
	public function setCity($city) {
		$this->city = $city;
	}

	/**
	 * @return string
	 */
	public function getZipCode() {
		return $this->zipCode;
	}

	/**
	 * @param string $zipCode
	 * @return void
	 */
	public function setZipCode($zipCode) {
		$this->zipCode = $zipCode;
	}

	/**
	 * @return string
	 */
	public function getRegion() {
		return $this->region;
	}

	/**
	 * @param string $region
	 * @return void
	 */
	public function setRegion($region) {
		$this->region = $region;
	}

	/**
	 * @return string
	 */
	public function getCountry() {
		return $this->country;
	}

	/**
	 * @param string $country
	 * @return void
	 */
	public function setCountry($country) {
		$this->country = $country;
	}

	/**
	 * @return string
	 */
	public function getComment() {
		return $this->comment;
	}

	/**
	 * @param string $comment
	 * @return void
	 */
	public function setComment($comment) {
		$this->comment = $comment;
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
	public function setAnimal(\DLigo\Animaltool\Domain\Model\Animal $animal=null) {
		$this->oldAnimal = $this->animal;
		$this->animal = $animal;
	}
  
	/**
	 * @return integer
	 */
	public function getExternalType() {
		return $this->externalType;
	}

	/**
	 * @param integer $externalType
	 * @return void
	 */
	public function setExternalType($externalType) {
		$this->externalType = $externalType;
	}

	/**
	 * @return boolean
	 */
	public function getIsPermanent() {
		return $this->isPermanent;
	}

	/**
	 * @param boolean $isPermanent
	 * @return void
	 */
	public function setIsPermanent($isPermanent=false) {
    if($isPermanent==null) $isPermanent=false;
		$this->isPermanent = $isPermanent;
	}
   
	/**
	 * @return \DateTime
	 */
	public function getStartDate() {
		return $this->startDate;
	}

	/**
	 * @param \DateTime $startDate
	 * @return void
	 */
	public function setStartDate(\DateTime $startDate) {
		$this->startDate = $startDate;
	}

	/**
	 * @return \DateTime
	 */
	public function getEndDate() {
		return $this->endDate;
	}

	/**
	 * @param \DateTime $endDate
	 * @return void
	 */
	public function setEndDate(\DateTime $endDate) {
		$this->endDate = $endDate;
	}

	public function stopExternal() {
		$this->endDate = new \DateTime('now');
	}
	/**
	 * @return string
	 */
	public function __toString() {
    $name = ($this->name) ? $this->name.' ('.$this->lastName.', '.$this->firstName.')' : $this->lastName.', '.$this->firstName;
    if(!empty($this->animal)) $name .= ' / '.$this->animal->getBoxID();
		return $name;
	}

  /**
   * ger Types for radio buttons
   *
   * @return array
   */
  public function getExternalTypes() {
    $etype = array();
    $entries = array(self::CLINIC=>'clinic', self::SHELTER=>'shelter', self::ADOPTION=>'adoption');
    $etypes = array();
    foreach ($entries as $key=>$entry) {
      $etypes[$key] = $this->translator->translateById('external.externalType.'.$entry, array(), NULL, NULL, 'Main', 'DLigo.Animaltool');
    }
    return $etypes;
  }
  
  public function getExternalTypeIds() {
    return array('CLINIC'=>self::CLINIC, 'SHELTER'=>self::SHELTER,'ADOPTION'=>self::ADOPTION);
  } 

}