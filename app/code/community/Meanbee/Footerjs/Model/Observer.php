<?php

class Meanbee_Footerjs_Model_Observer {

    // Regular expression that matches one or more script tags (including conditions but not comments)
    const REGEX_JS            = '#(\s*<!--\[if[^\n]*>\s*(<script.*</script>)+\s*<!\[endif\]-->)|(\s*<script.*</script>)#isU';
    const REGEX_DOCUMENT_END  = '#</body>\s*</html>#isU';

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function handleInlineJs(Varien_Event_Observer $observer)
    {
        Varien_Profiler::start('MeanbeeFooterJs');

        /** @var Meanbee_Footerjs_Helper_Data $helper */
        $helper = Mage::helper('meanbee_footerjs');
        if (!$helper->isEnabled()) {
            return $this;
        }

        /** @var Mage_Core_Controller_Response_Http $response */
        $response = $observer->getResponse();

        $patterns = array(
            'js'             => self::REGEX_JS,
            'document_end'   => self::REGEX_DOCUMENT_END
        );

        foreach($patterns as $pattern) {
            $matches = array();

            $html = $response->getBody();
            $success = preg_match_all($pattern, $html, $matches);
            if ($success) {
                $text = implode('', $matches[0]);
                $html = preg_replace($pattern, '', $html);
                $response->setBody($html . $text);
            }
        }

        Varien_Profiler::stop('MeanbeeFooterJs');

        return $this;
    }
}
