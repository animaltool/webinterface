<?php
namespace DLigo\Animaltool\ViewHelpers;

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

use TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper;


class ArrayViewHelper extends AbstractViewHelper {
	/**
	 * @param $obj  object Object
	 * @param $prop	mixed Property
	 * @param $comp string search in Arrray and use $obj[]->get$comp()==$prop
	 * @param $flip boolean flip keys and Values of the array      
	 * @return string $obj[$prop]   
	 */	 	
	public function render($obj,$prop,$comp='',$flip=false) {
    if(is_array($prop)){
      $nprop=array_pop($prop);
      if($prop!=null) $obj=$this->render($obj,$prop,$comp,$flip);
      if($obj=='') return '';
      $prop=$nprop;
    };
		if($obj instanceof \Doctrine\Common\Collections\Collection || is_array($obj)) {
      if($comp){
        foreach($obj as $o){
          $get='get'.$comp;
          if($o->$get()==$prop) return (string) $o;
        }
      };
      if(is_array($obj) && $flip) $obj=array_flip($obj);
			if((is_string($prop) || is_integer($prop)) && isset($obj[$prop])) {
				return $obj[$prop];
			}
		} elseif(is_object($obj)) {
			return $obj->$prop;
		}
		return "";
	}
}

?>