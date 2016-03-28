<?php
/*------------------------------------------------------------------------
# Websites: http://www.magentothem.com/
			http://www.plazathemes.com/
-------------------------------------------------------------------------*/ 
class Magentothem_Banner7_Block_Adminhtml_Banner7_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('banner7_form', array('legend'=>Mage::helper('banner7')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('banner7')->__('Title'),
          'required'  => false,
          'name'      => 'title',
      ));
	  
	  $fieldset->addField('title2', 'text', array(
          'label'     => Mage::helper('banner7')->__('Title 2'),
          'required'  => false,
          'name'      => 'title2',
      ));
	  
	  $fieldset->addField('title3', 'text', array(
          'label'     => Mage::helper('banner7')->__('Title 3'),
          'required'  => false,
          'name'      => 'title3',
      ));
	  
	  $fieldset->addField('link', 'text', array(
          'label'     => Mage::helper('banner7')->__('Link'),
          'required'  => false,
          'name'      => 'link',
      ));

      $fieldset->addField('image', 'image', array(
          'label'     => Mage::helper('banner7')->__('Image'),
          'required'  => false,
          'name'      => 'image',
	  ));
	  
	  // $fieldset->addField('image2', 'image', array(
          // 'label'     => Mage::helper('banner7')->__('Small Image 1'),
          // 'required'  => false,
          // 'name'      => 'image2',
	  // ));
	  
	  // $fieldset->addField('image3', 'image', array(
          // 'label'     => Mage::helper('banner7')->__('Small Image 2'),
          // 'required'  => false,
          // 'name'      => 'image3',
	  // ));
	  
	  $fieldset->addField('order', 'text', array(
          'label'     => Mage::helper('banner7')->__('Order'),
          'required'  => false,
          'name'      => 'order',
      ));


       
        if (!Mage::app()->isSingleStoreMode()) {
            $fieldset->addField('store_id', 'multiselect', array(
                'name' => 'store_id[]',
                'label' => $this->__('Store View'),
                'required' => TRUE,
                'values' => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(FALSE, TRUE),
            ));
        }
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('banner7')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('banner7')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('banner7')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('description', 'editor', array(
          'name'      => 'description',
          'label'     => Mage::helper('banner7')->__('Description'),
          'title'     => Mage::helper('banner7')->__('Description'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => false,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getBanner7Data() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getBanner7Data());
          Mage::getSingleton('adminhtml/session')->setBanner7Data(null);
      } elseif ( Mage::registry('banner7_data') ) {
          $form->setValues(Mage::registry('banner7_data')->getData());
      }
      return parent::_prepareForm();
  }
}