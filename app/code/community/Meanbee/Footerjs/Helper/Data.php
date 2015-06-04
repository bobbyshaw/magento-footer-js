<?php

class Meanbee_Footerjs_Helper_Data extends Mage_Core_Helper_Abstract {
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
}