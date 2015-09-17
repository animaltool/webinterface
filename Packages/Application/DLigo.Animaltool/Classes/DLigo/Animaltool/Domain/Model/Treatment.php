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
class Treatment {

	/**
	 * @var \DLigo\Animaltool\Domain\Model\User
   * @ORM\ManyToOne
	 */
	protected $doctor;

  /**
	 * @var \Doctrine\Common\Collections\ArrayCollection<\DLigo\Animaltool\Domain\Model\Therapy>
   * @ORM\ManyToMany
	 */
	protected $therapies;

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
	 * @var string
	 * @ORM\Column(type="text")   
	 */
	protected $comment;

	/**
	 * @var \DLigo\Animaltool\Domain\Model\Animal
   * @ORM\ManyToOne
	 */
	protected $animal;

	/**
	 * @var \DLigo\Animaltool\Domain\Model\Animal
	 * @Flow\Transient   
	 */
	protected $oldAnimal;

  /**
   * @var \Doctrine\Common\Collections\ArrayCollection<\DLigo\Animaltool\Domain\Model\TreatmentExtra>
   * @ORM\OneToMany(mappedBy="treatment",cascade={"all"},orphanRemoval=true)
   */
  protected $treatmentExtras;       

	/**
	 * @return \DLigo\Animaltool\Domain\Model\User
	 */
	public function getDoctor() {
		return $this->doctor;
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\User $doctor
	 * @return void
	 */
	public function setDoctor(\DLigo\Animaltool\Domain\Model\User $doctor) {
		$this->doctor = $doctor;
	}

	/**
	 * @return \Doctrine\Common\Collections\ArrayCollection<\DLigo\Animaltool\Domain\Model\Therapy>
	 */
	public function getTherapies() {
    if($this->therapies==null)$this->therapies=new \Doctrine\Common\Collections\ArrayCollection;
		return $this->therapies;
	}

	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection<\DLigo\Animaltool\Domain\Model\Therapy> $therapies
	 * @return void
	 */
	public function setTherapies(\Doctrine\Common\Collections\ArrayCollection $therapies) {
		$this->therapies = $therapies;
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
	 * @param \DLigo\Animaltool\Domain\Model\Animal $animal
	 * @return void
	 */
	public function setAnimal(\DLigo\Animaltool\Domain\Model\Animal $animal=null) {
		$this->oldAnimal = $this->animal;
		$this->animal = $animal;
	}

	/**
	 * @return \DLigo\Animaltool\Domain\Model\Animal
	 */
	public function getOldAnimal() {
		return $this->oldAnimal;
	}
  
  public function stopTreatment(){
    if(!$this->endDate)$this->endDate=new \DateTime('now');
  }

	/**
	 * @return \Doctrine\Common\Collections\Collection<\DLigo\Animaltool\Domain\Model\TreatmentExtra>
	 */
	public function getTreatmentExtras() {
    if($this->treatmentExtras==null)$this->treatmentExtras=new \Doctrine\Common\Collections\ArrayCollection;
		return $this->treatmentExtras;
	} 

	/**
	 * @param \Doctrine\Common\Collections\ArrayCollection<\DLigo\Animaltool\Domain\Model\TreatmentExtra> $treatmentExtras
	 * @return void
	 */
	public function setTreatmentExtras(\Doctrine\Common\Collections\ArrayCollection $treatmentExtras) {
		$this->treatmentExtras = $treatmentExtras;
	}

}