<?php
namespace DLigo\Animaltool\Events;

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

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\OnFlushEventArgs;
use TYPO3\Flow\Annotations as Flow;
use DLigo\Animaltool\Domain\Model\HistoryChange; 
 
 
class EventListener {

  /**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\inject
	 */
	protected $persistenceManager;

  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Domain\Repository\AnimalRepository
   */
  protected $animalRepository;
   
	/**
	 * @Flow\Inject
	 * @var \DLigo\Animaltool\Domain\Model\Session
	 */
	protected $session;
  
  /** 
   * @var TYPO3\Flow\Log\SystemLoggerInterface
   * @Flow\Inject 
   */
   protected $systemLogger;  
  
  private $em = null;
  private $uow = null;
  private $attachedEvents;
  private $hmeta;
  private $userId;
  private $userLabel;
  private $owners;
  private $photos;
  private $photospointer;

  public function onFlush(OnFlushEventArgs $args) {
    if(PHP_SAPI === 'cli') return;
  	$this->em = $args->getEntityManager();
    $eventManager = $this->em->getEventManager();
    $eventManager->removeEventListener('onFlush', $this);
    $this->uow=$this->em->getUnitOfWork();
    $histories=new \Doctrine\Common\Collections\ArrayCollection;
    $this->hmeta=$this->em->getClassMetadata('DLigo\Animaltool\Domain\Model\HistoryChange');
    $user=$this->session->getUser();
    $this->userId=$this->persistenceManager->getIdentifierByObject($user);
    $this->userLabel=$user->__toString();
    
  	foreach ($this->uow->getScheduledEntityInsertions() as $entity) {
    	$meta=$this->em->getClassMetadata(get_class($entity));
      $this->putAnimal($entity,$meta);

      $history=array('entity'=>$entity,'changes'=>$this->uow->getEntityChangeSet($entity),'meta'=>$meta,'type'=>'INSERT');
      $histories->add($history);                                    
      $this->em->getUnitOfWork()->recomputeSingleEntityChangeSet($meta,$entity);
    };
 
    foreach ($this->uow->getScheduledEntityDeletions() AS $entity) {
    	$meta=$this->em->getClassMetadata(get_class($entity));
      $this->putAnimal($entity,$meta);

      $history=array('entity'=>$entity,'changes'=>null,'meta'=>$meta,'type'=>'DELETE');
      $histories->add($history);                                    
    }; 

    foreach ($this->uow->getScheduledEntityUpdates() as $key => $entity) {
      $meta=$this->em->getClassMetadata(get_class($entity));
      $this->putAnimal($entity,$meta);
 
      $history=array('entity'=>$entity,'changes'=>$this->uow->getEntityChangeSet($entity),'meta'=>$meta,'type'=>'UPDATE');
      $histories->add($history);                                    
      $this->uow->computeChangeSet($meta, $entity);
    }    

    foreach($histories as $h)$this->processHistory($h);
    
    $eventManager->addEventListener('onFlush', $this);
  }

  private function processHistory($h){
    $entity=$h['entity'];
    $meta=$h['meta'];
    $type=$h['type'];
    $changes=$h['changes'];
    $history=new HistoryChange;
    $history->setObjectType($this->getNamespacelessName($meta));      
    $history->setAction($type);
    $id=$this->persistenceManager->getIdentifierByObject($entity);
    if(empty($id))$id='???';    
    $history->setId($id);
    $history->setTime(new \DateTime('now'));
    $animal=$this->getAnimal($entity);
    if(!empty($animal)){
      $history->setAnimalId($this->persistenceManager->getIdentifierByObject($animal));
      $history->setAnimalLabel($animal->getBoxId());
    }    
    $history->setById($this->userId);
    $history->setByLabel($this->userLabel);
    $history->setProperties($this->changesToString($changes,$type));
    $this->em->persist($history);
    $this->uow->computeChangeSet($this->hmeta, $history);
  }
  
  private function getNamespacelessName($meta){
    $name=$meta->getName();
    $idx = strrpos($name, '\\');
    return substr($name,$idx+1);
  }
  
  private function changesToString($changes,$type){
    if($type=='DELETE') return '';
    $ret='';
    foreach($changes as $field =>$c){
      if($field=='Persistence_Object_Identifier') continue;
      $ret.=$field.': ';
      if($type=='UPDATE') $ret.=$this->makeString($c[0]).' => ';
      $ret.=$this->makeString($c[1])."\n";
    }
    return $ret;
  }
  
  private function makeString($input){
    if($input instanceof \DateTime) {
			return $input->format(\DateTime::ISO8601);
		} elseif (is_object($input)) {
      return $this->persistenceManager->getIdentifierByObject($input);    
    } else {
      return (String) $input;
    }
  }
  
  private function getAnimal($entity){
   $animal=null;
   if(method_exists($entity,'getAnimal')){
     $animal=$entity->getAnimal();
   } elseif(method_exists($entity,'getOldAnimal')){
     $animal=$entity->getOldAnimal();
   } elseif($entity instanceof \DLigo\Animaltool\Domain\Model\Owner) {
     $animal=$this->animalRepository->findByOwner($entity)->getFirst();
     if(empty($animal)){
       $id=$this->persistenceManager->getIdentifierByObject($entity);
       if(isset($this->owners[$id])) $animal=$this->owners[$id];
     };
   } elseif($entity instanceof \TYPO3\Flow\Resource\Resource) {
     $animal=$this->animalRepository->findByPhoto($entity)->getFirst();     	
     if(empty($animal)){
       $id=$this->persistenceManager->getIdentifierByObject($entity);
       if(isset($this->photos[$id])) $animal=$this->photos[$id];
     };
   } elseif($entity instanceof \TYPO3\Flow\Resource\ResourcePointer) {
     $animal=$this->animalRepository->findByPhotoPointer($entity)->getFirst();     	
     if(empty($animal)){
       $id=$this->persistenceManager->getIdentifierByObject($entity);
       if(isset($this->photospointer[$id])) $animal=$this->photospointer[$id];
     };
   } elseif($entity instanceof \DLigo\Animaltool\Domain\Model\Animal) {
     $animal=$entity;     	
   };
   if(!empty($animal)) return $animal;
   return '';   
  }
  
  private function putAnimal($entity,$meta){
    if($meta->getName()=="DLigo\Animaltool\Domain\Model\Animal"){
      $owner=$entity->getOwner();
      $owner=(!empty($owner))? $this->persistenceManager->getIdentifierByObject($entity->getOwner()):null;
      if(!empty($owner))$this->owners[$owner]=$entity;
      $photo=$entity->getPhoto();
      $photoid=(!empty($photo))? $this->persistenceManager->getIdentifierByObject($photo):null;
      if(!empty($photoid)) $this->photos[$photoid]=$entity;
      if(!empty($photo)){
        $photopointer=$photo->getResourcePointer();
        $photopointer=(!empty($photopointer))? $this->persistenceManager->getIdentifierByObject($photopointer):null;
        if(!empty($photopointer)) $this->photospointer[$photopointer]=$entity;
      }
    };
  }
}