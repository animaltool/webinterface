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
class Animal {
  const CATCHED = 1;
  const THERAPY = 20;
  const EXTERNAL = 30;
  const RELEASING = 40;
  const RELEASED = 50;
  const ADOPTED = 60;
  const WAIT = 1;
  const OPERATION = 20;
  const READY = 30;
  const DEAD = 40;
  const GOOD = 10;
  const OK = 20;
  const BAD = 30;
  const VERYBAD = 40;
  const UNKNOWN = '';
  const MALE = 'M';
  const FEMALE = 'F';
  const SMALL = 'small';
  const MEDIUM = 'medium';
  const LARGE = 'large';
  
	/**
	 * @var string
   * @Flow\Validate(type="Alphanumeric")   
   * @Flow\Validate(type="Text")
   * @ORM\Column(nullable=true)
	 */
	protected $name='';
  
  /**
   * @var string
   * @ORM\Column(nullable=true)  
   */
  protected $rFID;           

	/**                                         
	 * @var boolean
	 */
	protected $isPrivate;

	/**
	 * @var \DLigo\Animaltool\Domain\Model\Owner
   * @ORM\Column(nullable=true)
   * @ORM\ManyToOne(cascade={"all"})
   */
	protected $owner=null;

	/**
	 * @var string
   * @Flow\Validate(type="Text")
   * @Flow\Validate(type="StringLength", options={ "minimum"=1, "maximum"=1 })
	 * @ORM\Column(type="string", length=1, options={"fixed" = true})   
	 */
	protected $gender;

	/**
	 * @var float
   * @ORM\Column(nullable=true)
	 */
	protected $weight;

	/**
	 * @var \DateTime
	 * @Flow\Validate(type="DateTime", options={"formatType"=\TYPO3\Flow\I18n\Cldr\Reader\DatesReader::FORMAT_TYPE_DATE})   
	 * @Flow\Validate(type="DateTimeRange", options={"latestDate"="now"})    
   * @ORM\Column(nullable=true)
	 */
	protected $birthday;

	/**
	 * @var string
   * @ORM\Column(nullable=true)  
	 */
	protected $earTag;

	/**
	 * @var integer
   * @ORM\Column(nullable=true)
	 */
	protected $therapyStatus=self::WAIT;

	/**
	 * @var \DLigo\Animaltool\Domain\Model\Species
   * @ORM\ManyToOne(cascade={"persist"})
	 */
	protected $species;

	/**
	 * @var \DLigo\Animaltool\Domain\Model\Bread
   * @ORM\ManyToOne(cascade={"persist"})
	 */
	protected $bread;

	/**
	 * @var \DLigo\Animaltool\Domain\Model\Color
   * @ORM\ManyToOne(cascade={"persist"})
	 */
	protected $color;
  
  
	/**
	 * @var string
	 * @ORM\Column(type="text",nullable=true)
	 */
	protected $specialProperties;

	/**
	 * @var integer
   * @ORM\Column(nullable=true)
	 */
	protected $healthCondition;

	/**
	 * @var integer
   * @ORM\Column(nullable=true)
	 */
	protected $stayStatus=self::CATCHED;

	/**
	 * @var string
	 * @ORM\Column(type="text")   
	 */
	protected $comment;
  
  /**
   * @var \Doctrine\Common\Collections\ArrayCollection<\DLigo\Animaltool\Domain\Model\Action>
   * @ORM\OneToMany(mappedBy="animal",cascade={"all"},orphanRemoval=true)
   * @ORM\OrderBy({"date" = "DESC"})   
   */
  protected $actions;
  
	/**
	 * @ORM\ManyToOne(cascade={"persist"})
	 * @var \TYPO3\Flow\Resource\Resource
   * @ORM\Column(nullable=true)
	 */
	protected $photo;   

	/**
	 * @var \DLigo\Animaltool\Domain\Model\Location
   * @ORM\ManyToOne
	 */
	protected $location;
  
  /**
   * @var \Doctrine\Common\Collections\ArrayCollection<\DLigo\Animaltool\Domain\Model\Treatment>
   * @ORM\OneToMany(mappedBy="animal",cascade={"all"},orphanRemoval=true)
   * @ORM\OrderBy({"startDate" = "DESC"})   
   */
  protected $treatments;       
  
  /**
   * @var boolean
   */
  protected $isDummy = false;        
    
 	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject   
	 */
	protected $translator;
  
  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Domain\Repository\TherapyRepository
   */
   protected $therapyRepository;

  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Domain\Repository\TreatmentExtraRepository
   */
   protected $treatmentExtraRepository;
    
