<?php

namespace Pcnametag\Placeorder\Observer;

use \Magento\Framework\Event\Observer;
use \Magento\Framework\Event\ObserverInterface;


class PaymentMethodAvailable implements ObserverInterface
{
	
    /**
     * payment_method_is_active event handler.
     *
     * @param \Magento\Framework\Event\Observer $observer
     */
    
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        // you can replace "checkmo" with your required payment method code
        if($observer->getEvent()->getMethodInstance()->getCode()=="checkmo")
        {
            $checkResult 	= $observer->getEvent()->getResult();
            $om 			= \Magento\Framework\App\ObjectManager::getInstance();
			$customerSession= $om->get('Magento\Customer\Model\Session');
			$logged			= $customerSession->isLoggedIn();
			if($logged != 1)
			{
				$checkResult->setData('is_available', false); //this is disabling the payment method at checkout page
			}
			if($logged == 1)
			{
				$customerData 	= $customerSession->getCustomer()->getData(); 
				$navBalance 	= isset($customerData['nav_balance'])?$customerData['nav_balance']:0;
				$navCreditLimit = isset($customerData['nav_credit_limit'])?$customerData['nav_credit_limit']:0;
				$cart = $om->get('\Magento\Checkout\Model\Cart'); 
$total = $cart->getQuote()->getGrandTotal();
				
				if($navCreditLimit > 0.01)
				{
					if(($total+$navBalance)>$navCreditLimit)
					{
						$checkResult->setData('is_available', false); //this is disabling the payment method at checkout page
					}
				}
				else
				{
					$checkResult->setData('is_available', false); //this is disabling the payment method at checkout page
				}
    
			}
        }
    }
}
