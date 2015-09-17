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
 * Validator for animals
 */
class AnimalExistsValidator extends \TYPO3\Flow\Validation\Validator\AbstractValidator {

	/**
	 * @Flow\Inject
	 * @var DLigo\Animaltool\Domain\Repository\AnimalRepository
	 */
	protected $animalRepository;
  
  /**
   * var string
   */     
  protected $tag;

	/**
	 * @param mixed $value The value that should be validated
	 * @return void
	 * @throws \TYPO3\Flow\Validation\Exception\InvalidSubjectException
	 */
	protected function isValid($value) {
    $animal=null;
    if($this->tag!=$value->getRFID()) $animal=$this->animalRepository->findOneByRFID($this->tag);       

		if ($animal == null) {
      if($this->tag!=$value->getEarTag())$animal=$this->animalRepository->findOneByEarTag($this->tag);
		}
		if ($animal != null) {
	 		$this->addError('The animal with this RFID/Ear tag already exists.', 9996);
	 	}
	}
  
  public function setTag($tag){
    $this->tag=$tag;
  }

}