	/**
	 * @var \Doctrine\Common\Collections\ArrayCollection<\DLigo\Animaltool\Domain\Model\External>
   * @ORM\OneToMany(mappedBy="animal",cascade={"all"},orphanRemoval=true)
   * @ORM\Column(nullable=true)
   * @ORM\OrderBy({"startDate" = "DESC"})   
	 */
	protected $externals;

  public function initializeObject(){
    $this->action=new \Doctrine\Common\Collections\ArrayCollection(); 
  }

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
	public function getRFID() {
		return $this->rFID;
	}

	/**
	 * @param string $rFID
	 * @return void
	 */
	public function setRFID($rFID) {
		$this->rFID = $rFID;
	}

	/**
	 * @return boolean
	 */
	public function getIsPrivate() {
		return $this->isPrivate;
	}

	/**
	 * @param boolean $isPrivate
	 * @return void
	 */
	public function setIsPrivate($isPrivate) {
		$this->isPrivate = $isPrivate;
	}

	/**
	 * @return \DLigo\Animaltool\Domain\Model\Owner
	 */
	public function getOwner() {
		return $this->owner;
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Owner $owner
	 * @return void
	 */
	public function setOwner(\DLigo\Animaltool\Domain\Model\Owner $owner = NULL) {
		$this->owner = $owner;
	}

	/**
	 * @return string
	 */
	public function getGender() {
		return $this->gender;
	}

	/**
	 * @param string $gender
	 * @return void
	 */
	public function setGender($gender) {
		$this->gender = strtoupper($gender);
	}

	/**
	 * @return float
	 */
	public function getWeight() {
		return $this->weight;
	}

	/**
	 * @param float $weight
	 * @return void
	 */
	public function setWeight($weight) {
		$this->weight = $weight;
	}

	/**
	 * @return \DateTime
	 */
	public function getBirthday() {
		return $this->birthday;
	}

	/**
	 * @param \DateTime $birthday
	 * @return void
	 */
	public function setBirthday($birthday) {
		$this->birthday = $birthday;
	}

	/**
	 * @return string
	 */
	public function getEarTag() {
		return $this->earTag;
	}

	/**
	 * @param string $earTag
	 * @return void
	 */
	public function setEarTag($earTag) {
		$this->earTag = $earTag;
	}

	/**
	 * @return integer
	 */
	public function getTherapyStatus() {
		return $this->therapyStatus;
	}

	/**
	 * @param integer $therapyStatus
	 * @return void
	 */
	public function setTherapyStatus($therapyStatus) {
		$this->therapyStatus = $therapyStatus;
	}

	/**
	 * @return integer
	 */
	public function getStayStatus() {
		return $this->stayStatus;
	}

	/**
	 * @param integer $stayStatus
	 * @return void
	 */
	public function setStayStatus($stayStatus) {
		$this->stayStatus = $stayStatus;
	}

	/**
	 * @return \DLigo\Animaltool\Domain\Model\Species
	 */
	public function getSpecies() {
		return $this->species;
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Species $species
	 * @return void
	 */
	public function setSpecies(\DLigo\Animaltool\Domain\Model\Species $species) {
		$this->species = $species;
	}

	/**
	 * @return \DLigo\Animaltool\Domain\Model\Bread
	 */
	public function getBread() {
		return $this->bread;
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Bread $bread
	 * @return void
	 */
	public function setBread(\DLigo\Animaltool\Domain\Model\Bread $bread) {
		$this->bread = $bread;
	}

	/**
	 * @return \DLigo\Animaltool\Domain\Model\Color
	 */
	public function getColor() {
		return $this->color;
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Color $color
	 * @return void
	 */
	public function setColor(\DLigo\Animaltool\Domain\Model\Color $color) {
		$this->color = $color;
	}

	/**
	 * @return string
	 */
	public function getSpecialProperties() {
		return $this->specialProperties;
	}

	/**
	 * @param string $specialProperties
	 * @return void
	 */
	public function setSpecialProperties($specialProperties) {
		$this->specialProperties = $specialProperties;
	}

	/**
	 * @return integer
	 */
	public function getHealthCondition() {
		return $this->healthCondition;
	}

	/**
	 * @param integer $healthCondition
	 * @return void
	 */
	public function setHealthCondition($condition) {
		$this->healthCondition = $condition;
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
   * @return \TYPO3\Flow\Resource\Resource
   * 
   */
  public function getPhoto(){
    return $this->photo;
  }
   
  /**
   * @param \TYPO3\Flow\Resource\Resource $photo
   * return void
   */
  public function setPhoto(\TYPO3\Flow\Resource\Resource $photo=null){
    if($photo && $this->photo && $photo->getResourcePointer()==$this->photo->getResourcePointer()) return;
    $this->photo=$photo;
  }           
                
  /**
   * return \Doctrine\Common\Collections\ArrayCollection<\DLigo\Animaltool\Domain\Model\Therapy>
   */      
  public function getTherapies(){
    $ret = new \Doctrine\Common\Collections\ArrayCollection;
    foreach($this->treatments as $treatment){
      foreach($treatment->getTherapies() as $therapy){
        $ret[$therapy->getName()]=$therapy;
      }
    }
    return $ret;
  }

	/**
	 * @return boolean
	 */
	public function getIsDummy() {
    if($this->isDummy==null)$this->isDummy=false;
		return $this->isDummy;
	}

	/**
	 * @param boolean $isDummy
	 * @return void
	 */
	public function setIsDummy($isDummy) {
		$this->isDummy = ($isDummy===null)?false:$isDummy;
	}

	/**
	 * @return \Doctrine\Common\Collections\Collection<\DLigo\Animaltool\Domain\Model\Actions>
	 */
	public function getActions() {
    if($this->actions==null)$this->actions=new \Doctrine\Common\Collections\ArrayCollection;
		return $this->actions;
	} 
  
	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection<\DLigo\Animaltool\Domain\Model\Action> $actions
	 * @return void
	 */
	public function setActions(\Doctrine\Common\Collections\ArrayCollection $actions) {
		$this->actions = $actions;
	}
  
	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection<\DLigo\Animaltool\Domain\Model\External>
	 */
	public function getExternals() {
    if($this->externals==null)$this->externals=new \Doctrine\Common\Collections\ArrayCollection;
		return $this->externals;
	}

	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection<\DLigo\Animaltool\Domain\Model\External> $externals
	 * @return void
	 */
	public function setExternals(\Doctrine\Common\Collections\ArrayCollection $externals) {
		$this->externals = $externals;
	} 
  
	/**
	 * @return \DLigo\Animaltool\Domain\Model\External
	 */
	public function getOpenExternal() {
    $last=$this->getExternals()->first();
		if($last!=null && $last->getEndDate()==null) return $last;
    return null;
	}

  /**  
   * @return \Doctrine\Common\Collections\ArrayCollection<\DLigo\Animaltool\Domain\Model\External>
   */   
  public function getOpenExternals(){
    $ret=new \Doctrine\Common\Collections\ArrayCollection;
    foreach($this->externals as $e){
      if($e->getEndDate()==null){
        $ret->add($e);
      };
    }
    return $ret;
  }
  
  public function stopOpenExternals(){
    foreach($this->externals as $e){
      if($e->getEndDate()==null) $e->stopExternal();
    }
  }

	/**
	 * @return \Doctrine\Common\Collections\Collection<\DLigo\Animaltool\Domain\Model\External>
	 */
	public function getPreviousExternals() {
    $es=clone $this->getExternals();
    $open=$this->getOpenExternal();
    if($open!=null){
      if($es->contains($open)) $es->removeElement($open);
    };
		return $es;
	} 

  /**
   *  return string
   */
  public function getBoxID(){
    $action=$this->getActions()->first();
    if($action) return $action->getBoxID();
    return "";
  }        

  public function getLastAction(){
    $action=$this->getActions()->first();
    return $action;
  }        
  
  /**
   *  return string
   */
  public function getIsTreatable(){
    return $this->therapyStatus==self::WAIT || $this->therapyStatus==self::OPERATION || $this->stayStatus==self::EXTERNAL; 
  }  
  
  /**
   *  return string
   */
  public function getIsDoneable(){
    return $this->stayStatus==self::RELEASING || $this->stayStatus==self::EXTERNAL; 
  }  

  public function __toString(){
    $ret=$this->getBoxID();
    $s=$this->getEarTag();
    $ret.=($ret&&$s?"/":"").$s;
    $s=$this->getName();
    $ret.=($ret&&$s?"/":"").$s;
    return $ret;
  }
  
  public function getLabel(){
    return $this->__toString();
  }
    
	/**
	 * @return \Doctrine\Common\Collections\Collection<\DLigo\Animaltool\Domain\Model\Treatment>
	 */
	public function getTreatments() {
    if($this->treatments==null)$this->treatments=new \Doctrine\Common\Collections\ArrayCollection;
		return $this->treatments;
	} 

	/**
	 * @return \Doctrine\Common\Collections\Collection<\DLigo\Animaltool\Domain\Model\Treatment>
	 */
	public function getPreviousTreatments() {
    $trs=clone $this->getTreatments();
    $open=$this->getOpenTreatment();
    if($open!=null){
      if($trs->contains($open)) $trs->removeElement($open);
    };
		return $trs;
	} 

	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection<\DLigo\Animaltool\Domain\Model\Treatment> $treatments
	 * @return void
	 */
	public function setTreatments(\Doctrine\Common\Collections\ArrayCollection $treatments) {
		$this->treatments = $treatments;
	}

  /**  
   * @return \DLigo\Animaltool\Domain\Model\Treatment
   */   
  public function getOpenTreatment(){
    foreach($this->treatments as $tr){
      if($tr->getEndDate()==null){
        return $tr;
      };
    }
    return null;
  }
  /**  
   * @return \Doctrine\Common\Collections\ArrayCollection<\DLigo\Animaltool\Domain\Model\Treatment>
   */   
  public function getOpenTreatments(){
    $ret=new \Doctrine\Common\Collections\ArrayCollection;
    foreach($this->treatments as $tr){
      if($tr->getEndDate()==null){
        $ret->add($tr);
      };
    }
    return $ret;
  }
  
  public function stopOpenTreatments(){
    foreach($this->treatments as $tr){
      $tth=$tr->getTherapies();
      if($tth!=null && $tth->count()==0)$tth=null;
      $tc=$tr->getComment();
      if(empty($tth) && empty($tc)){
        $tr->setAnimal(null);
        $this->treatments->removeElement($tr);
      } else {
        if($tr->getEndDate()==null){
          $tr->stopTreatment();
        };
      }
    }
  }

  /**
   * get therapyStatus for select box
   *
   * @return array
   */
  public function getTherapyStatuses() {
    $stats = array();
    $entries = array(self::WAIT=>'wait', self::OPERATION=>'inOperation', self::READY=>'ready', self::DEAD=>'dead');
    $stats = array();
    foreach ($entries as $key=>$entry) {
      $stats[$key] = $this->translator->translateById('animal.therapyStatus.'.$entry, array(), NULL, NULL, 'Main', 'DLigo.Animaltool');
    }
    return $stats;
  }
  
  public function getTherapyStatusIds() {
    return array('WAIT'=>self::WAIT, 'OPERATION'=>self::OPERATION,'READY'=>self::READY, 'DEAD'=>self::DEAD);
  } 

  /**
   * get stayStatus for select box
   *
   * @return array
   */
  public function getStayStatuses() {
    $stats = array();
    $entries = array(self::CATCHED=>'catched', self::THERAPY=>'inTherapy',self::EXTERNAL=>'external', self::RELEASING=>'releasing', self::RELEASED=>'released', self::ADOPTED=>'adopted');
    $stats = array();
    foreach ($entries as $key=>$entry) {
      $stats[$key] = $this->translator->translateById('animal.stayStatus.'.$entry, array(), NULL, NULL, 'Main', 'DLigo.Animaltool');
    }
    return $stats;
  } 

  public function getStayStatusIds() {
    return array('CATCHED'=>self::CATCHED, 'THERAPY'=>self::THERAPY,'EXTERNAL'=>self::EXTERNAL, 'RELEASYING'=>self::RELEASING, 'RELEASED'=>self::RELEASED,'ADOPTED'=>self::ADOPTED);
  } 

  /**
   * get healthConditions for select box
   *
   * @return array
   */
  public function getHealthConditions() {
    $conds = array();
    $entries = array(self::GOOD=>'good', self::OK=>'ok', self::BAD=>'bad', self::VERYBAD=>'veryBad');
    $conds = array();
    foreach ($entries as $key=>$entry) {
      $conds[$key] = $this->translator->translateById('animal.healthCondition.'.$entry, array(), NULL, NULL, 'Main', 'DLigo.Animaltool');
    }
    return $conds;
  } 

  /**
   * get genders for select box
   *
   * @return array
   */
  public function getGenders() {
    $conds = array();
    $entries = array(self::UNKNOWN=>'', self::MALE=>'m', self::FEMALE=>'f');
    $conds = array();
    foreach ($entries as $key=>$entry) {
      $conds[$key] = trim($this->translator->translateById('animal.gender.'.$entry, array(), NULL, NULL, 'Main', 'DLigo.Animaltool'));
    }
    return $conds;
  } 
  
  /**
   * get size (translated)
   * 
   * @return string
   */
  public function getSize(){
    if(empty($this->weight)) return '';
    $size=self::LARGE;
    if($this->weight<=10) $size=self::SMALL; elseif($this->weight<=30)$size=self::MEDIUM;
    return $this->translator->translateById('animal.size.'.$size, array(), NULL, NULL, 'Main', 'DLigo.Animaltool'); 
  }           
  
}