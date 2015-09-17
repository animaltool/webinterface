<?php
namespace DLigo\Animaltool\Validation\Validator;
 
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
*
*/
class TreatmentValidator extends \TYPO3\Flow\Validation\Validator\AbstractValidator {

  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Domain\Repository\TherapyRepository
   */
   protected $therapyRepository;

  /**
   * @Flow\Inject
   * @var DLigo\Animaltool\Domain\Repository\AnimalRepository
   */
   protected $animalRepository;
   
  /**
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 * @Flow\inject
	 */
	protected $persistenceManager;

   private $therapies=array();

	/**
	 * @var array
	 */
	protected $supportedOptions = array(
		'isArray' => array(false, 'The argument is an array of treatments', 'boolean'),
	);


    /**
     * @param array $value
     * @return void
     */
    protected function isValid($value) {
      if(!$this->options['isArray']) $value=array($value);
      foreach($value as $treatment) {
        if(is_array($treatment['therapies'])) foreach($treatment['therapies'] as $thid){
          $therapy=$this->therapyRepository->findByIdentifier($thid);
          if($therapy->getHasExtraData()){
            if(empty($treatment['treatmentExtra'][$thid])) $this->addError('You have to fill in extra data for the therapy "' . $therapy->getName().'".', 9995,array($therapy->getName()));  
          }         
          if($therapy->getIsUnique() && isset($this->therapies[$thid])) $this->addError('The therapy "' . $therapy->getName().'" can only be used once.', 9997,array($therapy->getName()));
          $this->therapies[$thid]=true;
        }
      }
    }
    
    public function setAnimal($id){
      $animal=$this->animalRepository->findByIdentifier($id);
      foreach($animal->getTreatments() as $tr)
        foreach($tr->getTherapies() as $th)
          $this->therapies[$this->persistenceManager->getIdentifierByObject($th)]=true;
    }
 
}