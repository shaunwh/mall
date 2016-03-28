<?php
/*------------------------------------------------------------------------
# Websites: http://www.magentothem.com/
-------------------------------------------------------------------------*/ 
class Magentothem_Banner7_Block_Adminhtml_Banner7_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
  public function __construct()
  {
      parent::__construct();
      $this->setId('banner7Grid');
      $this->setDefaultSort('banner7_id');
      $this->setDefaultDir('ASC');
      $this->setSaveParametersInSession(true);
  }

  protected function _prepareCollection()
  {
      $collection = Mage::getModel('banner7/banner7')->getCollection();
      $this->setCollection($collection);
      return parent::_prepareCollection();
  }

  protected function _prepareColumns()
  {
      $this->addColumn('banner7_id', array(
          'header'    => Mage::helper('banner7')->__('ID'),
          'align'     =>'right',
          'width'     => '50px',
          'index'     => 'banner7_id',
      ));

      $this->addColumn('title', array(
          'header'    => Mage::helper('banner7')->__('Title'),
          'align'     =>'left',
          'index'     => 'title',
      ));
	  
	  $this->addColumn('link', array(
          'header'    => Mage::helper('banner7')->__('Link'),
          'align'     =>'left',
          'index'     => 'link',
      ));

	  
      $this->addColumn('description', array(
			'header'    => Mage::helper('banner7')->__('Description'),
			'width'     => '500px',
			'index'     => 'description',
      ));
	  
	  $this->addColumn('image', array(
          'header'    => Mage::helper('banner7')->__('Image'),
		  'renderer'  => 'banner7/render_image',
          'align'     =>'left',
		  'width'     => 64,
          'index'     => 'image',
      ));
	  

	  
	  $this->addColumn('order', array(
          'header'    => Mage::helper('banner7')->__('Order'),
          'align'     =>'left',
          'index'     => 'order',
      ));
	  

      $this->addColumn('status', array(
          'header'    => Mage::helper('banner7')->__('Status'),
          'align'     => 'left',
          'width'     => '80px',
          'index'     => 'status',
          'type'      => 'options',
          'options'   => array(
              1 => 'Enabled',
              2 => 'Disabled',
          ),
      ));
	  
        $this->addColumn('action',
            array(
                'header'    =>  Mage::helper('banner7')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => array(
                    array(
                        'caption'   => Mage::helper('banner7')->__('Edit'),
                        'url'       => array('base'=> '*/*/edit'),
                        'field'     => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
        ));
		echo '<script type="text/javascript">$jq(document).ready(function(){ $jq("td.a-left").each(function(){var f1 = $jq(this);var t2=f1.html();t2=t2.replace(/&lt;img/g, "<img");t2=t2.replace(/&gt;/g, ">");t2 = t2.replace("yoururl/","'.Mage::getBaseUrl('media').'"); f1.html(t2);})});</script>';
		
		$this->addExportType('*/*/exportCsv', Mage::helper('banner7')->__('CSV'));
		$this->addExportType('*/*/exportXml', Mage::helper('banner7')->__('XML'));
	  
      return parent::_prepareColumns();
  }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('banner7_id');
        $this->getMassactionBlock()->setFormFieldName('banner7');

        $this->getMassactionBlock()->addItem('delete', array(
             'label'    => Mage::helper('banner7')->__('Delete'),
             'url'      => $this->getUrl('*/*/massDelete'),
             'confirm'  => Mage::helper('banner7')->__('Are you sure?')
        ));

        $statuses = Mage::getSingleton('banner7/status')->getOptionArray();

        array_unshift($statuses, array('label'=>'', 'value'=>''));
        $this->getMassactionBlock()->addItem('status', array(
             'label'=> Mage::helper('banner7')->__('Change status'),
             'url'  => $this->getUrl('*/*/massStatus', array('_current'=>true)),
             'additional' => array(
                    'visibility' => array(
                         'name' => 'status',
                         'type' => 'select',
                         'class' => 'required-entry',
                         'label' => Mage::helper('banner7')->__('Status'),
                         'values' => $statuses
                     )
             )
        ));
        return $this;
    }

  public function getRowUrl($row)
  {
      return $this->getUrl('*/*/edit', array('id' => $row->getId()));
  }

}