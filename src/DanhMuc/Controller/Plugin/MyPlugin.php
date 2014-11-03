<?php
namespace DanhMuc\Controller\Plugin;
 
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
 
class MyPlugin extends AbstractPlugin{
    public function hamInHelloChao(){
       return $gioithieu='Họ Tên: PHAN VĂN THANH';
    }
    

    private $level=0;
    private $mangTam=array();

    public function xuatMenu($tree, $root = null) {          
        
        foreach($tree as $i=>$child) {        	
            $parent = $child->getCha();
            if($parent == $root) {
                unset($tree[$i]);
                $child->setCap($this->level);       	
            	$this->mangTam[]=$child;
                $this->level++;
                $this->xuatMenu($tree, $child);
                $this->level--;
            } 
            
        }
        
        return $this->mangTam;
 
    }

}