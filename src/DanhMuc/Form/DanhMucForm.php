<?php
namespace DanhMuc\Form;

 use Zend\Form\Form;
 use Doctrine\Common\Persistence\ObjectManager;
 use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator;
 use DanhMuc\Entity\DanhMuc;
 use DanhMuc\Form\DanhMucFilter;

 class DanhMucForm extends Form
 {
     private $om;
     public function __construct(ObjectManager $objectManager)
     {
         // we want to ignore the name passed
        //var_dump($objectManager);
         parent::__construct('danh-muc-form');

         
         $this->om=$objectManager;

         $this->setHydrator(new DoctrineHydrator($objectManager))
              ->setObject(new DanhMuc())
              ->setInputFilter(new DanhMucFilter());

         $this->add(array(
             'name' => 'id',
             'type' => 'Hidden',
         ));


         $this->add(array(
             'name' => 'ten',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Tên',
             ),
         ));
         $this->add(array(
             'name' => 'moTa',
             'type' => 'Text',
             'options' => array(
                 'label' => 'Mô tả',
             ),
         ));

         
          $this->add(array(
             'name' => 'cha',
             'type' => 'Select',
             'options' => array(
                 'label' => 'Cha',
                 'value'=>'thanh',
                 'empty_option'=>'----Chọn cha----',
                  'disable_inarray_validator' => true,
                  'value_options'=> $this->getDanhMucChaValueSelect()
                 
             ),
         ));
         
         $this->add(array(
             'name' => 'submit',
             'type' => 'Submit',
             'attributes' => array(
                 'value' => 'Go',
                 'id' => 'submitbutton',
             ),
         ));

        
         
     }

     public function getDanhMucChaValueSelect()
     {
        $valueSelect=array();// tạo một mảng rỗng

        //$valueSelect=array('1'=>'a');// tạo một mảng rỗng
        
        $vss=$this->om->getRepository('DanhMuc\Entity\DanhMuc')->findAll();
        foreach ($vss as $vs) {
        
             $valueSelect[$vs->getId()]=$vs->getTen();      
            
            
        }
    
        return $valueSelect;
     }

    
            
 }
?>