<?php

class Meanbee_Footerjs_Helper_Data extends Mage_Core_Helper_Abstract {

    // Regular expression that matches one or more script tags (including conditions but not comments)
    const REGEX_JS            = '#(\s*<!--\[if[^\n]*>\s*(<script.*</script>)+\s*<!\[endif\]-->)|(\s*<script.*</script>)#isU';
    const REGEX_DOCUMENT_END  = '#</body>\s*</html>#isU';

    const XML_CONFIG_FOOTERJS_ENABLED = 'dev/js/meanbee_footer_js_enabled';

    /**
     * @param null $store
     *
     * @return bool
     */
    public function isEnabled($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_CONFIG_FOOTERJS_ENABLED, $store);
    }

    public function removeJs($html)
    {
        $patterns = array(
            'js'             => self::REGEX_JS
        );

        foreach($patterns as $pattern) {
            $matches = array();

            $success = preg_match_all($pattern, $html, $matches);
            if ($success) {
                $text = implode('', $matches[0]);
                $html = preg_replace($pattern, '', $html);
            }
        }

        return $html;
    }

    public function moveJsToEnd($html)
    {
        $patterns = array(
            'js'             => self::REGEX_JS,
            'document_end'   => self::REGEX_DOCUMENT_END
        );

        foreach($patterns as $pattern) {
            $matches = array();

            $success = preg_match_all($pattern, $html, $matches);
            if ($success) {
                $text = implode('', $matches[0]);
                $html = preg_replace($pattern, '', $html);
                $html = $html . $text;
            }
        }

        return $html;
    }
}