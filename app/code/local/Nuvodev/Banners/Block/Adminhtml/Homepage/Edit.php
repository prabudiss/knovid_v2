<?php
	
class Nuvodev_Banners_Block_Adminhtml_Homepage_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
		public function __construct()
		{

				parent::__construct();
				$this->_objectId = "id";
				$this->_blockGroup = "banners";
				$this->_controller = "adminhtml_homepage";
				$this->_updateButton("save", "label", Mage::helper("banners")->__("Save Item"));
				$this->_updateButton("delete", "label", Mage::helper("banners")->__("Delete Item"));

				$this->_addButton("saveandcontinue", array(
					"label"     => Mage::helper("banners")->__("Save And Continue Edit"),
					"onclick"   => "saveAndContinueEdit()",
					"class"     => "save",
				), -100);



				$this->_formScripts[] = "

							function saveAndContinueEdit(){
								editForm.submit($('edit_form').action+'back/edit/');
							}
						";
		}

		public function getHeaderText()
		{
				if( Mage::registry("homepage_data") && Mage::registry("homepage_data")->getId() ){

				    return Mage::helper("banners")->__("Edit Item '%s'", $this->htmlEscape(Mage::registry("homepage_data")->getId()));

				} 
				else{

				     return Mage::helper("banners")->__("Add Item");

				}
		}
}