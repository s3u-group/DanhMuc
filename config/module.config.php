<?php
return array(
     'controllers' => array(
         'invokables' => array(
             'DanhMuc\Controller\Index' => 'DanhMuc\Controller\IndexController',

         ),
     ),

     // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
            'danh_muc' => array(
                'type'    => 'literal',
                'options'=>array(
                    'route'=>'/danh-muc',
                    'defaults'=>array(
                        '__NAMESPACE__'=>'DanhMuc\Controller',
                        'controller'=>'Index',
                        'action'=>'index'
                    ),
                ),              

                 'may_terminate'=>true,// tôi có con hãy duyệt con tôi với
                 'child_routes'=>array(
                    'crud'=>array(
                        'type'=>'segment',//tương đối, có thể đầy đủ nhiều phân đoạn hoặc một phân đoạn cũng được
                        'options'=>array(                           
                                 'route'    => '[/][:action][/:id]',
                                 'constraints' => array(
                                     'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                     'id'     => '[0-9]+',
                                 ),
                                 /*
                                 'defaults' => array(
                                     'controller' => 'Index',
                                     'action'     => 'add',
                                 ),
                                 */
                            ),
                        ),                 

                    'paginator'=>array(
                        'type'=>'segment',//tương đối, có thể đầy đủ nhiều phân đoạn hoặc một phân đoạn cũng được
                        'options'=>array(
                            'route'=>'/[trang-:page]',
                            'defaults'=>array(
                                'page'=>1,
                                ),
                            ),

                        ),
             ),
            ),

         ),
     ),

     'view_manager' => array(
        'template_path_stack' => array(
            'danh_muc' => __DIR__ . '/../view',
        
        ),
         'template_map'=>array(
             'danh_muc/partial_danh_muc_phan_trang' => __DIR__ . '/../view/partial/danh-muc/partial-danh-muc-phan-trang.phtml',
         ),  


    ),

     'doctrine' => array(
        'driver' => array(
            // defines an annotation driver with two paths, and names it `my_annotation_driver`
            'danhMuc_annotation_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/DanhMuc/Entity',
                ),
            ),

            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => array(
                'drivers' => array(
                    // register `my_annotation_driver` for any entity under namespace `My\Namespace`
                    'DanhMuc\Entity' => 'danhMuc_annotation_driver',
                ),
            ),
        ),
    ),

     //để sử dụng được navigation trong đường dẫn phía dưới phải khai báo service
     
     'service_manager' => array(
        'factories'=> array(
            'danh_muc' => 'DanhMuc\Navigation\Service\DanhMucNavigationFactory',
            
            ),

        'invokables'=>array(
            // không được để trong factories vì nó không có instan của factories
            'danh_muc_service'=>'DanhMuc\Service\DanhMucService',
            ),
        ),
     

     'view_helpers'=>array(
        'invokables'=>array(
            'my_menu'=>'DanhMuc\View\Helper\MyMenu',
            'makeArray'=>'DanhMuc\View\Helper\MakeArray',  

            ),
        ),




     //phân quyền 
    /* 

     'bjyauthorize'=>array(

        'guards'=>array(
            'BjyAuthorize\Guard\Controller'=>array(
                
                array(
                    'controller'=>array('zfcuser'),                   
                    'roles'     =>array(),
                ),

               

                array(
                    'controller'=>array('DanhMuc\Controller\Index'),
                    'action'    =>array('index','view','login','register'),
                    'roles'     =>array('khach','nguoi-dung'),
                ),

                array(
                    'controller'=>array('DanhMuc\Controller\Index'),
                    'action'    =>array('add','delete','edit'),
                    'roles'     =>array('nguoi-dung'),
                ),
              
            ),
        ),
    ),
   */



    'controller_plugins' => array(
        'invokables' => array(
            'my_plugin' => 'DanhMuc\Controller\Plugin\MyPlugin',
        )
    ),

 );

?>