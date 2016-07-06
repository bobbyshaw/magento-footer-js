<?php

class Meanbee_Footerjs_Helper_Data extends Mage_Core_Helper_Abstract {

    // Regular expression that matches one or more script tags (including conditions but not comments)
    const REGEX_JS            = '#(\s*<!--(\[if[^\n]*>)?\s*(<script.*</script>)+\s*(<!\[endif\])?-->)|(\s*<script.*</script>)#isU';
    const REGEX_DOCUMENT_END  = '#</body>\s*</html>#isU';

    const XML_CONFIG_FOOTERJS_ENABLED = 'dev/js/meanbee_footer_js_enabled';
    const XML_CONFIG_FOOTERJS_EXCLUDED_BLOCKS = 'dev/js/meanbee_footer_js_excluded_blocks';
    const XML_CONFIG_FOOTERJS_EXCLUDED_FILES = 'dev/js/meanbee_footer_js_excluded_files';

    const EXCLUDE_FLAG = 'data-footer-js-skip="true"';
    const EXCLUDE_FLAG_PATTERN = 'data-footer-js-skip';

    /** @var array */
    protected $_blocksToExclude;

    /** @var string */
    protected $skippedFilesRegex;

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
                foreach ($matches[0] as $key => $js) {
                    if (strpos($js, self::EXCLUDE_FLAG_PATTERN) !== false) {
                        unset($matches[0][$key]);
                    }
                }
                $html = str_replace($matches[0], '', $html);
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
                // Strip excluded files
                if ($this->getSkippedFilesRegex() !== false) {
                    $matches[0] = preg_grep($this->getSkippedFilesRegex(), $matches[0], PREG_GREP_INVERT);
                }
                foreach ($matches[0] as $key => $js) {
                    if (strpos($js, self::EXCLUDE_FLAG_PATTERN) !== false) {
                        unset($matches[0][$key]);
                    }
                }
                $text = implode('', $matches[0]);
                $html = str_replace($matches[0], '', $html);
                $html = $html . $text;
            }
        }

        return $html;
    }

    public function getSkippedFilesRegex()
    {
        if ($this->skippedFilesRegex === null) {
            $skipConfig = trim(Mage::getStoreConfig(self::XML_CONFIG_FOOTERJS_EXCLUDED_FILES));
            if ($skipConfig !== '') {
                $skippedFiles = preg_replace('/\s*,\s*/', '|', $skipConfig);
                $this->skippedFilesRegex = sprintf("@src=.*?(%s)@", $skippedFiles);
            } else {
                $this->skippedFilesRegex = false;
            }
        }
        return $this->skippedFilesRegex;
    }

    /**
     * Add skip flag to all js in given html
     * 
     * @param string $html
     * @return string
     */
    public function addJsToExclusion($html)
    {
        return str_replace('<script', '<script ' . self::EXCLUDE_FLAG, $html);
    }

    /**
     * Get list of block names (in layout) to exclude their JS from moving to footer
     *
     * @return array
     */
    public function getBlocksToSkipMoveJs()
    {
        if (is_null($this->_blocksToExclude)) {
            $string = Mage::getStoreConfig(self::XML_CONFIG_FOOTERJS_EXCLUDED_BLOCKS);
            $exludedBlocks = explode(',', $string);
            foreach ($exludedBlocks as $key => $blockName) {
                $exludedBlocks[$key] = trim($blockName);
                if (strpos($exludedBlocks[$key], "\n") || strpos($exludedBlocks[$key], ' ')) {
                    Mage::log('Missing comma in setting "' . self::XML_CONFIG_FOOTERJS_EXCLUDED_BLOCKS . '"', Zend_Log::ERR, null, true);
                }
            }
            $this->_blocksToExclude = array_filter($exludedBlocks);
        }
        return $this->_blocksToExclude;
    }
}
