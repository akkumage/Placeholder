<?php
namespace Pcnametag\Placeorder\Plugin;
use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Quote\Model\Quote;
class Checkmo
{

  /**
   * @var \Magento\Checkout\Model\Session
   */
   protected $_checkoutSession;

  /**
   * Constructor
   *
   * @param \Magento\Checkout\Model\Session $checkoutSession
   */
	public function __construct
	(
		\Magento\Checkout\Model\Session $checkoutSession
	 ) {
		$this->_checkoutSession = $checkoutSession;
		return;
	}

	public function aroundIsAvailable(\Magento\Payment\Model\Method\AbstractMethod $subject, callable $proceed)
	{
		//$shippingMethod = $this->_checkoutSession->getQuote()->getShippingAddress()->getShippingMethod();
		
		$objectManager 	= \Magento\Framework\App\ObjectManager::getInstance();
		$customerSession= $objectManager->get('Magento\Customer\Model\Session');

/*		$logged_in	= $customerSession->isLoggedIn();
		if($logged_in!=1) 
		{
			return false;
		}
		* */
		$result = $proceed();
		
		return $result;
	  }
}
