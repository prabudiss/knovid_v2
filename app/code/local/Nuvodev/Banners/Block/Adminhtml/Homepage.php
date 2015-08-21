<?php


class Nuvodev_Banners_Block_Adminhtml_Homepage extends Mage_Adminhtml_Block_Widget_Grid_Container{

	public function __construct()
	{

	$this->_controller = "adminhtml_homepage";
	$this->_blockGroup = "banners";
	$this->_headerText = Mage::helper("banners")->__("Homepage Manager");
	$this->_addButtonLabel = Mage::helper("banners")->__("Add New Item");
	parent::__construct();
	
	}

}