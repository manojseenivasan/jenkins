<?php
class Ewall_Imagemarker_Model_Mail
{
	const CONTACT_FORM_EMAIL = 'imagemarker_section/imagemarker_group/email_template';
	const CONTACT_FORM_EMAIL_TEMPLATE = 'imagemarker_section/imagemarker_group/email';

	public function sendmail(){
		if($_REQUEST['debug']){echo "gdfg";die();
			$sender['email'] = Mage::getStoreConfig(self::CONTACT_FORM_EMAIL,Mage::app()->getStore()->getId()); 
		$sender['name'] = Mage::app()->getStore()->getId();
		$template =  Mage::getStoreConfig(self::CONTACT_FORM_EMAIL_TEMPLATE,Mage::app()->getStore()->getId());

		$translate = Mage::getSingleton('core/translate');
		$translate->setTranslateInline(false);

		$post = new Varien Object();
		$post->setName('Name');
		$post->setEmail('ewallteam@gmail.com');
		$post->setTelephone('9898989898');
		$post->setComment('Comment');
		$mailTemplate = Mage::getModel('core/email_template');
		$mailTemplate->setDesignConfig(array('area' => 'frontend'))
		->sendTransactional(
                        $template,
                        $sender, 
                        'ewallteam@gmail.com',
                        null,
                        array('data' => $post)
                    );
		$translate->setTranslateInline(true);
		var_dump($mailTemplate->getSendSuccess());die();
		}
	}
}