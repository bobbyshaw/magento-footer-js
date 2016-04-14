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
            Varien_Profiler::stop('MeanbeeFooterJs');
            return $this;
        }

        /** @var Mage_Core_Controller_Response_Http $response */
        $response = $observer->getResponse();
        $response->setBody($helper->moveJsToEnd($response->getBody()));

        Varien_Profiler::stop('MeanbeeFooterJs');

        return $this;
    }

}
