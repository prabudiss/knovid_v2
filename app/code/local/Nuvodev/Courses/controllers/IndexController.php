<?php
class Nuvodev_Courses_IndexController extends Mage_Core_Controller_Front_Action{
    public function IndexAction() {
      
        if(! Mage::helper('customer')->isLoggedIn()){
            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account/create'));
        }   
        else
        {
        
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Course"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("course", array(
                "label" => $this->__("Course"),
                "title" => $this->__("Course")
		   ));

      $this->renderLayout(); 
        } 
    }

    public function SubscribedAction() {
      
        if(! Mage::helper('customer')->isLoggedIn()){
            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account/create'));
        }  
        else
        {
        
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Course"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
      $breadcrumbs->addCrumb("home", array(
                "label" => $this->__("Home Page"),
                "title" => $this->__("Home Page"),
                "link"  => Mage::getBaseUrl()
		   ));

      $breadcrumbs->addCrumb("course", array(
                "label" => $this->__("Course"),
                "title" => $this->__("Course")
		   ));

      $this->renderLayout(); 
        } 
    }
    
    public function ManagedAction() {
      
        if(! Mage::helper('customer')->isLoggedIn()){
            Mage::app()->getFrontController()->getResponse()->setRedirect(Mage::getUrl('customer/account/create'));
        }            
        else
        {
	  $this->loadLayout();   
	  $this->getLayout()->getBlock("head")->setTitle($this->__("Course"));
	        $breadcrumbs = $this->getLayout()->getBlock("breadcrumbs");
        $breadcrumbs->addCrumb("home", array(
                  "label" => $this->__("Home Page"),
                  "title" => $this->__("Home Page"),
                  "link"  => Mage::getBaseUrl()
                     ));

        $breadcrumbs->addCrumb("course", array(
                  "label" => $this->__("Course"),
                  "title" => $this->__("Course")
                     ));

        $this->renderLayout(); 
        }  
    }

    public function AddtoCartAction() {
      
	  $prod_id = $_REQUEST['prod_id'];
	  if($prod_id == "") {echo "false"; return false;}
		try{	  
			
			$session = Mage::getSingleton('core/session', array('name'=>'frontend'));
			
			$product = Mage::getModel('catalog/product')->setStoreId($store)->load($prod_id);
			$cart = Mage::helper('checkout/cart')->getCart();

			$param = new Varien_Object(array(
				'qty'=> 1,
					));
			 
			$cart->addProduct($product, new Varien_Object($param));
			 
			$session->setLastAddedProductId($product->getId());
			$session->setCartWasUpdated(true);
		 
			$cart->save();
			
			echo "Success";
		}
		catch (Exception $e) {
			$result['result'] = 'error';
			$result['message'] =  $e->getMessage();
			echo json_encode($result);
		}		
    }

    public function GetVideoAction() {

      $type = $_REQUEST['type'];
      $pid = $_REQUEST['prod_id'];
      $index = $_REQUEST['index'];

      switch ($type)
      {
          case 1: //GET VIDEO TAG
          {
            $book = Mage::getModel('courses/book');
            $book->getVideoTag($pid, $index);
            break;
          }
          default :
          {
            echo "Something went wrong, please try again later!!!!";
            break;
          }
      }
    } 

    private function setAddress($customer) {

      /*echo "<br>First Name : " . $customer->getFirstname();
      echo "<br>Last Name : " . $customer->getLastname();
      echo "<br>Phone : " . $customer->getPhone();
      echo "<br>User Type : " . $customer->getUsertype();
      echo "<br>course : " . $customer->getCourse();
      echo "<br>year : " . $customer->getYear();
      echo "<br>college : " . $customer->getCollege();
      echo "<br>city : " . $customer->getPlace();
      echo "<br> Billing Address = " . $customer->getDefaultBilling();
      echo "<br> Shipping Adress = " . $customer->getDefaultShipping();*/

      Mage::Log("customer->getDefaultBilling" . $customer->getDefaultBilling());
      Mage::Log("customer->getDefaultShipping" .  $customer->getDefaultShipping());

      //Check Address information available!!!
      if (($customer->getDefaultBilling()  && $customer->getDefaultShipping())
        && ($customer->getDefaultBilling() == $customer->getDefaultShipping())) {
        return true;
      }

      Mage::Log("Address information not available, Adding new one!!!!");

      $_custom_address = array (
          'firstname' => $customer->getFirstname(),
          'lastname' => $customer->getLastname(),
          'street' => array (
              '0' =>  $customer->getYear() . ", " . $customer->getCourse(),
              '1' => $customer->getCollege(),
          ),
          'city' => $customer->getPlace(),
          'region_id' => '',
          'region' => '',
          'postcode' => '0',
          'country_id' => 'IND', /* Croatia */
          'telephone' => $customer->getPhone(),
      );
      $customAddress = Mage::getModel('customer/address');
      //$customAddress = new Mage_Customer_Model_Address();
      $customAddress->setData($_custom_address)
                  ->setCustomerId($customer->getId())
                  ->setIsDefaultBilling('1')
                  ->setIsDefaultShipping('1')
                  ->setSaveInAddressBook('1');
      try {
          $customAddress->save();
          Mage::Log("Adding information success!!!");
          return true;
      }
      catch (Exception $ex) {
          //Zend_Debug::dump($ex->getMessage());
        Mage::Log("Error in Address " . $ex->getMessage());
        return false;
      }
    }

    public function PurchaseAction() {

      if (!Mage::getSingleton('customer/session')->isLoggedIn()) {
              $this->_redirectUrl(Mage::getBaseUrl().'customer/account');
              return;
       }

       //Get the Cusotmer Object to add the address
       $customerData = Mage::getSingleton('customer/session')->getCustomer();

       $request = $this->getRequest();
       $id = $request->getParam('id');

       $product = Mage::getModel('catalog/product')
                      ->load($id)
                      ->setStoreId(Mage::app()->getStore()->getId());

       //check default address;
       if(!$this->setAddress($customerData)) {
          Mage::getSingleton('checkout/session')->addError("Oops!!! Internal Error Occured, Please try again later.");
          $this->getResponse()->setRedirect($product->getProductUrl());
       }

       if(!($product->getIsVirtual() && $product->getFinalPrice() == 0)){
           Mage::getSingleton('checkout/session')->addError($this->__('Method only available for Free Downloadable Items'));
           $this->getResponse()->setRedirect($product->getProductUrl());
       }

       $onepage = Mage::getSingleton('checkout/type_onepage');
       /* @var $onepage Mage_Checkout_Model_Type_Onepage */
       try{
           $quote = $onepage->getQuote();
           /* @var $quote Mage_Sales_Model_Quote */
           $quote->addProduct($product);
           Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
           $onepage->initCheckout();
           $payment=array('method'=>'free');
           $onepage->savePayment($payment);   
           $onepage->saveOrder();


           Mage::getSingleton('checkout/session')->addSuccess("Your subscription has been activated successfully. Keep Learning!"); 
           $this->getResponse()->setRedirect($product->getProductUrl());
       }
       catch(Exception $e){
           $result = $e->getMessage();
           Mage::getSingleton('checkout/session')->addError($result);
           Mage::Log("Error in Purchase : " . $result); 
       }
    }    
}