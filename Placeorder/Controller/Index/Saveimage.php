<?php
namespace Pcnametag\Placeordre\Controller\Index;
use Magento\Framework\Controller\ResultFactory;
class Saveimage extends \Magento\Framework\App\Action\Action
{
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Filesystem $filesystem,
         \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directory_list,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
        
    ) {
        $this->_request = $context->getRequest();
        $this->filesystem=$filesystem;
        $this->_mediaDirectory = $filesystem->getDirectoryWrite(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $this->directory_list= $directory_list;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        return parent::__construct($context);

    }
     
    public function execute()
    {
		die("hgfkjhf");
            $target = $this->_mediaDirectory->getAbsolutePath('m2doctor/uploads');        
	        $uploader = $this->_fileUploaderFactory->create(['fileId' => 'uploadimage']);
	       // $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
	        $uploader->setAllowRenameFiles(true);
	        $result = $uploader->save($target);
	        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        	$resultJson->setData($result['file']);
        	echo '<pre>';
        	print_r($resultJson);
        	echo '</pre>';
        	return $resultJson;
    }
}
