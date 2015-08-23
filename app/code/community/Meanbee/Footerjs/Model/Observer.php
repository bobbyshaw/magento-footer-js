<?php

class Meanbee_Footerjs_Model_Observer {

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function handleHttpResponseInlineJs(Varien_Event_Observer $observer)
    {
        Varien_Profiler::start('MeanbeeFooterJs');

        /** @var Meanbee_Footerjs_Helper_Data $helper */
        $helper = Mage::helper('meanbee_footerjs');
        if (!$helper->isEnabled()) {
            return $this;
        }

        /** @var Mage_Core_Controller_Response_Http $response */
        $response = $observer->getResponse();

        if ($response->isRedirect() || !$response->getBody()) {
            // No further action if no body or we know it's a redirect
            return $this;
        }

        $response->setBody($helper->moveJsToEnd($response->getBody()));

        Varien_Profiler::stop('MeanbeeFooterJs');

        return $this;
    }

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function handleBlockInlineJs(Varien_Event_Observer $observer)
    {
        Varien_Profiler::start('MeanbeeFooterJs');

        /** @var Meanbee_Footerjs_Helper_Data $helper */
        $helper = Mage::helper('meanbee_footerjs');
        if (!$helper->isEnabled()) {
            return $this;
        }

        /** @var Varien_Object $transport */
        $transport = $observer->getTransport();

        /** @var Mage_Core_Block_Abstract $block */
        $block = $observer->getBlock();

        if (Mage::app()->getRequest()->getModuleName() == 'pagecache') {
            $transport->setHtml($helper->removeJs($transport->getHtml()));
            return $this;
        }

        if (!is_null($block->getParentBlock())) {
            // Only look for JS at the root block
            return $this;
        }

        $transport->setHtml($helper->moveJsToEnd($transport->getHtml()));

        Varien_Profiler::stop('MeanbeeFooterJs');

        return $this;
    }


}
