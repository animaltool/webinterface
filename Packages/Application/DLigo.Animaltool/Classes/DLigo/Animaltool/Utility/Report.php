<?php
namespace DLigo\Animaltool\Utility;

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
 * @Flow\Scope("singleton")
 */
class Report {

  /**
   * @Flow\Inject
   * @var \Doctrine\Common\Persistence\ObjectManager
   */
  protected $entityManager;
 
  public function countAllSpeciesAndTeam(\DateTime $from,\DateTime $to=null,\DLigo\Animaltool\Domain\Model\Location $location=null,$addWhere=null,$useReleaseDate=false){
    $s='count(DISTINCT a) ,s.name as species,t.firstname,t.lastname,t.teamID
      FROM \DLigo\Animaltool\Domain\Model\Animal a 
      JOIN a.species s
      JOIN a.actions ac
      JOIN ac.team t';
    $g='GROUP BY s.name,t.teamID
      ORDER BY s.name,t.teamID';
    $ret=$this->countBy($from,$to,$location,$addWhere,($useReleaseDate ? 'ac.releaseDate' : 'ac.date'),$s,$g);
    return $ret;                  
  }            

  public function countAllTherapyAndTeam(\DateTime $from,\DateTime $to=null,\DLigo\Animaltool\Domain\Model\Location $location=null,$addWhere=null){
    $s='count(DISTINCT a) ,th.name as therapy,t.firstname,t.lastname,t.teamID,s.name
      FROM \DLigo\Animaltool\Domain\Model\Treatment tr 
      JOIN tr.animal a
      JOIN tr.therapies th
      JOIN tr.doctor t
      JOIN a.species s';
    $g='GROUP BY th.name,t.teamID
      ORDER BY th.name,t.teamID';
    $ret=$this->countBy($from,$to,$location,$addWhere,'tr.startDate',$s,$g);
    return $ret;                  
  }            

  public function countAllSpeciesAndStayStatus(\DateTime $from,\DateTime $to=null,\DLigo\Animaltool\Domain\Model\Location $location=null,$addWhere=null){
    $s='count(DISTINCT a),s.name as species ,a.stayStatus
      FROM \DLigo\Animaltool\Domain\Model\Animal a
      JOIN a.species s
      JOIN a.actions ac';
    $g='GROUP BY s.name,a.stayStatus
      ORDER BY s.name,a.stayStatus';
    $ret=$this->countBy($from,$to,$location,$addWhere,'ac.date',$s,$g);
    return $ret;                  
  }             

  public function countAllSpeciesAndTherapyStatus(\DateTime $from,\DateTime $to=null,\DLigo\Animaltool\Domain\Model\Location $location=null,$addWhere=null){
    $s='count(DISTINCT a),s.name as species,a.therapyStatus
      FROM \DLigo\Animaltool\Domain\Model\Animal a
      JOIN a.species s
      JOIN a.actions ac';
    $g='GROUP BY s.name,a.therapyStatus
      ORDER BY s.name,a.therapyStatus';
    $ret=$this->countBy($from,$to,$location,$addWhere,'ac.date',$s,$g);
    return $ret;                  
  }            

  public function countAllSpeciesAndTherapyTeam(\DateTime $from,\DateTime $to=null,\DLigo\Animaltool\Domain\Model\Location $location=null,$addWhere=null){
    $s='count(DISTINCT a) ,s.name as species,t.firstname,t.lastname,t.teamID
      FROM \DLigo\Animaltool\Domain\Model\Animal a 
      JOIN a.treatments tr
      JOIN a.species s
      JOIN tr.doctor t';
    $g='GROUP BY s.name,t.teamID
      ORDER BY s.name,t.teamID';
    $ret=$this->countBy($from,$to,$location,$addWhere,'tr.startDate',$s,$g);
    return $ret;                  
  }      

  public function countAllSpeciesAndGender(\DateTime $from,\DateTime $to=null,\DLigo\Animaltool\Domain\Model\Location $location=null,$addWhere=null,$useTreatmentDate=false){
    $left=($useTreatmentDate ? '' : 'LEFT ');
    $s='count(DISTINCT a),s.name as species,a.gender
      FROM \DLigo\Animaltool\Domain\Model\Animal a 
      JOIN a.species s
      '.$left.'JOIN a.treatments tr
      '.$left.'JOIN tr.therapies th
      JOIN a.actions ac';
    $g='GROUP BY s.name,a.gender
      ORDER BY s.name,a.gender';
    $ret=$this->countBy($from,$to,$location,$addWhere,($useTreatmentDate ? 'tr.startDate' : 'ac.date'),$s,$g);    
    return $ret;                  
  }            

