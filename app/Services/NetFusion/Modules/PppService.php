<?php

namespace App\Services\NetFusion\Modules;

use App\Services\NetFusion\MikhmonAPI;

class PppService
{
    protected $api;

    public function __construct(MikhmonAPI $api)
    {
        $this->api = $api;
    }

    /**
     * Get PPP Secrets
     */
    public function getSecrets()
    {
        return $this->api->comm('/ppp/secret/print');
    }

    /**
     * Add PPP Secret
     */
    public function addSecret($data)
    {
        return $this->api->comm('/ppp/secret/add', $data);
    }

    /**
     * Remove PPP Secret
     */
    public function removeSecret($id)
    {
        return $this->api->comm('/ppp/secret/remove', ['.id' => $id]);
    }

    /**
     * Get PPP Profiles
     */
    public function getProfiles()
    {
        return $this->api->comm('/ppp/profile/print');
    }

    /**
     * Add PPP Profile
     */
    public function addProfile($data)
    {
        return $this->api->comm('/ppp/profile/add', $data);
    }

    /**
     * Remove PPP Profile
     */
    public function removeProfile($id)
    {
        return $this->api->comm('/ppp/profile/remove', ['.id' => $id]);
    }

    /**
     * Get Active PPP Connections
     */
    public function getActive()
    {
        return $this->api->comm('/ppp/active/print');
    }

    /**
     * Remove Active PPP Connection (Disconnect)
     */
    public function removeActive($id)
    {
        return $this->api->comm('/ppp/active/remove', ['.id' => $id]);
    }
}
