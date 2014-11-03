<?php
namespace DanhMuc\View\Helper;

use Zend\View\Helper\AbstractHelper;

class MakeArray extends AbstractHelper{
	public function __invoke($mang){

		$array = array();		
		foreach($mang as $item){
			$array[$item->getId()] = $item->getTen(); 
		}
		return $array;
		
	}
}