<?php

class Meanbee_Footerjs_Model_PageCache_Observer extends Enterprise_PageCache_Model_Observer
{


    /**
     * Render placeholder tags around the block if needed
     *
     * Modified to not save JS to container cache.
     * Rely on the fact that JS is being moved to the end of the page
     * and that the JS is not changed after initial generation.
     *
     * @param Varien_Event_Observer $observer
     *
     * @return Enterprise_PageCache_Model_Observer
     */
    public function renderBlockPlaceholder(Varien_Event_Observer $observer)
    {
        if (!$this->_isEnabled) {
            return $this;
        }
        $block = $observer->getEvent()->getBlock();
        $transport = $observer->getEvent()->getTransport();
        $placeholder = $this->_config->getBlockPlaceholder($block);

        if ($transport && $placeholder && !$block->getSkipRenderTag()) {
            $blockHtml = $transport->getHtml();
            $footerJs = Mage::helper('meanbee_footerjs');
            if (in_array($block->getNameInLayout(), $footerJs->getBlocksToSkipMoveJs())) {
                $blockHtml = $footerJs->addJsToExclusion($blockHtml);
            }

            $request = Mage::app()->getFrontController()->getRequest();
            /** @var $processor Enterprise_PageCache_Model_Processor_Default */
            $processor = $this->_processor->getRequestProcessor($request);
            if ($processor && $processor->allowCache($request)) {
                $container = $placeholder->getContainerClass();
                if ($container && !Mage::getIsDeveloperMode()) {
                    $container = new $container($placeholder);
                    $container->setProcessor(Mage::getSingleton('enterprise_pagecache/processor'));
                    $container->setPlaceholderBlock($block);

                    // Modify to not save block with JS in it as JS is being moved to the end of the page.
                    $container->saveCache($footerJs->removeJs($blockHtml));
                }
            }

            $blockHtml = $placeholder->getStartTag() . $blockHtml . $placeholder->getEndTag();
            $transport->setHtml($blockHtml);
        }
        return $this;
    }
}