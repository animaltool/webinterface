<?php
namespace DLigo\Animaltool\Domain\Repository;
use DLigo\Animaltool\Domain\Model\Animal;

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
use TYPO3\Flow\Persistence\Repository;

/**
 * @Flow\Scope("singleton")
 */
class AnimalRepository extends Repository {

  protected $defaultOrderings = array('actions.date' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_DESCENDING);

	/**
	 * @var \TYPO3\Flow\Object\ObjectManagerInterface
	 * @Flow\Inject
	 */
	protected $objectManager;

  /**
   * @var integer
   * @Flow\Inject(setting="image.width")
   */
  protected $imageWidth;
        
  /**
   * @var integer
   * @Flow\Inject(setting="image.height")
   */
  protected $imageHeight;
 
  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Utility\Report
   */
   protected $reportUtility;

  /**
   * @param string $sword RFID or BoxID
   */     
	public function findQuick($sword){
    $query = $this->createQuery();
    $query->matching(
                $query->logicalOr(
                  $query->equals('rFID', $sword),
                  $query->like('earTag', '%'.$sword.'%',false),
                  $query->like('actions.boxID', '%'.$sword.'%',false),
                  $query->like('owner.street', '%'.$sword.'%',false)
                )
              )->setOrderings(array('actions.date' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_DESCENDING));
    return $query->execute();                  
  }                            
  
  public function filterLocation(\TYPO3\Flow\Persistence\QueryResultInterface $objects, \DLigo\Animaltool\Domain\Model\Location $location=null,$from=null,$to=null) {
    $query = clone $objects->getQuery();
		$constraint = $query->getConstraint();
    $dateConstr=null;
    $constraints=array();
    if($from){
      $dateConstr=$this->getDateConstraint($query,$from,$to);
    };
		if ($constraint !== NULL) {
      $constraints[]=$constraint;
    };
    if($location !== NULL){
      $constraints[]=$query->equals('location',$location);
    };
		if ($dateConstr !== NULL) {
      $constraints[]=$dateConstr;
    };      
		$query->matching($query->logicalAnd(
  		$constraints        
    ));
    
    $query->makeDistinct();
//\TYPO3\Flow\var_dump($constraints);              
//    $qb=\TYPO3\Flow\Reflection\ObjectAccess::getProperty($query, 'queryBuilder', TRUE);
//\TYPO3\Flow\var_dump($qb->getQuery()->getSQL());              
    return $query->execute();          
  }    

	public function findTreatable(){
    $query = $this->createQuery();
    $query->matching(
                $query->logicalOr(
                  $query->equals('therapyStatus', Animal::WAIT),
                  $query->equals('stayStatus', Animal::CATCHED)
                )
              )->setOrderings(array('actions.date' => \TYPO3\Flow\Persistence\QueryInterface::ORDER_DESCENDING));
    return $query->execute();                  
  }                            

  public function hideDummies(\TYPO3\Flow\Persistence\QueryResultInterface $objects) {
    $query = clone $objects->getQuery();
		$constraint = $query->getConstraint();
		if ($constraint !== NULL) {
  		$query->matching($query->logicalAnd(
	   		$constraint,
        $query->equals('isDummy',false)
      ));
 		} else {
			$query->matching(
        $query->equals('isDummy',false)
			);
		}
    
    return $query->execute();          
  }    
  
  protected function processPhoto($animal){
    $photores=$animal->getPhoto();
    if($photores){
      $size=getimagesize('resource://' . $photores);
      if($size[0]>$this->imageWidth || $size[1]>$this->imageHeight){
  	 	  $imagine = $this->objectManager->get('Imagine\Image\ImagineInterface');
	  		$imagineImage = $imagine->open('resource://' . $photores);
        $box=$this->objectManager->get('Imagine\Image\Box', $this->imageWidth, $this->imageHeight);
			  $imagineImage = $imagineImage->thumbnail($box,\Imagine\Image\ManipulatorInterface::THUMBNAIL_INSET);
			  file_put_contents('resource://' . $photores, $imagineImage->get('png'));
      };  
    };
  }
  
	public function add($animal) {
    $this->processPhoto($animal);
    parent::add($animal);
  }

	public function update($animal) {
    $this->processPhoto($animal);
    parent::update($animal);
  }
  
  /**
   * @param \TYPO3\Flow\Resource\ResourcePointer $pointer
   */     
	public function findByPhotoPointer(\TYPO3\Flow\Resource\ResourcePointer $pointer){
    $query = $this->createQuery();
    $query->matching(
      $query->equals('photo.resourcePointer', $pointer)
    );
    return $query->execute();                  
  }                
  
  public function findByDate(\DateTime $from,\DateTime $to=null,\DLigo\Animaltool\Domain\Model\Location $location=null,$filterOwner=false,$addConstraint=null){
    $query = $this->createQuery();
    $constraints[]=$query->equals('isDummy',false);
    $constraints[]=$this->getDateConstraint($query,$from,$to);
    if($filterOwner) $constraints[]=$query->logicalNot($query->equals('owner', NULL));
    if($location!=null)$constraints[]=$query->equals('location',$location);
    if($addConstraint!=null){
      $cfunc=$addConstraint['func'];
      $name=$addConstraint['name'];
      $value=$addConstraint['value'];
      $constraints[]=$query->$cfunc($name,$value);
    };
    $query->matching($query->logicalAnd($constraints));
    return $query->execute();                  
  }
  
  private function getDateConstraint($query,$from,$to){
    $to2=$this->reportUtility->getTo($from,$to);
    $constDate[]=$query->logicalAnd($query->greaterThanOrEqual('actions.date',$from),$query->lessThan('actions.date',$to2));
    //$constDate[]=$query->logicalAnd($query->greaterThanOrEqual('treatments.startDate',$from),$query->lessThan('treatments.startDate',$to2));
    return $query->logicalOr($constDate);
  }
    
}