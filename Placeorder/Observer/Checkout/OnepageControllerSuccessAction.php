<?php


namespace Pcnametag\Placeorder\Observer\Checkout;

use \Magento\Framework\Event\Observer;
use \Magento\Sales\Model\OrderFactory;

class OnepageControllerSuccessAction implements \Magento\Framework\Event\ObserverInterface
{
    private $_orderFactory;
    private $directory_list;
    public function __construct(
        OrderFactory $orderFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Magento\Framework\Filesystem\Io\File  $ioAdapter
    ) {
        $this->_orderFactory    = $orderFactory;
        $this->directory_list = $directory_list; 
        $this->ioAdapter	= $ioAdapter;

    }
    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $lastOrderId 	= $observer->getEvent()->getData('order_ids');
       
		$order 			= $this->_orderFactory->create()->load($lastOrderId[0]);
        if (!$order->getId()) {
            return $this;
        }
		
		$orderId 	= $order->getIncrementId();
		//die();exit();
		//echo "==";
		$current_order	= str_pad($orderId, 8, '0', STR_PAD_LEFT);
		//echo "==";
		//$quoteId	= $order->getQuoteId();
		$orderAllItems = $order->getAllItems();
		foreach ($orderAllItems as $item) {  
			$options1 = $item->getProductOptions();
			if(empty($options1))
				$options = $item->getProductOptionByCode('additional_options');
			else
				$options = isset($options1['additional_options'])?$options1['additional_options']:array();
			/*echo '<pre>';
			print_r($options);
			echo '</pre>';*/
			
		if(is_array($options))
		{
			foreach ($options as $key=>$opt) {
				if(is_array($opt))
				{
					if(($opt['label'] == 'File 1') || ($opt['label'] == 'File 2') || ($opt['label'] == 'Artwork File'))
					{
						if($opt['label'] == 'Artwork File')
						{
							$file_array	= explode("/liquifire/",$opt['value']);
							$oldfile_name = isset($file_array[1])?$file_array[1]:'';
						}
						else
							$oldfile_name = $opt['value'];
						//echo "file name==".$oldfile_name;
						if($oldfile_name != '')
							$oldfile= $this->directory_list->getPath('pub')."/media/liquifire/".$oldfile_name;
					//	echo "<br/>==old file==".$oldfile."==<br/>new file==";
						$min_order 	= $max_order = '400000000';
					 	$newpath 	= $this->directory_list->getPath('pub')."/media/order";
						//$current_order = 2643;
						//$start_point	= ($current_order%1000);
						//$start_point	= ($start_point	= 0)?0:$start_point+1000;
						
						for($i=400000000;$i<=499999999;$i+=1000)
						{
							$j=$i+999;
							//echo "cur order=".$current_order."==i==".$i."==j==".$j."==";
							if(($current_order >= $i && $current_order <= $j))
							{
								$min_order =  str_pad($i, 8, '0', STR_PAD_LEFT);
								$max_order =  str_pad($j, 8, '0', STR_PAD_LEFT);
								break;
							}
						}
					
						$fullpath = $newpath."/".$min_order." - ".$max_order."/".$current_order."/CustomerUploads";
					//die();
			//exit();
						if (!is_dir($fullpath)) {
							$this->ioAdapter->mkdir($fullpath, 0775);
						}
						@copy($oldfile, $fullpath."/".$oldfile_name);
					}
				}
			}
			 
		 }
	
		//die("dfghdfkgjhdkjfg");
	}
   return $this;
    }
}
