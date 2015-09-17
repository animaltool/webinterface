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
class Species { 

	/**
	 * @var string
	 * @Flow\Identity   
	 */
	protected $name;
  
  /**
   * @var \Doctrine\Common\Collections\Collection<\DLigo\Animaltool\Domain\Model\Bread>
   * @ORM\OneToMany(mappedBy="species",cascade={"all"}, orphanRemoval=true)
   */
  protected $breads;
  
  /**
   * @var boolean
   */
  protected $useTag;     

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
	 * @return \Doctrine\Common\Collections\Collection<\DLigo\Animaltool\Domain\Model\Bread>
	 */
	public function getBreads() {
    if(empty($this->breads))$this->breads=new \Doctrine\Common\Collections\ArrayCollection; 
		return $this->breads;
	} 

	/**
	 * @param \Doctrine\Common\Collections\Collection<\DLigo\Animaltool\Domain\Model\Bread> $breads
	 */
	public function setBreads(\Doctrine\Common\Collections\Collection $breads) {
		return $this->breads=$breads;
	}
  
	/**
	 * @return string
	 */
	public function getBreadNames() {
		$ret="";
    foreach($this->breads as $bread)
      $ret=(empty($ret) ? "" :$ret."\n").$bread->getName();
    return $ret;
	}
  
  /**
   * @param boolean $useTag
   */     
  public function setUseTag($useTag){
    $this->useTag=$useTag;
  } 
  
  /**
   * @return boolean
   */
  public function getUseTag(){
    return $this->useTag;
  }     

  public function __toString(){
    return $this->name;
  }

}
