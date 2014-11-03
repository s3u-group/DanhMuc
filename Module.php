<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace DanhMuc;

use Zend\Mvc\MvcEvent;




use Zend\Captcha;
use Zend\Form\Element;
use Zend\Form\Fieldset;
use Zend\Form\Form;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;
use Zend\Form\Factory;


class Module
{

    public function onBootstrap( MVCEvent $e )
    {

       
// khúc này phải có

        $services = $e->getApplication()->getServiceManager();       
        $zfcServiceEvents = $services->get('zfcuser_user_service')->getEventManager();


        // Store the field
        $zfcServiceEvents->attach('register', function($e) use($services) {
            $user=$e->getParam('user');//lấy người dùng hiện tại đang đăng ký ở event
            $em=$services->get('Doctrine\ORM\EntityManager');// lệnh kết nôi doctrine orm
            $defaultUserRole=$em->getRepository('DanhMuc\Entity\Role')// kết nối tới file Role trong danh mục
                                ->findOneBy(array('roleId'=>'nguoi-dung'));// lấy lấy 1 dòng có roleId có tên là người dùng
            $user->addRole($defaultUserRole);//
            
        });

//===================================================================================================
        


        $events = $e->getApplication()->getEventManager()->getSharedManager();

        /*           
        $events->attach('DanhMuc\Controller\IndexController', 'addAction', function ($e) {                    
                    
                    $event  = $e->getName();
                    $target = $e->getTarget(); // ThemEvent                    
                    $target->flashMessenger()->addMessage('Tên class: '.get_class($target).'. Tên Event: '.$event);

                               
        });
        */

        //$services = $e->getApplication()->getServiceManager();   
        // $services này đã có khai báo ở trên rồi nên không cần khai báo lại
        $danhMucServiceEvent = $services->get('danh_muc_service')->getEventManager();
        $danhMucServiceEvent->attach('taoMoiDanhMucService', function ($e) { 
            $event  = $e->getName();
            $PEV = $e->getTarget(); // ThemEvent                    
            $PEV->flashMessenger()->addMessage('Tên class: '.get_class($PEV).'. Tên Event: '.$event);
                   
        });
       $danhMucServiceEvent->attach('xoaNhieuDanhMucService', function ($e) { 
            $event  = $e->getName();
            $PEV = $e->getTarget(); // ThemEvent                    
            $PEV->flashMessenger()->addMessage('Tên class: '.get_class($PEV).'. Tên Event: '.$event);
                   
        });

       

        $events->attach('ZfcUser\Form\Register','init', function($e) {
            $form = $e->getTarget();
            // Do what you please with the form instance ($form)


           /* $userName = new Element('userName');
            $userName->setLabel('User Name');
            $userName->setAttributes(array(
                'type'  => 'text'
            ));



            $displayName = new Element('displayName');
            $displayName->setLabel('Display Name');
            $displayName->setAttributes(array(
                'type'  => 'text'
            ));


            
            $state = new Element('state');
            $state->setLabel('User Name');
            $state->setAttributes(array(
                'type'  => 'text'
            ));



            $city = new Element('city');
            $city->setLabel('User Name');
            $city->setAttributes(array(
                'type'  => 'text'
            ));

            $form->add($userName);
            $form->add($displayName);
            $form->add($state);
            $form->add($city);

            */
            
            $form->add(array(
                    'name' => 'userName',
                    'type' => 'Text',
                    'options' => array(
                            'label' => 'User Name: ',
                    ),
            ));
            $form->add(array(
                    'name' => 'displayName',
                    'type' => 'Text',
                    'options' => array(
                            'label' => 'Display Name: ',
                    ),
            ));
           
        });


        $events->attach('ZfcUser\Form\RegisterFilter','init', function($e) {
            $filter = $e->getTarget();
            // Do what you please with the filter instance ($filter)

            $filter->add(array(
                'name'       => 'userName',
                'required'   => true,
                'validators' => array(
                        array(
                                'name'    => 'StringLength',
                                'options' => array(
                                        'min' => 3,
                                        'max' => 255,
                                ),
                        ),
                ),
        ));
        $filter->add(array(
                'name'       => 'displayName',
                'required'   => true,
                'validators' => array(
                        array(
                                'name'    => 'StringLength',
                                'options' => array(
                                        'min' => 3,
                                        'max' => 255,
                                ),
                        ),
                ),
        ));

        });

        $zfcServiceEvents->attach('register', function($e) {
            $user = $e->getParam('user');  // User account object
            $form = $e->getParam('form');  // Form object
            //var_dump($form->get('firstname')->getValue()); die;
            //var_dump($user); die;
        });

        $zfcServiceEvents->attach('register.post', function($e) {
            $user = $e->getParam('user');
        });










    }


    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}
?>