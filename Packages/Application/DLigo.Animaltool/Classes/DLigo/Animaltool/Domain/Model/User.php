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
class User  extends \TYPO3\Party\Domain\Model\AbstractParty {

	/**
	 * @var string
	 */
	protected $firstname;

	/**
	 * @var string
	 */
	protected $lastname;

	/**
	 * @var string
	 */
	protected $phone;

	/**
	 * @var string
	 */
	protected $street;

	/**
	 * @var string
	 */
	protected $number;

	/**
	 * @var string
	 */
	protected $zipCode;

	/**
	 * @var string
	 */
	protected $city;

	/**
	 * @var string
	 */
	protected $region;

	/**
	 * @var string
	 */
	protected $country;

	/**
	 * @var string
	 * @ORM\Column(type="text")   
	 */
	protected $comment;

	/**
	 * @var integer
   * @ORM\Column(columnDefinition="INT(11) NOT NULL AUTO_INCREMENT") 
   * @Flow\Identity
 	 */
	protected $teamID;
   
  /**
	 * @var \Doctrine\Common\Collections\ArrayCollection<\DLigo\Animaltool\Domain\Model\Location>
   * @ORM\ManyToMany
	 */
	protected $locations;
  
  
	/**
	 * @var integer
	 */   
  protected $lastBoxID=0;
  
	/**
	 * @var Array  
	 * @Flow\Transient
 	 */
  private static $chars;
  
	/**
	 * @Flow\Inject
	 * @var \DLigo\Animaltool\Domain\Repository\LocationRepository
	 */
	protected $locationRepository;

	/**
	 * @return string
	 */
	public function getFirstname() {
		return $this->firstname;
	}

	/**
	 * @param string $firstname
	 * @return void
	 */
	public function setFirstname($firstname) {
		$this->firstname = $firstname;
	}

	/**
	 * @return string
	 */
	public function getLastname() {
		return $this->lastname;
	}

	/**
	 * @param string $lastname
	 * @return void
	 */
	public function setLastname($lastname) {
		$this->lastname = $lastname;
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
	public function getNumber() {
		return $this->number;
	}

	/**
	 * @param string $number
	 * @return void
	 */
	public function setNumber($number) {
		$this->number = $number;
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
  
  public function getRole(){
    return array_keys($this->getAccounts()->first()->getRoles())[0];
  }

  public function getUsername(){
    return $this->getAccounts()->first()->getAccountIdentifier();
  }

  public function __toString() {
    return $this->firstname." ".$this->lastname." (".$this->getTeamId().")";
  }

	/**
	 * @return string
	 */
	public function getTeamID() {
    return self::teamIDString($this->teamID);
	}
	/**
	 * @return string
	 */
	public static function teamIDString($tid) {
    if(empty(self::$chars)) {
      self::$chars=range('A','Z');
    };
    if(empty($tid))return "";
    $i=$tid;
    $id='';
    $l=count(self::$chars);
    while($i>=0){
      $c=($i-1) % $l;
      $id=self::$chars[$c].$id;
      $i=$i-($l+$c);
    };
    return $id;
	}
  
  /**
   * @return integer
   */
   public function getLastBoxID(){
     return $this->lastBoxID;
   }
        
  /**
   *  @param integer $lastBoxID
   */
   public function setLastBoxID($lastBoxID){
     if($lastBoxID>$this->lastBoxID)$this->lastBoxID=$lastBoxID;
   }              

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection<\DLigo\Animaltool\Domain\Model\Location>
	 */
	public function getLocations() {
    if($this->locations==null)$this->locations=new \Doctrine\Common\Collections\ArrayCollection;
		return $this->locations;
	}

	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection<\DLigo\Animaltool\Domain\Model\Location> $locations
	 * @return void
	 */
	public function setLocations(\Doctrine\Common\Collections\ArrayCollection $locations) {
		$this->locations = $locations;
	}

	/**
	 */
	public function getAviableLocations() {
    $locs=$this->getLocations();
    if(empty($locs) || $locs->count()==0) $locs=$this->locationRepository->findAll();
		return $locs;
	}

}