  public function countAllSpeciesAndSource(\DateTime $from,\DateTime $to=null,\DLigo\Animaltool\Domain\Model\Location $location=null,$addWhere=null){
    $s='count(DISTINCT a) ,s.name as species,CASE WHEN ac.lat=0 AND ac.lng=0 THEN 1 ELSE 0 AS web
      FROM \DLigo\Animaltool\Domain\Model\Animal a 
      JOIN a.species s
      JOIN a.actions ac';
    $g='GROUP BY s.name,web
      ORDER BY s.name,web';
    $ret=$this->countBy($from,$to,$location,$addWhere,'ac.date',$s,$g);
    return $ret;                  
  }            

  public function countAllSpeciesAndOwnerStat(\DateTime $from,\DateTime $to=null,\DLigo\Animaltool\Domain\Model\Location $location=null,$addWhere=null){
    $s='count(DISTINCT a) ,s.name as species,CASE WHEN a.owner IS NOT NULL THEN 1 ELSE 0 AS ownerStat
      FROM \DLigo\Animaltool\Domain\Model\Animal a 
      JOIN a.species s
      JOIN a.actions ac';
    $g='GROUP BY s.name,ownerStat
      ORDER BY s.name,ownerStat';
    $ret=$this->countBy($from,$to,$location,$addWhere,'ac.date',$s,$g);
    return $ret;                  
  }            

  public function countAllSpeciesAndTagType(\DateTime $from,\DateTime $to=null,\DLigo\Animaltool\Domain\Model\Location $location=null,$addWhere=null){
    $s='count(DISTINCT a) ,s.name as species,CASE WHEN a.rFID IS NOT NULL AND a.rFID!=\'\' THEN 2 WHEN a.earTag IS NOT NULL AND a.earTag!=\'\' THEN 1 ELSE 0 AS tagType
      FROM \DLigo\Animaltool\Domain\Model\Animal a 
      JOIN a.species s
      JOIN a.actions ac';
    $g='GROUP BY s.name,tagType
      ORDER BY s.name,tagType';
    $ret=$this->countBy($from,$to,$location,$addWhere,'ac.date',$s,$g);
    return $ret;                  
  }            

  public function buildReportTable($counts,$rowId,$colId,$rowFunc=null,$colFunc=null,$flip=false){
    if($rowFunc==null) $rowFunc=function($item) use ($rowId){
        return $item[$rowId];
      };
    if($colFunc==null) $colFunc=function($item) use ($colId){
        return $item[$colId];
      };
    $ret=array();
    foreach($counts as $item){
      $r=$item[$rowId];
      $c=$item[$colId];
      if(!isset($ret['total']['col'][$c])){
        $ret['total']['col'][$c]=0;
        $ret['head']['col'][$c]=$colFunc($item);          
      };
      if(!isset($ret['total']['row'][$r])){
        $ret['total']['row'][$r]=0;
        $ret['head']['row'][$r]=$rowFunc($item);          
      };
      if(!isset($ret['total']['total'])) $ret['total']['total']=0;
      $ret['data'][$r][$c]=$item[1];
      $ret['total']['col'][$c]+=$item[1];
      $ret['total']['row'][$r]+=$item[1];
      $ret['total']['total']+=$item[1];        
    };
    if($flip && !empty($ret)){
      $ret2['total']['col']=$ret['total']['row'];
      $ret2['head']['col']=$ret['head']['row'];          
      $ret2['total']['row']=$ret['total']['col'];
      $ret2['head']['row']=$ret['head']['col'];          
      $ret2['total']['total']=$ret['total']['total'];
      foreach($ret['data'] as $r=>$row)
        foreach($row as $c=>$val)
          $data[$c][$r]=$val;      
      $ret2['data']=$data;
      $ret=$ret2;
    };
    return $ret;
  } 
  
  public function getTo(\DateTime $from,\DateTime $to=null){
    $from->setTime(0,0,0);
    if($to==null){
      $to2=clone $from;
    } else $to2=clone $to;
    $to2->setTime(0,0,0);
    $to2->modify('+1 DAY');
    return $to2;
  }
  
  private function countBy($from,$to,$location,$addWhere,$datefield,$selectAndFrom,$groupAndOrder){
    $constraints=array();
    $to2=$this->getTo($from,$to);
    $constraints[]=$datefield.'>=:from';
    $params['from']=$from;
    $constraints[]=$datefield.'<:to';
    $params['to']=$to2;
    if(!empty($location)){
      $constraints[]='a.location=:loc';
      $params['loc']=$location;
    };
    $constraints[]='a.isDummy=0';
    if($addWhere!=null){
      $constraints[]=$addWhere;
    };
    $where=' WHERE '.implode(' AND ',$constraints);
    $query = $this->entityManager->createQuery('SELECT '.$selectAndFrom.' '.$where.' '.$groupAndOrder);
    $ret=$query->execute($params);
    return $ret;                  
  }      

}