<?php
class Nuvodev_Banners_Block_Adminhtml_Featured_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
		protected function _prepareForm()
		{

				$form = new Varien_Data_Form();
				$this->setForm($form);
				$fieldset = $form->addFieldset("banners_form", array("legend"=>Mage::helper("banners")->__("Item information")));

				
						$fieldset->addField('image', 'image', array(
						'label' => Mage::helper('banners')->__('Image'),
						'name' => 'image',
						'note' => '(*.jpg, *.png, *.gif)',
						));
									
						 $fieldset->addField('position', 'select', array(
						'label'     => Mage::helper('banners')->__('Position'),
						'values'   => Nuvodev_Banners_Block_Adminhtml_Featured_Grid::getValueArray7(),
						'name' => 'position',					
						"class" => "required-entry",
						"required" => true,
						));
						$fieldset->addField("product_id", "text", array(
						"label" => Mage::helper("banners")->__("Product Id"),					
						"class" => "required-entry",
						"required" => true,
						"name" => "product_id",
						));
									
						 $fieldset->addField('status', 'select', array(
						'label'     => Mage::helper('banners')->__('Status'),
						'values'   => Nuvodev_Banners_Block_Adminhtml_Featured_Grid::getValueArray11(),
						'name' => 'status',					
						"class" => "required-entry",
						"required" => true,
						));

				if (Mage::getSingleton("adminhtml/session")->getFeaturedData())
				{
					$form->setValues(Mage::getSingleton("adminhtml/session")->getFeaturedData());
					Mage::getSingleton("adminhtml/session")->setFeaturedData(null);
				} 
				elseif(Mage::registry("featured_data")) {
				    $form->setValues(Mage::registry("featured_data")->getData());
				}
				return parent::_prepareForm();
		}
}
