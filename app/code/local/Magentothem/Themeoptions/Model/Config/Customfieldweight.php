<?php
class Magentothem_Themeoptions_Model_Config_Customfieldweight
{

    public function toOptionArray()
    {
		$fontweight = "100,200,300,400,500,600,700,800,900";
		
		$fweight = explode(',', $fontweight);
	    $options = array();
	    foreach ($fweight as $w ){

		    $options[] = array(
			    'value' => $w,
			    'label' => $w,
		    );
	    }
		
        return $options;
    }

}
