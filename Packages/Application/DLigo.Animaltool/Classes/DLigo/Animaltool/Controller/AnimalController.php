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
use TYPO3\Flow\Mvc\Controller\ActionController;
use DLigo\Animaltool\Domain\Model\Animal;

class AnimalController extends AbstractController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Log\SystemLoggerInterface
	 */
	protected $systemLogger;

	/**
	 * @Flow\Inject
	 * @var \DLigo\Animaltool\Domain\Repository\AnimalRepository
	 */
	protected $animalRepository;

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
   * @var DLigo\Animaltool\Domain\Repository\BreadRepository
   */
   protected $breadRepository;

  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Domain\Repository\TherapyRepository
   */
   protected $therapyRepository;

  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Domain\Repository\TreatmentRepository
   */
   protected $treatmentRepository;

  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Domain\Repository\TreatmentExtraRepository
   */
   protected $treatmentExtraRepository;

  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Domain\Repository\LocationRepository
   */
   protected $locationRepository;

  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Domain\Repository\ActionRepository
   */
   protected $actionRepository;

  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Domain\Repository\OwnerRepository
   */
   protected $ownerRepository;

  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Domain\Repository\UserRepository
   */
   protected $userRepository;

  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Domain\Repository\ExternalRepository
   */
   protected $externalRepository;

  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Utility\Report
   */
   protected $reportUtility;
   
  /**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\inject
	 */
	protected $persistenceManager;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Validation\ValidatorResolver
	 */
	protected $validatorResolver;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Property\PropertyMapper
	 */
	protected $propertyMapper;

 	/**
	 * @var \TYPO3\Flow\I18n\Translator
	 * @Flow\Inject   
	 */
	protected $translator;
    
	public function initializeIndexAction() {
    if (isset($this->arguments['from'])) {
      $this->arguments['from']
        ->getPropertyMappingConfiguration()
        ->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\DateTimeConverter', \TYPO3\Flow\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'Y-m-d' );
      }    
    if (isset($this->arguments['to'])) {
      $this->arguments['to']
        ->getPropertyMappingConfiguration()
        ->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\DateTimeConverter', \TYPO3\Flow\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'Y-m-d' );
      }    
   }  
    
	/**
	 * @param string $filter  
	 * @param integer $quicksearch  
	 * @param string $sword  
	 * @param \DateTime $from   
	 * @param \DateTime $to   
	 * @return void
	 */
	public function indexAction($filter="session",$quicksearch=0,$sword="",$from=null,$to=null) {
    if($filter=="session") {
      $filter=$this->session->getFilter();
    } else {
      $this->session->setFilter($filter);
    };
    $dummy=new \DLigo\Animaltool\Domain\Model\Animal;
    $this->view->assign('genders', $dummy->getGenders());
    if($quicksearch){
      $filter="quick";
      $animals=$this->animalRepository->filterLocation($this->animalRepository->findQuick($sword),$this->session->getLocation());
      $this->view->assign('sword', $sword);
      if($animals->count()==1 && $animals->getFirst()->getIsTreatable()) $this->redirect('treatment',null,null,array('animal'=>$animals->getFirst()));            
    } else{
  		switch($filter){
        case "all":
          $animals=$this->animalRepository->findAll();
          break;
        case "ready":
          $animals=$this->animalRepository->findByStayStatus(Animal::RELEASING);
          break;
        case "extern":
          $animals=$this->animalRepository->findByStayStatus(Animal::EXTERNAL);
          break;
        case "done":
          $animals=$this->animalRepository->findByStayStatus(Animal::RELEASED);
          break;
        case "adopted":
          $animals=$this->animalRepository->findByStayStatus(Animal::ADOPTED);
          break;
        case "dead":
          $animals=$this->animalRepository->findByTherapyStatus(Animal::DEAD);
          break;
        case "treating":
          $animals=$this->animalRepository->findByTherapyStatus(Animal::THERAPY);
          break;
        case "dummy":
          $animals=$this->animalRepository->findByIsDummy(true);
          break;
        case "new":
        default:
          $animals=$this->animalRepository->findByTherapyStatus(Animal::WAIT);
          break;
      }
      $animals=$this->animalRepository->filterLocation($animals,$this->session->getLocation(),$from,$to);
    }
    $this->view->assign('filter', $filter);
    $this->view->assign('from', $from);
    $this->view->assign('to', $to);
    if($filter!="dummy")$animals=$this->animalRepository->hideDummies($animals);
    $this->view->assign('animals', $animals);
	}

	/**
	 * @return void
	 */
	public function newAction() {
    $sp=$this->speciesRepository->findAll();
    $species=array();
    foreach($sp as $s){
       $species[$this->persistenceManager->getIdentifierByObject($s)]=$s;    
       $useTags[$this->persistenceManager->getIdentifierByObject($s)]=$s->getUseTag();    
    };
    $this->view->assign('species', $species);  
    $this->view->assign('useTags', $useTags);  
    $dummy=new \DLigo\Animaltool\Domain\Model\Animal; 
    $this->view->assign('therapyStatuses', $dummy->getTherapyStatuses());
    $this->view->assign('stayStatuses', $dummy->getStayStatuses());
    $this->view->assign('healthConditions', $dummy->getHealthConditions());
    $this->view->assign('colors',$this->colorRepository->findAll());
    $this->view->assign('genders', $dummy->getGenders());
    $user=$this->session->getUser();
    if($this->session->getLocation()==null) $this->view->assign('locations',$this->locationRepository->findAll());
    $boxid=$user->getTeamID().'-'.($user->getLastBoxID()+1);
    $this->view->assign('boxid',$boxid);
	}


	public function initializeCreateAction() {
    if(!isset($this->request->getArgument('newAnimal')['isPrivate']))return;
    $isprivate=$this->request->getArgument('newAnimal')['isPrivate'];
    if(!$isprivate){
      $validators=$this->arguments->getArgument('newOwner')->getValidator();
      foreach ($validators->getValidators() as $validator) {
        $validators->removeValidator($validator);
      }
    };
  }
  
	/**
	 * @param \DLigo\Animaltool\Domain\Model\Animal $newAnimal
	 * @param \DLigo\Animaltool\Domain\Model\Owner $newOwner
	 * @param array<\DLigo\Animaltool\Domain\Model\Bread> $bread
	 * @param string $tag
	 * @Flow\Validate (argumentName="$newAnimal.photo", type="NotEmpty")
	 * @Flow\Validate (argumentName="$newAnimal.gender", type="NotEmpty")
	 * @return void
	 */
	public function createAction(Animal $newAnimal,\DLigo\Animaltool\Domain\Model\Owner $newOwner,$bread,$tag='') {
    $oldAnimal=null;
    $duplicate=false;
  
    $dummyAction=new \DLigo\Animaltool\Domain\Model\Action();
    $dummyAction->setLat(0);
    $dummyAction->setLng(0);
    $dummyAction->setAnimal($newAnimal);
    $dummyAction->setDate(new \DateTime('now'));
    $user=$this->session->getUser();
    $boxid=$user->getTeamID().'-'.($user->getLastBoxID()+1);
    $dummyAction->setBoxID($boxid);            
    $dummyAction->setTeam($user);
    $location=$this->session->getLocation();
    if($location==null){
     $location=$newAnimal->getLocation();
    }
    $dummyAction->setLocation($location);
    $newAnimal->setLocation($location);
    $speciesid=$this->persistenceManager->getIdentifierByObject($newAnimal->getSpecies());
    $breadid=$bread[$speciesid];
    $newAnimal->setBread($breadid);
    $newAnimal->setStayStatus(Animal::CATCHED);
    $newAnimal->setTherapyStatus(Animal::WAIT);
    
		$this->actionRepository->add($dummyAction);
    $newAnimal->setOwner($newOwner);
    if(!empty($tag)) {
      $oldAnimal=$this->animalRepository->findByRFID($tag)->getFirst();
      if($oldAnimal){
        $tag="_DUMMY_".$tag."_".time();
        $duplicate=true;
        $newAnimal->setIsDummy(true);
      };
      $newAnimal->setRFID($tag);
    };
	  $this->ownerRepository->add($newOwner);
		$this->animalRepository->add($newAnimal);
		$this->addFlashMessage('Created a new animal.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.animal.new');

    if($duplicate) $this->redirect('duplicate',null,null,array('animal'=>$newAnimal,'oldAnimal'=>$oldAnimal));
		$this->redirect('treatment',null,null,array('animal'=>$newAnimal));
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Animal $animal
	 * @Flow\IgnoreValidation (argumentName="$animal")   
	 * @return void
	 */
	public function editAction(Animal $animal) {
    $sp=$this->speciesRepository->findAll();
    $species=array();
    foreach($sp as $s){
       $species[$this->persistenceManager->getIdentifierByObject($s)]=$s;    
       $useTags[$this->persistenceManager->getIdentifierByObject($s)]=$s->getUseTag();    
    };
    $this->view->assign('species', $species);  
    $this->view->assign('useTags', $useTags);  
    $ac=$animal->getActions();
    $actions=array();
    foreach($ac as $a){
       $actions[$this->persistenceManager->getIdentifierByObject($a)]=$a;    
    };
    $this->view->assign('actions',$actions);
    $es=$animal->getExternals();
    $externals=array();
    foreach($es as $e){
       $externals[$this->persistenceManager->getIdentifierByObject($e)]=$e;    
    };
    $this->view->assign('externals',$externals);
    $tr=$animal->getTreatments();
    $treatments=array();
    foreach($tr as $t){
       $treatments[$this->persistenceManager->getIdentifierByObject($t)]=$t;    
    };
    $this->view->assign('treatments',$treatments);
    $ts=$this->therapyRepository->findAll();
    $therapies=array();
    foreach($ts as $t){
       $therapies[$this->persistenceManager->getIdentifierByObject($t)]=$t;    
    };
    $this->view->assign('therapies',$therapies);
    $this->view->assign('therapyStatuses', $animal->getTherapyStatuses());
    $this->view->assign('stayStatuses', $animal->getStayStatuses());
    $this->view->assign('healthConditions', $animal->getHealthConditions());
    $this->view->assign('colors',$this->colorRepository->findAll());
    $this->view->assign('genders', $animal->getGenders());
    $user=$this->session->getUser();
    $this->view->assign('locations',$this->locationRepository->findAll());
    $boxid=$user->getTeamID().'-'.($user->getLastBoxID()+1);
    $this->view->assign('boxid',$boxid);
    $this->view->assign('teams',$this->userRepository->findByRoles(array('DLigo.Animaltool:Doctor','DLigo.Animaltool:Catcher','DLigo.Animaltool:Admin')));
    $this->view->assign('doctors',$this->userRepository->findByRoles(array('DLigo.Animaltool:Doctor','DLigo.Animaltool:Catcher','DLigo.Animaltool:Admin')));
		$this->view->assign('animal', $animal);
	}

	public function initializeUpdateAction() {
    $isprivate=$this->request->getArgument('animal')['isPrivate'];
    if(!$isprivate){
      $validators=$this->arguments->getArgument('owner')->getValidator();
      foreach ($validators->getValidators() as $validator) {
        $validators->removeValidator($validator);
      }
    };
    
    if($this->request->hasArgument('tag') && !empty($this->request->getArguments()['tag'])){
      $animalValidator=$this->arguments->getArgument('animal')->getValidator();
      $conjValidator = $this->validatorResolver->createValidator('Conjunction');
      $conjValidator->addValidator($animalValidator);
      $animalExistsValidator = $this->validatorResolver->createValidator('\DLigo\Animaltool\Validation\Validator\AnimalExistsValidator');
      $animalExistsValidator->setTag($this->request->getArguments()['tag']);    
      $conjValidator->addValidator($animalExistsValidator);
      $this->arguments->getArgument('animal')->setValidator($conjValidator);    
    };                                                                      
  }

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Animal $animal
	 * @param \DLigo\Animaltool\Domain\Model\Owner $owner
	 * @param array<\DLigo\Animaltool\Domain\Model\Bread> $bread
	 * @param string $tag
	 * @param array $externals
	 * @param array $treatments   
	 * @Flow\Validate (argumentName="$treatments", type="\DLigo\Animaltool\Validation\Validator\TreatmentValidator",options={"isArray"=true} )
	 * @Flow\Validate (argumentName="$animal.photo", type="NotEmpty")
	 * @return void
	 */
	public function updateAction(Animal $animal,$owner,$bread,$tag,$externals=array(),$treatments=array()) {
    if($animal->getIsPrivate()) {
      $animal->setRFID($tag);
      $oldowner=$animal->getOwner();
      if($oldowner){
        foreach(array('Name','FirstName','IDNumber','CNP','Phone','Street','HouseNumber','ZipCode','City','Region','Country','PassId','Comment') as $field){
          $get='get'.$field;
          $set='set'.$field;
          $oldowner->$set($owner->$get());
        }
      } else {
        $animal->setOwner($owner);
      };
    } else {
      $animal->setEarTag($tag);
      $animal->setOwner(null);
    }
    
    foreach($externals as $eid=>$eArray){
      $external=$this->externalRepository->findByIdentifier($eid);
      foreach(array('name','firstName','lastName','externalType','isPermanent','phone','street','houseNumber','zipCode','city','region','country','comment') as $field){
        $set='set'.ucfirst($field);
        $external->$set($eArray[$field]);
      }
      if($external->getExternalType()==\DLigo\Animaltool\Domain\Model\External::CLINIC)$external->setIsPermanent(false);
      if($external->getExternalType()==\DLigo\Animaltool\Domain\Model\External::ADOPTION)$external->setIsPermanent(true);
      if($external->getIsPermanent()) {
        $external->stopExternal();
      } else {
        $open=true;
      };
      $this->externalRepository->update($external);
    };
    
    foreach($treatments as $trid=>$treatArray){
      $treatment=$this->treatmentRepository->findByIdentifier($trid);
      $treatment->setComment($treatArray['comment']);
      $doctor=$this->userRepository->findByIdentifier(isset($treatArray['doctor']['__identity'])?$treatArray['doctor']['__identity']:$treatArray['doctor']);
      $treatment->setDoctor($doctor);
      $therapies=new \Doctrine\Common\Collections\ArrayCollection;
      $extras=new \Doctrine\Common\Collections\ArrayCollection;
      if(is_array($treatArray['therapies'])) foreach($treatArray['therapies'] as $thid){
        $therapy=$this->therapyRepository->findByIdentifier($thid);
        if($therapy->getHasExtraData()){
          $extra=$this->treatmentExtraRepository->findByTherapyAndTreatment($thid,$treatment)->getFirst();
          if(!$extra){
            $extra=new \DLigo\Animaltool\Domain\Model\TreatmentExtra();
            $extra->setTreatment($treatment);
            $extra->setTherapy($therapy);
          };
          $extra->setValue($treatArray['treatmentExtra'][$thid]);
          unset($treatArray['treatmentExtra'][$thid]);
          $extras->add($extra);
        };
        $therapies->add($therapy);
      }
      $treatment->setTherapies($therapies);
      $treatment->setTreatmentExtras($extras);
      $this->treatmentRepository->update($treatment);
      if(isset($treatArray['treatmentExtra']) && is_array($treatArray['treatmentExtra'])) foreach($treatArray['treatmentExtra'] as $thid=>$value){
        $extra=$this->treatmentExtraRepository->findByTherapyAndTreatment($thid,$treatment)->getFirst();
        if($extra!=null) $this->treatmentExtraRepository->remove($extra);
      };
    }
    
    $speciesid=$this->persistenceManager->getIdentifierByObject($animal->getSpecies());
/*    $breadid=isset($bread[$speciesid]['__identity'])?$bread[$speciesid]['__identity']:$bread[$speciesid];
    $animal->setBread($this->breadRepository->findByIdentifier($breadid));
*/
    $animal->setBread($bread[$speciesid]);
  
		$this->animalRepository->update($animal);
		$this->addFlashMessage('Updated the animal.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.animal.update');
		$this->redirect('index');
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Animal $animal
	 * @Flow\IgnoreValidation (argumentName="$animal")   
	 * @return void
	 */
	public function treatmentAction(Animal $animal) {
    $this->response->setHeader('no-store',true);
    $this->response->setHeader('no-cache',true);
    $this->view->assign('healthConditions', $animal->getHealthConditions());
    $this->view->assign('breads',$this->breadRepository->findBySpecies($animal->getSpecies()));
    $this->view->assign('colors',$this->colorRepository->findAll());
    $this->view->assign('genders', $animal->getGenders());
    $this->view->assign('doctors',$this->userRepository->findByRoles(array('DLigo.Animaltool:Doctor')));
    $ts=$this->therapyRepository->findAll();
    $therapies=array();
    foreach($ts as $t){
       $therapies[$this->persistenceManager->getIdentifierByObject($t)]=$t;    
    };
    $this->view->assign('therapies',$therapies);
		$this->view->assign('animal', $animal);
	}
 
	public function initializeTreatAction() {
    if($this->request->hasArgument('release') && $this->request->hasArgument('useTag') && $this->request->getArgument('useTag')){
      $tagValidator = $this->validatorResolver->createValidator('NotEmpty');
      $this->arguments->getArgument('tag')->setValidator($tagValidator);    
    };
    foreach($this->arguments->getArgument('treatment')->getValidator()->getValidators() as $v)
      $v->setAnimal($this->request->getArgument('animal')['__identity']);    
  }

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Animal $animal
	 * @param array $treatment
	 * @param string $tag
	 * @param string $passId
	 * @param string $contunue   
	 * @param string $release   
	 * @param string $extern   
	 * @param string $newextern   
	 * @param string $dead   
	 * @param string $origRFID   
	 * @param string $origEarTag   
	 * @Flow\Validate (argumentName="$treatment", type="\DLigo\Animaltool\Validation\Validator\TreatmentValidator")
	 * @Flow\Validate (argumentName="$animal.healthCondition",type="NotEmpty")   
	 * @Flow\Validate (argumentName="$animal.photo", type="NotEmpty")
	 * @return void
	 */
	public function treatAction(Animal $animal, $treatment,$tag='',$passId='',$continue=null,$release=null,$extern=null,$newextern=null,$dead=null,$origRFID='',$origEarTag='') {
    $duplicate=false;
//    \TYPO3\Flow\var_dump($this->request);

    $done=false;
    $mergeMessage='';
    $merge='';
    $tr=$animal->getOpenTreatment();
    if(!$tr){
      $tr=new \DLigo\Animaltool\Domain\Model\Treatment();
      $tr->setStartDate(new \DateTime('now'));
      $tr->setAnimal($animal);
//      $tr->setDoctor($this->session->getUser());
      $animal->getTreatments()->add($tr);
    }; 
    $did=isset($treatment['doctor']['__identity'])?$treatment['doctor']['__identity']:$treatment['doctor'];
    $tr->setDoctor($this->userRepository->findByIdentifier($did));
    $therapies=new \Doctrine\Common\Collections\ArrayCollection;
    $extras=new \Doctrine\Common\Collections\ArrayCollection;
    if(is_array($treatment['therapies'])) foreach($treatment['therapies'] as $thid){
      $therapy=$this->therapyRepository->findByIdentifier($thid);
      if($therapy->getHasExtraData()){
        $extra=$this->treatmentExtraRepository->findByTherapyAndTreatment($thid,$tr)->getFirst();
        if(!$extra){
          $extra=new \DLigo\Animaltool\Domain\Model\TreatmentExtra();
          $extra->setTreatment($tr);
          $extra->setTherapy($therapy);
        };
        $extra->setValue($treatment['treatmentExtra'][$thid]);
        unset($treatment['treatmentExtra'][$thid]);
        $extras->add($extra);
      };
      $therapies->add($therapy);
    };
    $tr->setTherapies($therapies);
    $tr->setTreatmentExtras($extras);
    $tr->setComment($treatment['comment']);
    if($this->persistenceManager->isNewObject($tr)) {
      $this->treatmentRepository->add($tr);
    } else $this->treatmentRepository->update($tr);
    if(isset($treatment['treatmentExtra']) && is_array($treatment['treatmentExtra'])) foreach($treatment['treatmentExtra'] as $thid=>$value){
      $extra=$this->treatmentExtraRepository->findByTherapyAndTreatment($thid,$tr)->getFirst();
      if($extra!=null) $this->treatmentExtraRepository->remove($extra);
    };
   
    if($continue){
      $animal->setStayStatus(Animal::THERAPY);
      $animal->setTherapyStatus(Animal::OPERATION);
      $animal->stopOpenExternals();
      $animal->stopOpenTreatments();
    }elseif($release){
      $animal->setStayStatus(Animal::RELEASING);
      $animal->setTherapyStatus(Animal::READY);
      $animal->stopOpenExternals();
      $animal->stopOpenTreatments();
      $animal->getLastAction()->setReleaseDate(new \DateTime('now'));
    }elseif($extern){
      if($animal->getStayStatus()==Animal::CATCHED) $animal->setStayStatus(Animal::THERAPY);
      if($animal->getTherapyStatus()==Animal::WAIT) $animal->setTherapyStatus(Animal::OPERATION);
      $animal->stopOpenTreatments();
    }elseif($newextern){
      $animal->stopOpenExternals();
      $animal->stopOpenTreatments();
    }elseif($dead){
      $animal->setStayStatus(Animal::RELEASED);
      $animal->setTherapyStatus(Animal::DEAD);
      $animal->stopOpenExternals();
      $animal->stopOpenTreatments();
    }
    
    $oldAnimal=null;
    if($animal->getIsPrivate()) {
      $animal->setRFID($tag);
      if($tag!=$origRFID && !empty($tag)) $oldAnimal=$this->animalRepository->findByRFID($tag)->getFirst();
      $animal->getOwner()->setPassId($passId);
    } else {
      $animal->setEarTag($tag);
      if($tag!=$origEarTag && !empty($tag)) $oldAnimal=$this->animalRepository->findByEarTag($tag)->getFirst();
    }
    if(!empty($oldAnimal)) {
      $tag="_DUMMY_".$tag."_".time();
      $duplicate=true;
      $animal->setIsDummy(true);
      $animal->setRFID($tag);
    };
      
  	$this->animalRepository->update($animal);
    if($duplicate) $this->redirect('duplicate',null,null,array('animal'=>$animal,'oldAnimal'=>$oldAnimal,'external'=>$extern!=null));
    if($extern || $newextern)$this->redirect('open','External',null,array('animal'=>$animal));
		$this->addFlashMessage('Updated the animal.'.$mergeMessage, '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.animal.update'.$merge);
		$this->redirect('index');
    return '';
	}

	/**
	 * @param \DLigo\Animaltool\Domain\Model\Animal $animal
	 * @return void
	 */
	public function deleteAction(Animal $animal) {
		$this->animalRepository->remove($animal);
		$this->addFlashMessage('Deleted a animal.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.animal.delete');
		$this->redirect('index');
	}

  /**
   *   
	 * @param \DLigo\Animaltool\Domain\Model\Animal $oldAnimal
	 * @param \DLigo\Animaltool\Domain\Model\Animal $animal
	 * @param boolean $external   
	 * @return void
	 */
	public function duplicateAction(Animal $oldAnimal,Animal $animal,$external=false) {
		$this->view->assign('oldanimal', $oldAnimal);
		$this->view->assign('animal', $animal);
		$this->view->assign('external', $external);
    if(substr($animal->getRFID(), 0, 7) === '_DUMMY_'){
      $tag=$oldAnimal->getRFID();
    } else {
      $tag=$oldAnimal->getEarTag();
    };
		$this->view->assign('animal_tag', $tag);
  }

	public function initializeMergeAction() {
    if(!$this->request->hasArgument('try')) return;
    $validators=$this->arguments->getArgument('tryagain')->getValidator();
    $notempty=$this->validatorResolver->createValidator('NotEmpty');
    $validators->addValidator($notempty);
  }

  /**
   *   
	 * @param \DLigo\Animaltool\Domain\Model\Animal $oldAnimal
	 * @param \DLigo\Animaltool\Domain\Model\Animal $animal
	 * @param string $tryagain   
	 * @param string $tryagain   
	 * @param boolean $external   
	 * @param boolean $cancel   
	 * @return void
	 */
	public function mergeAction(Animal $oldAnimal,Animal $animal,$tryagain='',$external=false,$cancel=null){
    if($cancel){
      $isrfid=(substr($animal->getRFID(), 0, 7) === '_DUMMY_');
      if($isrfid){
        $animal->setRFID(null);
      } else {
        $animal->setEarTag(null);
      };
      $animal->setIsDummy(false);
      $this->animalRepository->update($animal);           
		  $this->addFlashMessage('Animals not merged. RFID/Eartag removed', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.animal.merge.cancel');
		  $this->redirect('index');
    }
    if(!empty($tryagain)){
      $isrfid=(substr($animal->getRFID(), 0, 7) === '_DUMMY_');
      if($isrfid){
        $old=$this->animalRepository->findByRFID($tryagain)->getFirst();
      } else {
        $old=$this->animalRepository->findByEarTag($tryagain)->getFirst();
      };
      if(empty($old)){
        if($isrfid) {
          $animal->setRFID($tryagain);
        } else {
          $animal->setEarTag($tryagain);
        };
        $animal->setIsDummy(false);
        $this->animalRepository->update($animal);           
		    $this->redirect('index');
      } else {
        $tag="_DUMMY_".$tryagain."_".time();
        if($isrfid) {
          $animal->setRFID($tag);
        } else {
          $animal->setEarTag($tag);
        };
        $this->animalRepository->update($animal);           
        $this->redirect('duplicate',null,null,array('animal'=>$animal,'oldAnimal'=>$old,'external'=>$external));
      };
    };
    
    $overrideif=array('Weight','Birthday','Photo','Gender','Bread','Color','EarTag');
    $merge=array('Comment','SpecialProperties');
    $add=array('Actions','Treatments');
    
    foreach($overrideif as $key){
      $get='get'.$key;
      $set='set'.$key;
      $val=$animal->$get();
      if(empty($val)) $animal->$set($oldAnimal->$get());
    };
    foreach($merge as $key){
      $get='get'.$key;
      $set='set'.$key;
      $nl='';
      $o=$animal->$get();
      $n=$oldAnimal->$get();
      if(!empty($o) && !empty($n)) $nl="\n";
      $animal->$set($animal->$get().$nl.$oldAnimal->$get());
    };
    foreach($add as $key){
      $get='get'.$key;
      $set='set'.$key;
      $oldCol=$oldAnimal->$get();
      $newCol=$animal->$get();
      $col=new \Doctrine\Common\Collections\ArrayCollection;
      foreach($oldCol as $obj){
        $nobj=clone $obj;
        $col->add($nobj);
        $nobj->setAnimal($animal);
      };
      $animal->stopOpenTreatments();
      foreach($newCol as $obj){
        $nobj=$obj;
        $col->add($nobj);
        $nobj->setAnimal($animal);
      };
      $animal->$set($col);
    };          
    if($oldAnimal->getIsPrivate() || $animal->getIsPrivate()){
      $animal->setIsPrivate(true);
      $owner=$oldAnimal->getOwner(); 
      if($animal->getOwner()==null && $oldAnimal->getOwner()!=null) $owner=clone $oldAnimal->getOwner();
    };
    
    $animal->setRFID($oldAnimal->getRFID());
    $this->animalRepository->remove($oldAnimal);
    $this->persistenceManager->persistAll();
    $animal->setIsDummy(false);
    $this->animalRepository->update($animal);           
    if($external) $this->redirect('open','External',null,array('animal'=>$animal));
		$this->redirect('index');
  }
  
  public function doneAction(Animal $animal){
    if($animal->getStayStatus()==Animal::EXTERNAL && ($animal->getOpenExternal()==null || $animal->getOpenExternal()->getExternalType()!=\DLigo\Animaltool\Domain\Model\External::CLINIC)){
      if($animal->getOpenExternal()) $animal->getOpenExternal()->setIsPermanent(true);
      $animal->setStayStatus(Animal::ADOPTED);
		  $this->addFlashMessage('Set animal as permanently sheltered/adopted.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.animal.adopted');
    } else {
      $animal->setStayStatus(Animal::RELEASED);
		  $this->addFlashMessage('Set animal as done/released.', '',\TYPO3\Flow\Error\Message::SEVERITY_OK,array(),'flash.animal.done');
    };
    $animal->setTherapyStatus(Animal::READY);
    $this->animalRepository->update($animal);
		$this->redirect('index');
  }  

  /**
   *   
	 * @param \DLigo\Animaltool\Domain\Model\Animal $animal
	 * @return void
	 */
	public function viewAction(Animal $animal) {
		$this->view->assign('animal', $animal);
  }
  
	public function initializeReportAction() {
    if (isset($this->arguments['from'])) {
      $this->arguments['from']
        ->getPropertyMappingConfiguration()
        ->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\DateTimeConverter', \TYPO3\Flow\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'Y-m-d' );
      }    
    if (isset($this->arguments['to'])) {
      $this->arguments['to']
        ->getPropertyMappingConfiguration()
        ->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\DateTimeConverter', \TYPO3\Flow\Property\TypeConverter\DateTimeConverter::CONFIGURATION_DATE_FORMAT, 'Y-m-d' );
      }    
   }  
    

	/**
	 * @param \DateTime $from
	 * @param \DateTime $to
	 * @param integer $reportType   
	 * @return void
	 */
	public function reportAction(\DateTime $from=null,\DateTime $to=null,$reportType=0) {
    if($from) {
      $col=function($item){
        return $item['firstname'].' '.$item['lastname'].'('.\DLigo\Animaltool\Domain\Model\User::teamIDString($item['teamID']).')';
      };
      $source=function($item){
        if($item['web']){
          $label=$this->translator->translateById('report.source.web', array(), NULL, NULL, 'Main', 'DLigo.Animaltool');
        } else {
          $label=$this->translator->translateById('report.source.app', array(), NULL, NULL, 'Main', 'DLigo.Animaltool');
        };
        return $label;
      };
      $ownerStat=function($item){
        if($item['ownerStat']){
          $label=$this->translator->translateById('report.withOwner', array(), NULL, NULL, 'Main', 'DLigo.Animaltool');
        } else {
          $label=$this->translator->translateById('report.withoutOwner', array(), NULL, NULL, 'Main', 'DLigo.Animaltool');
        };
        return $label;
      };
      $tagType=function($item){
        if($item['tagType']==2){
          $label=$this->translator->translateById('report.tagType.2', array(), NULL, NULL, 'Main', 'DLigo.Animaltool');
        } elseif($item['tagType']==1){
          $label=$this->translator->translateById('report.tagType.1', array(), NULL, NULL, 'Main', 'DLigo.Animaltool');
        } else {
          $label=$this->translator->translateById('report.tagType.0', array(), NULL, NULL, 'Main', 'DLigo.Animaltool');
        };
        return $label;
      };
      $gender=function($item){
        $label=$this->translator->translateById('report.gender.'.strtolower($item['gender']), array(), NULL, NULL, 'Main', 'DLigo.Animaltool');
        return $label;
      };
      $dummy=new \DLigo\Animaltool\Domain\Model\Animal;     
      $stats=$dummy->getStayStatuses();
      $stay=function($item) use($stats) {
        return $stats[$item['stayStatus']];
      };
      $stats=$dummy->getTherapyStatuses();
      $therapy=function($item) use($stats) {
        return $stats[$item['therapyStatus']];
      };
      if($reportType==1){
        for($i=100;$i<=105;$i++){
          $report[$i]=true;
        }
        $typename='simple';
      } elseif($reportType==0){
        for($i=200;$i<=218;$i++){
          $report[$i]=true;
        }
        $typename='advanced';
      } elseif($reportType==2){
        for($i=300;$i<=302;$i++){
          $report[$i]=true;
        }
        $typename='lists';
      } else {
        $report[$reportType]=true;
      };
      $tables=array();
      if(!empty($report[100])){
        $counts=$this->reportUtility->countAllSpeciesAndSource($from,$to,$this->session->getLocation());
        $table=$this->reportUtility->buildReportTable($counts,'species','web',null,$source,true);
        $this->view->assign('species_source', $table);
        $name='species-source';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[101])){
        $counts=$this->reportUtility->countAllSpeciesAndOwnerStat($from,$to,$this->session->getLocation());
        $table=$this->reportUtility->buildReportTable($counts,'species','ownerStat',null,$ownerStat,true);
        $this->view->assign('species_ownerstat', $table);
        $name='species-ownerstat';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[102])){
        $counts=$this->reportUtility->countAllSpeciesAndGender($from,$to,$this->session->getLocation());
        $table=$this->reportUtility->buildReportTable($counts,'species','gender',null,$gender,true);
        $this->view->assign('species_gender', $table);
        $name='species-gender';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[103])){
        $counts=$this->reportUtility->countAllSpeciesAndGender($from,$to,$this->session->getLocation(),'th.name=\'Sterilisation\'',true);
        $table=$this->reportUtility->buildReportTable($counts,'species','gender',null,$gender,true);
        $this->view->assign('species_gender_sterilisation', $table);
        $name='species-gender-sterilisation';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[104])){
        $counts=$this->reportUtility->countAllSpeciesAndGender($from,$to,$this->session->getLocation(),'(th.name=\'Rabies vaccine\' OR th.name=\'Other vaccine\')',true);
        $table=$this->reportUtility->buildReportTable($counts,'species','gender',null,$gender,true);
        $this->view->assign('species_gender_vaccination', $table);
        $name='species-gender-vaccination';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[105])){
        $counts=$this->reportUtility->countAllSpeciesAndStayStatus($from,$to,$this->session->getLocation(),'(a.stayStatus='.Animal::THERAPY.' OR a.stayStatus='.Animal::RELEASED.' OR a.stayStatus='.Animal::EXTERNAL.')');
        $table=$this->reportUtility->buildReportTable($counts,'species','stayStatus',null,$stay,true);
        $this->view->assign('species_staystatus', $table);
        $name='species-staystatus-simple';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[200])){
        $counts=$this->reportUtility->countAllSpeciesAndTeam($from,$to,$this->session->getLocation());
        $table=$this->reportUtility->buildReportTable($counts,'species','teamID',null,$col);
        $this->view->assign('species_team', $table);
        $name='species-team';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[201])){
        $counts=$this->reportUtility->countAllSpeciesAndTeam($from,$to,$this->session->getLocation(),"a.owner IS NOT NULL");
        $table=$this->reportUtility->buildReportTable($counts,'species','teamID',null,$col);
        $this->view->assign('species_team_owner', $table);
        $name='species-team-owner';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[202])){
        $counts=$this->reportUtility->countAllSpeciesAndTeam($from,$to,$this->session->getLocation(),"a.owner IS NULL");
        $table=$this->reportUtility->buildReportTable($counts,'species','teamID',null,$col);
        $this->view->assign('species_team_noowner', $table);
        $name='species-team-noowner';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[203])){
        $counts=$this->reportUtility->countAllSpeciesAndTeam($from,$to,$this->session->getLocation(),"a.stayStatus=".Animal::RELEASED,true);
        $table=$this->reportUtility->buildReportTable($counts,'species','teamID',null,$col);
        $this->view->assign('species_team_done', $table);
        $name='species-team-done';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[204])){       
        $counts=$this->reportUtility->countAllTherapyAndTeam($from,$to,$this->session->getLocation());
        $table=$this->reportUtility->buildReportTable($counts,'therapy','teamID',null,$col);
        $this->view->assign('therapy_team', $table);
        $name='therapy-team';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[205])){
        $counts=$this->reportUtility->countAllTherapyAndTeam($from,$to,$this->session->getLocation(),"a.gender='F'");
        $table=$this->reportUtility->buildReportTable($counts,'therapy','teamID',null,$col);
        $this->view->assign('therapy_team_female', $table);
        $name='therapy-team-female';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[206])){
        $counts=$this->reportUtility->countAllTherapyAndTeam($from,$to,$this->session->getLocation(),"a.gender='M'");
        $table=$this->reportUtility->buildReportTable($counts,'therapy','teamID',null,$col);
        $this->view->assign('therapy_team_male', $table);
        $name='therapy-team-male';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[207])){
        $counts=$this->reportUtility->countAllSpeciesAndStayStatus($from,$to,$this->session->getLocation());
        $table=$this->reportUtility->buildReportTable($counts,'species','stayStatus',null,$stay);
        $this->view->assign('species_staystatus', $table);
        $name='species-staystatus';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[208])){
        $counts=$this->reportUtility->countAllSpeciesAndTherapyStatus($from,$to,$this->session->getLocation());
        $table=$this->reportUtility->buildReportTable($counts,'species','therapyStatus',null,$therapy);
        $this->view->assign('species_therapystatus', $table);
        $name='species-therapystatus';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
/*      if(!empty($report[209])){
        $counts=$this->reportUtility->countAllSpeciesAndTherapyTeam($from,$to,$this->session->getLocation(),"a.stayStatus=".Animal::OPERATION);
        $table=$this->reportUtility->buildReportTable($counts,'species','teamID',null,$col);
        $this->view->assign('species_therapyteam', $table);
        $name='species-therapyteam';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };*/
      if(!empty($report[210])){
        $counts=$this->reportUtility->countAllSpeciesAndTeam($from,$to,$this->session->getLocation(),"a.gender='F'");
        $table=$this->reportUtility->buildReportTable($counts,'species','teamID',null,$col);
        $this->view->assign('species_team_female', $table);
        $name='species-team-female';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[211])){
        $counts=$this->reportUtility->countAllSpeciesAndTeam($from,$to,$this->session->getLocation(),"a.gender='M'");
        $table=$this->reportUtility->buildReportTable($counts,'species','teamID',null,$col);
        $this->view->assign('species_team_male', $table);
        $name='species-team-male';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[212])){       
        $counts=$this->reportUtility->countAllTherapyAndTeam($from,$to,$this->session->getLocation(),"s.name='Dog'");
        $table=$this->reportUtility->buildReportTable($counts,'therapy','teamID',null,$col);
        $this->view->assign('therapy_team_dog', $table);
        $name='therapy-team-dog';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[213])){
        $counts=$this->reportUtility->countAllTherapyAndTeam($from,$to,$this->session->getLocation(),"a.gender='F' AND s.name='Dog'");
        $table=$this->reportUtility->buildReportTable($counts,'therapy','teamID',null,$col);
        $this->view->assign('therapy_team_dog_female', $table);
        $name='therapy-team-dog-female';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[214])){
        $counts=$this->reportUtility->countAllTherapyAndTeam($from,$to,$this->session->getLocation(),"a.gender='M' AND s.name='Dog'");
        $table=$this->reportUtility->buildReportTable($counts,'therapy','teamID',null,$col);
        $this->view->assign('therapy_team_dog_male', $table);
        $name='therapy-team-dog-male';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[215])){       
        $counts=$this->reportUtility->countAllTherapyAndTeam($from,$to,$this->session->getLocation(),"s.name='Cat'");
        $table=$this->reportUtility->buildReportTable($counts,'therapy','teamID',null,$col);
        $this->view->assign('therapy_team_cat', $table);
        $name='therapy-team-cat';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[216])){
        $counts=$this->reportUtility->countAllTherapyAndTeam($from,$to,$this->session->getLocation(),"a.gender='F' AND s.name='Cat'");
        $table=$this->reportUtility->buildReportTable($counts,'therapy','teamID',null,$col);
        $this->view->assign('therapy_team_cat_female', $table);
        $name='therapy-team-cat-female';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[217])){
        $counts=$this->reportUtility->countAllTherapyAndTeam($from,$to,$this->session->getLocation(),"a.gender='M' AND s.name='Cat'");
        $table=$this->reportUtility->buildReportTable($counts,'therapy','teamID',null,$col);
        $this->view->assign('therapy_team_cat_male', $table);
        $name='therapy-team-cat-male';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[218])){
        $counts=$this->reportUtility->countAllSpeciesAndTagType($from,$to,$this->session->getLocation(),"s.name='Dog' AND ((a.rFID IS NOT NULL AND a.rFID!='') OR (a.earTag IS NOT NULL AND a.earTag!=''))");
        $table=$this->reportUtility->buildReportTable($counts,'species','tagType',null,$tagType);
        $this->view->assign('species_tagtype', $table);
        $name='species-tagtype';
        $tables[]=$table;
        $this->view->assign('tables', $tables);
      };
      if(!empty($report[300])){
        $animals=$this->animalRepository->findByDate($from,$to,$this->session->getLocation());
        $this->view->assign('animals', $animals);
        $name='animals';
        $pdf = new \Famelo\PDF\Document('DLigo.Animaltool:report', 'A4');
        $pdf->assign('animals',$animals);
      };
      if(!empty($report[301])){
        $owners=$this->animalRepository->findByDate($from,$to,$this->session->getLocation(),true);
        $this->view->assign('animals_withowner', $owners);
        $name='owners';
      };
      if(!empty($report[302])){
        $owners=$this->animalRepository->findByDate($from,$to,$this->session->getLocation(),true,array('func'=>'equals','name'=>'species.name','value'=>'Dog'));
        $this->view->assign('recs', $owners);
        $name='recs';
      };
      $this->view->assign('report', true);
    } else $this->view->assign('report', false);  
		$this->view->assign('from', $from);
		$this->view->assign('to', $to);
		$this->view->assign('reportType', $reportType);
    if($this->request->getFormat()=='csv'){
      $this->response->setHeader('Content-Type','text/plain');
      if(count($tables)>1)$name=$typename;
      $name='export_'.$name;
      $name.='_'.$from->format('Y-m-d');
      if($to) $name.='_'.$to->format('Y-m-d');
      if($this->session->getLocation()) $name.='_'.$this->session->getLocation()->getName();
      $name.='.csv';
      $this->response->setHeader('Content-Disposition','attachment; filename="'.$name.'"');
    };
    if($this->request->getFormat()=='pdf'){
  		$pdf->assign('from', $from);
	 	  $pdf->assign('to', $to);
	 	  $pdf->assign('session', $this->session);
      $name='report_anmals';
      $name.='_'.$from->format('Y-m-d');
      if($to) $name.='_'.$to->format('Y-m-d');
      if($this->session->getLocation()) $name.='_'.$this->session->getLocation()->getName();
      $name.='.pdf';
      $pdf->download($name);
    };
	}
 

}