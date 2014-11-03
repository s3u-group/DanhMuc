<?php
namespace DanhMuc\Form;
use Zend\InputFilter\InputFilter;

class DanhMucFilter extends InputFilter
{
	
	public function __construct()
	{
		
		$this->add(
			array(
				'name'=> 'ten',
				'required'=> true // required => true là bắt buộc phải nhập vào
				)
			);
		$this->add(
			array(
				'name'=> 'moTa',
				'required'=> false // required => true là bắt buộc phải nhập vào
				)
			);

		$this->add(
			array(
				'name'=> 'cha',
				'required'=> false // required => true là bắt buộc phải nhập vào
				)
			);
	}
}
?>