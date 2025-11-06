<?php

namespace App\Services;

use App\Model\Configuration;

class ConfigurationService
{
    /**
     * Get configuration value by type
     */
    public function getConfig($type)
    {
        $config = Configuration::first();
        
        if (!$config) {
            return null;
        }

        $json = (array) json_decode($config->config);

        return $json[$type] ?? null;
    }

    /**
     * Get multiple configuration values
     */
    public function getConfigs(array $types)
    {
        $config = Configuration::first();
        
        if (!$config) {
            return array_fill_keys($types, null);
        }

        $json = (array) json_decode($config->config);
        $result = [];

        foreach ($types as $type) {
            $result[$type] = $json[$type] ?? null;
        }

        return $result;
    }

    /**
     * Get all configuration as array
     */
    public function getAllConfig()
    {
        $config = Configuration::first();
        
        if (!$config) {
            return [];
        }

        return (array) json_decode($config->config);
    }
}
