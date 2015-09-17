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
use DLigo\Animaltool\Domain\Model\Color;

class ColorController extends AbstractController {

	/**
	 * @Flow\Inject
	 * @var \DLigo\Animaltool\Domain\Repository\ColorRepository
	 */
	protected $colorRepository;

	/**
	 * @param string $colors
	 * @return void
	 */
	public function indexAction($colors=null) {
    $oldColors = new \Doctrine\Common\Collections\ArrayCollection($this->colorRepository->findAll()->toArray());
    $colorNames="";
    if($colors===null) {
      foreach($oldColors as $color){
        if(!empty($colorNames)) $colorNames.="\n";
        $colorNames.=$color->getName();
      }    
    } else {
      $colorNames=$colors;
      foreach(explode("\n",$colors) as $colorName){
        $colorName=trim($colorName);
        if(empty($colorName)) continue;
        $color=$this->colorRepository->findOneByName($colorName);
        if(empty($color)){
          $color=new Color($colorName);
          $this->colorRepository->add($color);
        };   
        if($oldColors) $oldColors->removeElement($color);
      };      
      if($oldColors) foreach($oldColors as $color){
        $this->colorRepository->remove($color);  
      }
    }
		$this->view->assign('colors', $colorNames);
	}
}