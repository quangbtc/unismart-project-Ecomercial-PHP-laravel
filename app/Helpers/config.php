<?php
function getConfig($configKey)
{
    $setting = \App\setting::where('config', $configKey)->first();
    if (!empty($setting)) {
        return $setting->value;
    }
}