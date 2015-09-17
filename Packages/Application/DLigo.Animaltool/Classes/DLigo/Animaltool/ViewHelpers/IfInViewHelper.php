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

use TYPO3\Fluid\Core\ViewHelper\AbstractConditionViewHelper;


class IfInViewHelper extends AbstractConditionViewHelper {
	/**
	 * @param $is string Compare Value  
	 * @param $in mixed Values 
	 * @return string the rendered string
	 */	 	
	public function render($is,$in) {
    if($in==null) return $this->renderElseChild();
    if(!is_array($in)){
      try{
        $in=$in->toArray();
      } catch(Exception $e){
  			return $this->renderElseChild();
      };
    };
		if (in_array($is,$in)) {
			return $this->renderThenChild();
		} else {
			return $this->renderElseChild();
		}
	}
}

?>