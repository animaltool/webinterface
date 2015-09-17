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

/**
 * @Flow\Scope("session")
 */
class Session {

  /**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\inject
	 */
	protected $persistenceManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
	 */
	protected $authenticationManager;


	/**
	 * @var \DLigo\Animaltool\Domain\Model\Location
	 */
  protected $location=null;
  
	/**
	 * @var \DLigo\Animaltool\Domain\Model\User
	 */
  protected $user=null;
  
  /**
   * @var string
   */
  protected $filter='new';     

  /**
   * @Flow\Session(autoStart = TRUE)
   */
  public function setLocation(\DLigo\Animaltool\Domain\Model\Location $location=null){
    $this->location=$location;
  }

  /**
   * @Flow\Session(autoStart = TRUE)
   */
  public function start(){
    $this->clear();
  }

  /**
   */
  public function clear(){
    $this->location=null;
    $this->user=null;
  }

  /**
   * @return \DLigo\Animaltool\Domain\Model\Location
   */     
  public function getLocation(){
    return $this->location;
  }

  /**
   * @return string
   */     
  public function getLocationId(){
    if($this->location==null) return null;
    return $this->persistenceManager->getIdentifierByObject($this->location);
  }
  
  /**
   * @return \DLigo\Animaltool\Domain\Model\User
   */     
  public function getUser(){
    if(!$this->user) $this->user=$this->authenticationManager->getSecurityContext()->getAccount()->getParty();
    return $this->user;
  }
  
  /**
   * @Flow\Session(autoStart = TRUE)
   */
  public function setFilter($filter){
    $this->filter=$filter;
  }
  
  /**
   * @return string
   */     
  public function getFilter(){
    return $this->filter;
  }
  
}