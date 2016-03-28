<?php
/*------------------------------------------------------------------------
# Websites: http://www.magentothem.com/
-------------------------------------------------------------------------*/ 
class Magentothem_Banner7_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
    	
    	/*
    	 * Load an object by id 
    	 * Request looking like:
    	 * http://site.com/banner7?id=15 
    	 *  or
    	 * http://site.com/banner7/id/15 	
    	 */
    	/* 
		$banner7_id = $this->getRequest()->getParam('id');

  		if($banner7_id != null && $banner7_id != '')	{
			$banner7 = Mage::getModel('banner7/banner7')->load($banner7_id)->getData();
		} else {
			$banner7 = null;
		}	
		*/
		
		 /*
    	 * If no param we load a the last created item
    	 */ 
    	/*
    	if($banner7 == null) {
			$resource = Mage::getSingleton('core/resource');
			$read= $resource->getConnection('core_read');
			$banner7Table = $resource->getTableName('banner7');
			
			$select = $read->select()
			   ->from($banner7Table,array('banner7_id','title','content','status'))
			   ->where('status',1)
			   ->order('created_time DESC') ;
			   
			$banner7 = $read->fetchRow($select);
		}
		Mage::register('banner7', $banner7);
		*/

			
		$this->loadLayout();     
		$this->renderLayout();
    }
}