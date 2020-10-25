<?php

class paymentModuleInfo
{
    public $payment_code;

    public $keys;

    // class constructor

    public function __construct($pmInfo_array)
    {
        $this->payment_code = $pmInfo_array['payment_code'];

        for ($i = 0, $n = count($pmInfo_array) - 1; $i < $n; $i++) {
            $key_value_query = tep_db_query('select configuration_title, configuration_value, configuration_description from ' . TABLE_CONFIGURATION . " where configuration_key = '" . $pmInfo_array[$i] . "'");

            $key_value = tep_db_fetch_array($key_value_query);

            $this->keys[$pmInfo_array[$i]]['title'] = $key_value['configuration_title'];

            $this->keys[$pmInfo_array[$i]]['value'] = $key_value['configuration_value'];

            $this->keys[$pmInfo_array[$i]]['description'] = $key_value['configuration_description'];
        }
    }
}
