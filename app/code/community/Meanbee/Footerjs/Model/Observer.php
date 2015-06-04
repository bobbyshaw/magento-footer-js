<?php
class Meanbee_Footerjs_Model_Observer {

    // Regular expression that matches one or more script tags (including conditions or comments)
    const REGEX_JS  = '#(\s*<![^>]*>\s*(\s*<script.*</script>)+\s*<![^>]*-->)|(\s*<script.*</script>)#isU';
    const REGEX_DOCUMENT_END    = '#</body>.*</html>#isU';

    /**
     * @param Varien_Event_Observer $observer
     *
     * @return $this
     */
    public function handleInlineJs(Varien_Event_Observer $observer) {
        /** @var Varien_Object $transport */
        $transport = $observer->getTransport();

        $patterns = array(
            'js'             => self::REGEX_JS,
            'document_end'   => self::REGEX_DOCUMENT_END
        );

        foreach($patterns as $pattern) {
            $matches = array();

            $html = $transport->getHtml();
            $success = preg_match_all($pattern, $html, $matches);
            if ($success) {
                $text = implode('', $matches[0]);
                $html = preg_replace($pattern, '', $html);
                $transport->setHtml($html . $text);
            }
        }

        return $this;
    }

}
