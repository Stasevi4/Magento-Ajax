<?php

class Stasevi4_Productlist_Helper_Data 
    extends Mage_Core_Helper_Abstract
    {
        /**
     * Array of available orders to be used for sort by
     *
     * @return array
     */
    protected $categoryid;	
	
	public function __construct(){
	  	$this->setCategoryID(Mage::app()->getStore()->getRootCategoryId());
	}
	
    public function getAvailableOrders()
    {
        return array(
            // attribute name => label to be used
            'Price' => $this->__('Price'),
			'SKU' => $this->__('sku')
        );
    }
    public function setCategoryID($value){
	
	    $this->categoryid = $value;
	}
    /**
     * Return product collection to displayed by our list block
     *
     * @return Mage_Catalog_Model_Resource_Product_Collection
     */
    public function getProductCollection()
    {
	    if(Mage::app()->getRequest()->getParam('categoryid')){
		   $this->setCategoryID(Mage::app()->getRequest()->getParam('categoryid')); 	
		}        
		
        $rootCategoryId = Mage::app()->getStore()->getRootCategoryId();
        $collection = Mage::getModel('catalog/category')
            ->load($this->categoryid)
            ->getProductCollection()
            ->addAttributeToSelect(Mage::getSingleton('catalog/config')->getProductAttributes())
            ->addMinimalPrice()
            ->addFinalPrice()
            ->addTaxPercents()
            ->addUrlRewrite($rootCategoryId);
  /*      
		$layer = Mage::getModel("catalog/layer");
		$category = Mage::getModel("catalog/category")->load($this->categoryid);
		$layer->setCurrentCategory($category);
		$attributes = $layer->getFilterableAttributes();
 */
		
        Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
        Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);

        return $collection;
    }
}

?>