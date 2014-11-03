<?php
namespace DanhMuc\Navigation\Service;
use Zend\Navigation\Service\DefaultNavigationFactory;// Navigation dùng để tạo menu
use Zend\ServiceManager\ServiceLocatorInterface; // sử dụng để lấy objecmanager để truy vấn cơ sở dữ liệu
use Zend\DanhMuc\Entity\DanhMuc;// lấy dữ liệu trong cơ sở dữ liệu thông qua doctrine
/**
* 
*/

// để sử dụng dduocj class NavigationFactory phải khai báo trong config
/*
'service_manager' => array(
        'factories'=> array(
            'danh_muc' => 'DanhMuc\Navigation\Service\DanhMucNavigationFactory',
            ),
        ),
*/
class DanhMucNavigationFactory extends DefaultNavigationFactory 
{ 
	protected function getName(){
		return 'danh_muc';
	}

	protected function getPages(ServiceLocatorInterface $serviceLocator)
	{

		$objecManager=$serviceLocator->get('Doctrine\ORM\EntityManager');
		$dms=$objecManager->getRepository('DanhMuc\Entity\DanhMuc')->findAll();
        //var_dump($dms);
		
		if (null === $this->pages) {
            $config = $this->parseTree($dms);     
            //$config=array();       
            $pages = $this->getPagesFromConfig($config);
            $this->pages = $this->preparePages($serviceLocator, $pages);
            }        
        return $this->pages;

	}

    private $level=0;
    //private $co=0;// cái này tự tôi làm có thể sai và có thể bỏ nhe!

	public function parseTree($tree, $root = null) {
        /*

        if($this->co==0&&$tree==null)// cái này tự tôi làm có thể sai và có thể bỏ nhe!
        {// cái này tự tôi làm có thể sai và có thể bỏ nhe!
            return array();// cái này tự tôi làm có thể sai và có thể bỏ nhe!
        }// cái này tự tôi làm có thể sai và có thể bỏ nhe!
        $this->co++;// cái này tự tôi làm có thể sai và có thể bỏ nhe!
        */
        $return = array();        
        foreach($tree as $i=>$child) {
            $parent = $child->getCha();
            if($parent == $root) {
                unset($tree[$i]);
                $this->level++;
                $pages = $this->parseTree($tree, $child);
                if($pages)
                    $return[] = array(
                        'label' => ($this->level==1)? $child->getTen().'<span class="caret"></span>': $child->getTen(),
                        'route' => 'danh_muc',
                        'wrapClass' => ($this->level>1)?'dropdown menu-item dropdown-submenu':'dropdown menu-item',         // class to <li>
                        'class'     => 'dropdown-toggle',  // class to <a> like usual
                        'attribs'   => array(
                        'data-toggle' => 'dropdown',  // Key = Attr name, Value = Attr Val                        
                        ),
                        'pagesContainerClass' => 'dropdown-menu',
                        'params' => array('action'=>'view', 'id'=>$child->getId()),
                        'pages' => $pages
                    );
                else
                    $return[] = array(
                        'label' => $child->getTen(),
                        'route' => 'danh_muc',
                        'params' => array('action'=>'view', 'id'=>$child->getId()),
                        'pagesContainerClass' => 'dropdown-menu',
                    );
                $this->level--;
            }
        }
        //return empty($return) ? null : $return;    
        return $return;
    }

	
	
}
?>