<?php
class Meanbee_Footerjs_Model_Observer {

    public function handleInlineJs(Varien_Event_Observer $observer) {
        /** @var Mage_Core_Block_Abstract $block */
        $block = $observer->getBlock();
        /** @var Varien_Object $transport */
        $transport = $observer->getTransport();
        $html = $transport->getHtml();

        $ignoredBlocks = array('inline.js');
        if (in_array($block->getNameInLayout(), $ignoredBlocks)) {
            return false;
        }

        /** @var Mage_Core_Block_Text $inlineBlock */
        $inlineBlock = $block->getLayout()->getBlock('inline.js');

        $jsPattern = '#<script.*</script>#isU';
        $conditionalJsPattern = '#<\!--\[if[^\>]*>\s*<script.*</script>\s*<\!\[endif\]-->#isU';

        // First deal with conditionals
        $matches = array();
        $success = preg_match_all($conditionalJsPattern, $html, $matches);
        if ($success) {
            $text = implode('', $matches[0]);

            $before = ($block->getNameInLayout() == "head") ? true : false;
            $inlineBlock->addText($text, $before);

            $html = preg_replace($conditionalJsPattern, '' , $html);
            $transport->setHtml($html);
        }

        // Then the rest of the javascript
        $matches = array();
        $success = preg_match_all($jsPattern, $html, $matches);
        if ($success) {
            $text = implode('', $matches[0]);

            $before = ($block->getNameInLayout() == "head") ? true : false;
            $inlineBlock->addText($text, $before);

            $html = preg_replace($jsPattern, '' , $html);
            $transport->setHtml($html);
        }

        return $this;
    }

}
