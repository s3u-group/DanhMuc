<?php
namespace DanhMuc\Service;

use Zend\ServiceManager\ServiceManagerAwareInterface;
use ZfcBase\EventManager\EventProvider;
use Zend\ServiceManager\ServiceManager;


class DanhMucService extends EventProvider implements ServiceManagerAwareInterface
{
	public function setServiceManager(ServiceManager $serviceManager)
	{
		$this->serviceManager = $serviceManager;
        return $this;
	}

	public function taoMoiDanhMucService($target)
	{
		$this->getEventManager()->trigger(__FUNCTION__,$target);
	}

	public function xoaNhieuDanhMucService($target)
	{
		$this->getEventManager()->trigger(__FUNCTION__,$target);
	}
}
?>
