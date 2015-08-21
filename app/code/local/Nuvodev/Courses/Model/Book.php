<?php

class Nuvodev_Courses_Model_Book extends Mage_Core_Model_Abstract
{
	public function checkSubscription($prod) {

		//$vid_free = "getVideoFree_" . $index;
		$subscribed = false;
		$logged_in = false;

		$customer_id = 0;

		//$prod = Mage::getModel('catalog/product')->load($pid);
		//Check whether the user logged in.
		if (Mage::getSingleton('customer/session')->isLoggedIn()) {
	    	$customer_id = Mage::getSingleton('customer/session')->getCustomer()->getId();
	    	$logged_in = true;
	    }

	    if($customer_id)
		{
		    $orders = Mage::getResourceModel('sales/order_collection')
		        ->addFieldToFilter('customer_id', $customer_id)
		        ->addAttributeToSort('created_at', 'DESC');

		    foreach($orders as $ord)
		    {
		        $order = Mage::getModel('sales/order')->load($ord->getId());
		        $items = $order->getAllItems();
		        foreach ($items as $itemId => $item)
		        {
		            $prod_id=$item->getProductId();

		            if ($prod->getId() == $prod_id) {

						$product = Mage::getModel('catalog/product')->load($prod_id);
		            	$validity = $product->getValidity();
		            	if(strtotime($item->getCreatedAt() + $validity ."Days") >= (strtotime("Now")))
			            {
			                $subscribed = true;
			                break;
			            }

			        }
			        else if ($prod->getParentId() == $prod_id) {
			        	$product = Mage::getModel('catalog/product')->load($prod_id);
		            	$validity = $product->getValidity();
		            	if(strtotime($item->getCreatedAt() + $validity ."Days") >= (strtotime("Now")))
			            {
			                $subscribed = true;
			                break;
			            }
			        }
		        }
		    }
		}

		if ($logged_in && $subscribed) { 
			return true;
		}
		return false;
		
	}

	public function getVideoTag($pid, $index)
	{
		$_helper = Mage::helper('courses/video');
		$vid_free = "getVideoFree_" . $index;
		
		$prod = Mage::getModel('catalog/product')->load($pid);

		if ($prod->$vid_free())
		{
			$_helper->getVideo($prod, $index);
		}
		else if ($this->checkSubscription($prod)) {
			$_helper->getVideo($prod, $index);
		}
		else {
			$_helper->getSubcribeTag($prod, $index);
		}

	}
}
?>
