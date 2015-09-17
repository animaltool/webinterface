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
class Owner {

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")   
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
	protected $iDNumber;

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")   
	 */
	protected $cNP;

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
	 * @var \DLigo\Animaltool\Domain\Model\Animal
	 * @Flow\Transient   
	 */
	protected $animal;

	/**
	 * @Flow\Inject
	 * @var \DLigo\Animaltool\Domain\Repository\AnimalRepository
	 */
	protected $animalRepository;

	/**
	 * @var string
	 * @ORM\Column(type="text")   
	 */
	protected $comment;

	/**
	 * @var string
	 */
	protected $passId;

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
	public function getIDNumber() {
		return $this->iDNumber;
	}

	/**
	 * @param string $iDNumber
	 * @return void
	 */
	public function setIDNumber($iDNumber) {
		$this->iDNumber = $iDNumber;
	}

	/**
	 * @return string
	 */
	public function getCNP() {
		return $this->cNP;
	}

	/**
	 * @param string $cNP
	 * @return void
	 */
	public function setCNP($cNP) {
		$this->cNP = $cNP;
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
    if(empty($this->animal))$this->animal=$this->animalRepository->findByOwner($this)->getFirst();
		return $this->animal;
	}

	/**
	 * @return string
	 */
	public function __toString() {
    $name = ($this->firstName) ? $this->name.', '.$this->firstName : $this->name;
    $animal=$this->getAnimal();
    if(!empty($animal)) $name.=' / '.$animal->getBoxId();
		return $name;
	}

	/**
	 * @return string
	 */
	public function getPassId() {
		return $this->passId;
	}

	/**
	 * @param string $passId
	 * @return void
	 */
	public function setPassId($passId) {
		$this->passId = $passId;
	}
}