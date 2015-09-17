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

function checkDateInput() {
    var input = document.createElement('input');
    input.setAttribute('type','date');

    var notADateValue = 'not-a-date';
    input.setAttribute('value', notADateValue); 

    return !(input.value === notADateValue);
}

function speciesSelect(){
  if($('select#species').length>0){
    var i=$('select#species')[0].selectedIndex;
    $('.bread-select').each(function(){
      if($(this).attr('id')=='bread-'+i) $(this).show(); else $(this).hide(); 
    });
    if($('#usetag-'+i).val()) $('.field-rfid').show();else $('.field-rfid').hide();
  }
}

$( document ).ready(function() {
  $('#animal-filter').change(function(){
    $('#animal-filter-form').submit();
  });
  $('#animal-filter-form input').change(function(){
    $('#animal-filter-form').submit();
  });

  $('#location-select').change(function(){
    $('#location-select-form').submit();
  });
  
  $('#qicksearch-form').submit(function(){
    if($('#quicksearch-sword').val().trim().length==0) return false;
  });
  
  speciesSelect();
  $('select#species').change(function(){
    speciesSelect();
  });
  
  if($('#isPrivate').is(":checked")) {
    $('.field-owner').show();
  } else {
    $('.field-owner').hide();
  };
  $('#isPrivate').change(function(){
    if($('#isPrivate').is(":checked")) {
      $('.field-owner').show();
    } else {
      $('.field-owner').hide();
    };
  });
 
  $('.field-therapy input').each(function(){
    var split=$(this).attr('id').split('-');
    var id=split[split.length-1];
    var mid=(split.length>2)? split[1]+'-':'';
    if($(this).is(":checked")) {
      $('#treatmentextra-'+mid+id).parent().show();
    } else {
      $('#treatmentextra-'+mid+id).parent().hide();
    };
  });
/*  var th_checked=0;
  $('.field-therapy input:checked').each(function(){
    th_checked++;
  });  
  if(th_checked==0){
    $('.button[name="continue"]').hide();
  } else {
    $('.button[name="continue"]').show();
  } */
  $('.field-therapy input').change(function(){
    var split=$(this).attr('id').split('-');
    var id=split[split.length-1];
    var mid=(split.length>2)? split[1]+'-':'';
    if($(this).is(":checked")) {
//      th_checked+=1;
      $('#treatmentextra-'+mid+id).parent().show();
    } else {
//      th_checked-=1;
      $('#treatmentextra-'+mid+id).parent().hide();
    };
/*    if(th_checked==0){
      $('.button[name="continue"]').hide();
    } else {
      $('.button[name="continue"]').show();
    }*/
  });
  var treat_old=$('#form-treatment').serialize();
  $('.button[name="continue"]').hide();
  $('#form-treatment :input').change(function(){
    var current=$('#form-treatment').serialize();
    if(treat_old!=current){
      $('.button[name="continue"]').show();
    } else {
      $('.button[name="continue"]').hide();
    }
  });

  if(!checkDateInput()){
  $('.field-date input,input.date-input').each(function(){
    var input=$(this);
    input.DatePicker({
      onChange: function(formated, dates){
        input.val(formated);
      },
      format: 'Y-m-d',
      date: input.val(),
      onRender: function(date) {
		    return {
		      disabled: (date.valueOf() > Date.now().valueOf())
    		}
    	},
    });
  });
  }

  $(".field-externaltype-shelter input").each(function(){
    var split=$(this).attr('id').split('-');
    var id=split.length==3?'-'+split[2]:'';
    if($(this).is(':checked')) {
      $("#ispermanent"+id).parent().show();
    } else {
      $("#ispermanent"+id).parent().hide();
    };
  });
  $(".field-externaltype-adoption input").each(function(){
    var split=$(this).attr('id').split('-');
    var id=split.length==3?'-'+split[2]:'';
    var name;
    if($('#ename'+id).length>0)name='#ename'; else name='#name';
    if($(this).is(':checked')) {
      $(name+id).parent().hide();
    } else {
      $(name+id).parent().show();
    };
  });
  $('.field-externaltype .field-radio input').change(function(){
    var split=$(this).attr('id').split('-');
    var id=split.length==3?'-'+split[2]:'';
    var name;
    if($('#ename'+id).length>0)name='#ename'; else name='#name';
    if($("#externaltype-shelter"+id).is(':checked')) {
      $("#ispermanent"+id).parent().show();
    } else {
      $("#ispermanent"+id).parent().hide();
    };
    if($("#externaltype-adoption"+id).is(':checked')) {
      $(name+id).parent().hide();
    } else {
      $(name+id).parent().show();
    };
  });
  
  $('#isUnique').change(function(){
    if($(this).is(':checked')) $('#hasExtraData').prop('checked',false);
  });
  $('#hasExtraData').change(function(){
    if($(this).is(':checked')) $('#isUnique').prop('checked',false);
  });
  
  $('input.confirm').click(function(){
    if(confirm(lang.confirm)){
      return true;
    } else {
      return false;
    }
  })
  
  $('.toggle').each(function(){
    if($(this).data('init')=='off'){
      var id='#'+$(this).data('toggle');
      $(id).hide();
      $(this).data('state','off');
    } else {
      $(this).data('state','on');
    };
  });
  $('.toggle').click(function(){
    var id='#'+$(this).data('toggle');
    var title;
    if($(this).data('state')=='on'){
      $(id).hide();
      title=$(this).data('off');
      $(this).data('state','off');
    } else {
      $(id).show();
      title=$(this).data('on');
      $(this).data('state','on');
    };
    $(this).html(title);
    return false;
  });
  
  $('.link-button,.button').mouseup(function(){
    $(this).blur();
  });

});