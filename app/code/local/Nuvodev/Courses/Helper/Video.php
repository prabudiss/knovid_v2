<?php 

class Nuvodev_Courses_Helper_Video extends Mage_Core_Helper_Abstract {

	public function getSubcribeTag($prod, $index){

		$_helper = Mage::helper('catalog/output');
		$tmpVideoTag = "getVideoName_" . $index;

                echo "    <div class='video_locked'>";
                echo "	  <div id='video_sub_controls'>";
                echo " 	   Oops!!! Not Subscribed yet? Subscribe now to start learning!!";
                //echo "  	<button title='Subscbribe' class=button onclick='javascript:product_addtocart_form_"; echo $prod->getId(); echo ".submit();'><span><span>Subscribe (₹". round($prod->getPrice()) .")</span></span>";
                echo "    </div></div>";
                echo "    <div class='product-videobottom'>";
                echo "        <span>";
                echo "            <span>";
                echo "                Learning : "; echo $prod->$tmpVideoTag(); echo "<br>";
                echo "            </span>";
                echo "            <span style='font-size:12px;''>";
                echo "               Chapter : "; echo $_helper->productAttribute($prod, $prod->getName(), 'name');
                echo "           </span>";
                echo "        </span>";
                echo "    </div>";


	}

        public function getVideo($prod, $index){

                $_helper = Mage::helper('catalog/output');
                $tmpVideoNameTag = "getVideoName_" . $index;
                $tmpVideoUrlTag = "getVideoUrl_" . $index;

                echo "<div style='width: 100%'>";
                echo "        <iframe height=498 width=100% src='"; echo $prod->$tmpVideoUrlTag(); echo "?autohide=2&amp;controls=1&amp;iv_load_policy=3&amp;theme=light&amp;color=white;showinfo=0' frameborder=0 allowfullscreen ></iframe>";
                echo "</div>";
                echo "    <div class='product-videobottom'>";
                echo "        <span>";
                echo "            <span>";
                echo "                Learning : "; echo $prod->$tmpVideoNameTag(); echo "<br>";
                echo "            </span>";
                echo "            <span style='font-size:12px;''>";
                echo "               Chapter : "; echo $_helper->productAttribute($prod, $prod->getName(), 'name');
                echo "           </span>";
                echo "        </span>";
                echo "    </div>";

        }

	
}

?>