<?php
namespace DLigo\Animaltool\Controller;

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

class AppController extends \TYPO3\Flow\Mvc\Controller\ActionController {


  protected $supportedMediaTypes = array('application/json');

  /**
	 * @var array
	 */
	protected $viewFormatToObjectNameMap = array('json' => 'TYPO3\Flow\Mvc\View\JsonView');

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Authentication\AuthenticationManagerInterface
	 */
	protected $authenticationManager;
  
  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Domain\Repository\LocationRepository
   */
   protected $locationRepository;        

     /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Domain\Repository\UserRepository
   */
   protected $userRepository;        

  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Domain\Repository\SpeciesRepository
   */
   protected $speciesRepository;        

  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Domain\Repository\ColorRepository
   */
   protected $colorRepository;

  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Domain\Repository\OwnerRepository
   */
   protected $ownerRepository;

  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Domain\Repository\AnimalRepository
   */
   protected $animalRepository;

  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Domain\Repository\ActionRepository
   */
   protected $actionRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Property\PropertyMapper
	 */
	protected $propertyMapper;
  
	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Resource\ResourceManager
	 */
	protected $resourceManager;

	/**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\Inject
	 */
	protected $persistenceManager;

	/**
	 * @return void
	 * @throws \TYPO3\Flow\Security\Exception\AuthenticationRequiredException
	 */
	public function initializeAction() {
			$this->authenticationManager->authenticate();
	}
  

	public function infoAction() {
    $user = $this->authenticationManager->getSecurityContext()->getAccount()->getParty();      
	  $val['user']=$user;
    $val['locations']=$user->getAviableLocations();
    $val['species']=$this->speciesRepository->findAll();
    $val['colors']=$this->colorRepository->findAll();
 		$this->view->assign('value', $val);   
    $this->view->setConfiguration(array(
      "value"=>array(
        "user"=>array(
          "_exposeObjectIdentifier"=>TRUE
        ),
        "locations"=>array(
          "_descendAll"=>array(
            "_exposeObjectIdentifier"=>TRUE
          )
        ),
        "species"=>array(
          "_descendAll"=>array(
            "_exposeObjectIdentifier"=>TRUE,
            "_descend"=>array(
              "breads"=>array(
                "_exposeObjectIdentifier"=>TRUE,
              )
            )            
          )
        ),
        "colors"=>array(
          "_descendAll"=>array(
            "_exposeObjectIdentifier"=>TRUE
          )
        )
      )
    ));
  }

  public function addAction(){
    $currentUser = $this->authenticationManager->getSecurityContext()->getAccount()->getParty();

    $photoInfo=$this->request->getArgument("photo");
    $actionData=$this->request->getArgument("action");
    $actionData=json_decode($actionData,true);
    $animalData=$this->request->getArgument("animal");
    $animalData=json_decode($animalData,true);
    $ownerData=$this->request->getArgument("owner");
    $ownerData=json_decode($ownerData,true);   
    $photoData=$this->request->getArgument("photo");
    $action=$this->propertyMapper->convert($actionData,'DLigo\Animaltool\Domain\Model\Action');
    $action->setDate(new \DateTime('now'));
    $user=$this->propertyMapper->convert($actionData['team'],'DLigo\Animaltool\Domain\Model\User');
    $lastId=$user->getLastBoxID();      
    $action->setTeam($user);
    $box=explode('-',$actionData['boxID']);
    if($lastId>$box[1]) $action->setBoxID($user->getTeamID().'-'.($lastId+1));
    $animal=$this->propertyMapper->convert($animalData,'DLigo\Animaltool\Domain\Model\Animal');
    $birthday=\DateTime::createFromFormat("U",$animalData["birthday"]);
    if(!empty($animalData["birthday"])){
      $birthday=\DateTime::createFromFormat("U",$animalData["birthday"]);
      $birthday->setTime(0,0,0);
      $animal->setBirthday($birthday);
    };
    $action->setAnimal($animal);
    $owner=null;
    if(isset($animalData["isPrivate"]) && $animalData["isPrivate"]){
      $owner=$this->propertyMapper->convert($ownerData,'DLigo\Animaltool\Domain\Model\Owner');
      $animal->setOwner($owner);
      $animal->setEarTag(null);
      if(!empty($animalData["earTag"])) {
        $rfid=$animalData["earTag"];
        $oldAnimal=$this->animalRepository->findOneByRFID($rfid);       
        if($oldAnimal==null) $animal->setRFID($rfid);
      }
    } else {
      if(!empty($animalData["earTag"])){
        $eartag=$animalData["earTag"];
        $oldAnimal=$this->animalRepository->findOneByEarTag($eartag);
        if($oldAnimal!=null) $animal->setEarTag(null);
      };
    };    
    $photo=$this->resourceManager->importUploadedResource($photoInfo);
    //$this->systemLogger->log(\TYPO3\Flow\var_dump($photo,"Photo",true,true),LOG_INFO);
    $animal->setPhoto($photo);
    if($owner) $this->ownerRepository->add($owner);   
    $this->animalRepository->add($animal);
    $this->actionRepository->add($action);
    $this->userRepository->update($user);
    $this->persistenceManager->persistAll();
    $this->response->setStatus(201);
    echo("{".'"lastID": '.$currentUser->getLastBoxID()."}");
    flush();
    ob_flush();
  }
  
  public function animalsAction(\DLigo\Animaltool\Domain\Model\Location $location){
    $animals=$this->animalRepository->findByStayStatus(\DLigo\Animaltool\Domain\Model\Animal::RELEASING);
    $animals=$this->animalRepository->filterLocation($animals,$location);
    $animals=$this->animalRepository->hideDummies($animals);
    $val['animals']=array();
    foreach($animals as $animal){
      $arr=array();
      $arr['animal']=$animal;
      if($animal->getPhoto()) $arr['photo']=base64_encode(file_get_contents('resource://'.$animal->getPhoto()));
      $val['animals'][]=$arr;  
    };
 		$this->view->assign('value', $val);   
    $this->view->setConfiguration(array(
      "value"=>array(
        "animals"=>array(
          "animal"=>array(
            "_exposeObjectIdentifier"=>TRUE,
            "_descend"=>array(
             "owner"=>array(
               "_exposeObjectIdentifier"=>TRUE,
             ),
             "color"=>array(
                "_exposeObjectIdentifier"=>TRUE,
              ),
              "bread"=>array(
                "_exposeObjectIdentifier"=>TRUE,
              ),
              "lastAction"=>array(
                "_exposeObjectIdentifier"=>TRUE,
                "_descend"=>array(
                  "team"=>array(
                    "_exposeObjectIdentifier"=>TRUE,
                  )
                )            
              )
            )
          )
        )
      )
    ));
  }
  
  public function releaseAction(\DLigo\Animaltool\Domain\Model\Animal $animal){
    $animal->setStayStatus(\DLigo\Animaltool\Domain\Model\Animal::RELEASED);
    $this->animalRepository->update($animal);
    $this->response->setStatus(204);
  }
  
}

