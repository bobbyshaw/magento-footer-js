<?php

class Meanbee_Footerjs_Model_Observer
{

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
            Varien_Profiler::stop('MeanbeeFooterJs');
            return $this;
        }

        /** @var Varien_Object $transport */
        $transport = $observer->getTransport();

        /** @var Mage_Core_Block_Abstract $block */
        $block = $observer->getBlock();

        if (in_array($block->getNameInLayout(), $helper->getBlocksToSkipMoveJs())) {
            $transport->setHtml($helper->addJsToExclusion($transport->getHtml()));
        }

        if (Mage::app()->getRequest()->getModuleName() == 'pagecache') {
            $transport->setHtml($helper->removeJs($transport->getHtml()));
            Varien_Profiler::stop('MeanbeeFooterJs');
            return $this;
        }

        if (!is_null($block->getParentBlock())) {
            Varien_Profiler::stop('MeanbeeFooterJs');
            // Only look for JS at the root block
            return $this;
        }

        $transport->setHtml($helper->moveJsToEnd($transport->getHtml()));

        Varien_Profiler::stop('MeanbeeFooterJs');

        return $this;
    }


}
