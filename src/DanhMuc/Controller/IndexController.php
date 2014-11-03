<?php
	namespace DanhMuc\Controller;

	 use Zend\Mvc\Controller\AbstractActionController;
	 use Zend\View\Model\ViewModel;	 
 	 use DanhMuc\Entity\DanhMuc;
 	 use DanhMuc\Form\DanhMucForm;


 	 use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as DoctrineAdapter;
 	 use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
 	 use Zend\Paginator\Paginator;


 	 use Zend\EventManager\SharedEventManager;
 	 use Zend\EventManager\EventManager;
	 use Zend\EventManager\EventManagerAwareInterface;
	 use Zend\EventManager\EventManagerInterface;
	 use ZfcBase\EventManager\EventProvider;
	 use Zend\ServiceManager\ServiceManager;
	 use DanhMuc\Service\DanhMucService;

	 
	 class IndexController extends AbstractActionController
	 {
	 	 protected $albumTable;
	 	 private $entityManager;
	 	

	 	 public function getEntityManager()
	 	 {
	 	 	if(!$this->entityManager)
	 	 	{
	 	 		$this->entityManager=$this->getServiceLocator()->get('Doctrine\ORM\EntityManager'); 	 	 		
	 	 	}
	 	 	return $this->entityManager;
	 	 }
	     public function indexAction()
	     {    	 
	     	
	     	$plugin = $this->MyPlugin();
        	$gioithieu=$plugin->hamInHelloChao();

	     	// hiển thị bình thường (hiển thị tất cả các trường có trong cơ sở dữ liệu lên 1 trang duy nhất)
	     	
	     	
	     	$objectManager= $this->getEntityManager();
	     	$paginator = $objectManager->getRepository('DanhMuc\Entity\DanhMuc')->findAll();
	     	
		
	     
	     	// hiển thị theo kiểu phân trang có thêm phần DanhMucNavigationFactory.php trong Service mới chạy được
	     	$objectManager = $this->getEntityManager();
	        $repository = $objectManager->getRepository('DanhMuc\Entity\DanhMuc');
	        $queryBuilder = $repository->createQueryBuilder('dm');
	        $query = $queryBuilder->getQuery();
	        $danhMuc = $query->execute();
	        //$queryBuilder->add('orderby', 'dm.cha');
	        
	        /*
	        $adapter = new DoctrineAdapter(new ORMPaginator($queryBuilder));
	        
	        $paginator = new Paginator($adapter);
	        $paginator->setDefaultItemCountPerPage(5);     
	        $page = (int)$this->params('page');
	        if($page) 
	            $paginator->setCurrentPageNumber($page);
	        */

	        $plugin = $this->MyPlugin();
        	$danhMuc=$plugin->xuatMenu($danhMuc, $root = null);
	        
	        return array(
	            'paginator' => $paginator,
	            'danhMuc'	=> $danhMuc,
	            'gioithieu'			=> $gioithieu
	        );
		
	        
	        if(!$this->ZfcUserAuthentication()->hasIdentity())
	        {
	        	return $this->redirect()->toRoute('home');
	        }     


	     }


	     public function addAction()
	     {
	     	$objectManager= $this->getEntityManager();	
	     	
	     	 $form = new DanhMucForm($objectManager); 
	     	 $album = new DanhMuc();
	     	    	
	     	 $form->bind($album);
	     	 //var_dump($form);
	    // 	 die(var_dump($album));
	     	 
	     	 $request = $this->getRequest();
	         if ($request->isPost()) {

	         	
	             
	             //$form->setInputFilter($album->getInputFilter());
	             $form->setData($request->getPost());

	             if ($form->isValid()) {
	             	$objectManager->persist($album);// là phương thức add album
	             	$objectManager->flush();  

	             	// bật trigger
	             	//$this->getEventManager()->trigger(__FUNCTION__, $this);	             	
			         $phatEV = $this->getServiceLocator()->get('danh_muc_service');
	        		 $phatEV->taoMoiDanhMucService($this); 


	                // Redirect to list of albums					
					
	                return $this->redirect()->toRoute('danh_muc');
	             }
	         }
	         return array('form' => $form);
	     }


	    
	     public function hamBoCon($danhMucs,$id)
	     {
	     	foreach ($danhMucs as $vtDanhMuc=>$danhMuc) {
	     		if($danhMuc->getIdCha()==$id)
	     		{
	     			unset($danhMucs[$vtDanhMuc]);
	     			$danhMucs=$this->hamBoCon($danhMucs,$danhMuc->getId());
	     			
	     		}	     		
	     	}	     	
	     	return $danhMucs;
	     }

	     public function editAction()
	     {
	     	$id = (int) $this->params()->fromRoute('id', 0);
	         if (!$id) {
	             return $this->redirect()->toRoute('danh_muc', array(
	                 'action' => 'add'
	             ));
	         }

	         // Get the Album with the specified id.  An exception is thrown
	         // if it cannot be found, in which case go to the index page.
	         


	         $objectManager= $this->getEntityManager();
	         $form = new DanhMucForm($objectManager); 
	     	 $idDanhMuc = $objectManager->getRepository('DanhMuc\Entity\DanhMuc')->find($id);
	         $form->bind($idDanhMuc);
	         die(var_dump($idDanhMuc));
	         $form->get('submit')->setAttribute('value', 'Edit');

	         //var_dump($form);
	         // tạo câu lệnh trong doctrine thêm dữ liệu theo điều kiện
	         
	         $repository = $objectManager->getRepository('DanhMuc\Entity\DanhMuc');
	         $queryBuilder = $repository->createQueryBuilder('dm');
	         $queryBuilder->add('where','dm.id !='.$idDanhMuc->getId());
	         $query = $queryBuilder->getQuery();
	         $danhMuc = $query->execute();
	         // kết thúc tạo câu lệnh trong doctrine

	         //var_dump($album->getId());
	         //var_dump($danhMuc);
	         $danhMuc=$this->hamBoCon($danhMuc,$idDanhMuc->getId());
	         
	         //var_dump($danhMuc);
	         $request = $this->getRequest();
	         if ($request->isPost()) {
	            // $form->setInputFilter($album->getInputFilter());
	             $form->setData($request->getPost());

	             if ($form->isValid()) {
	                
	                $objectManager->flush();// flush

	                 // Redirect to list of albums
	                 return $this->redirect()->toRoute('danh_muc');
	             }
	         }

	         return array(
	             'id' => $id,
	             'form' => $form,
	             'danhMuc' => $danhMuc
	         );
	         
	     }

	     public function deleteAction()
	     {
	     	 $id = (int) $this->params()->fromRoute('id', 0);
	         if (!$id) {
	             return $this->redirect()->toRoute('danh_muc');
	         }

	         $objectManager= $this->getEntityManager();
	         $form = new DanhMucForm($objectManager); 
	     	 $album = $objectManager->getRepository('DanhMuc\Entity\DanhMuc')->find($id);
	         if(!$album)
	         {
	         	return $this->redirect()->toRoute('danh_muc');
	         }

	         $request = $this->getRequest();
	         if ($request->isPost()) {
	             $del = $request->getPost('del', 'No');

	             if ($del == 'Xóa nó đi') {
	                 $id = (int) $request->getPost('id');	                 
	                 $objectManager->remove($album);
	             	 $objectManager->flush();      
	             }

	             // Redirect to list of albums
	             return $this->redirect()->toRoute('danh_muc');
	         }

	         return array(
	             'id'    => $id,
	             'album' => $album,
	         );
	     	 
	     }

	     public function deleteMultiAction()
	     {
	     	 $request = $this->getRequest();
	     	 if ($request->isPost()) {	     	 	
		     	 $del = $request->getPost('btnXoaNhieu');
		     	 $checkId = $request->getPost('check');	
		     	 $objectManager= $this->getEntityManager();
				 $form = new DanhMucForm($objectManager); 
		     	 if($del=='Xóa hết')
		     	 {

				     $repository = $objectManager->getRepository('DanhMuc\Entity\DanhMuc');
				     $queryBuilder = $repository->createQueryBuilder('dm');
				     $tam='';
			     	 $i=0;
			     	 foreach ($checkId as $id) {
			     	 	$i++;
			     	 	$tam.='dm.id='.$id.' ';
			     	 	if($i<count($checkId))
			     	 	{
			     	 		$tam.='or ';
			     	 	}
			     	 }
			         $queryBuilder->add('where',$tam);
			         $query = $queryBuilder->getQuery();
			         $danhMucs = $query->execute();
			         return array(		      
		             	'danhMucs' => $danhMucs,
		             	);
		     	 }
		     	 	     	 
		     	 
		     	 if($del=='Ok')	
		     	 {
			     	 foreach ($checkId as $id) {     	 			     	 
			     	 	$deleteId = $objectManager->getRepository('DanhMuc\Entity\DanhMuc')->find($id);
			     	 	$objectManager->remove($deleteId);
			            $objectManager->flush();    
			             
		     	 	}
		     	 	$phatEV = $this->getServiceLocator()->get('danh_muc_service');
	        		$phatEV->xoaNhieuDanhMucService($this);
		     	 	return $this->redirect()->toRoute('danh_muc'); 

		     	 }
		     	 if($del=='No')	
		     	 {
		     	 	return $this->redirect()->toRoute('danh_muc');
		     	 }

		     }
	     }
        
	 }
?>