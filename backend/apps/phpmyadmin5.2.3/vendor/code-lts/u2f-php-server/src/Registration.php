<?php

namespace CodeLts\U2F\U2FServer;

class Registration
{
    
    protected $keyHandle;
    
    protected $publicKey;
    
    protected $certificate;
    
    protected $counter = -1;

    /**
     * @param string $keyHandle
     * @return void
     */
    public function setKeyHandle($keyHandle)
    {
        $this->keyHandle = $keyHandle;
    }

    /**
     * @param string $publicKey
     * @return void
     */
    public function setPublicKey($publicKey)
    {
        $this->publicKey = $publicKey;
    }

    /**
     * @param string $certificate
     * @return void
     */
    public function setCertificate($certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * @return string
     */
    public function getKeyHandle()
    {
        return $this->keyHandle;
    }

    /**
     * @return string
     */
    public function getPublicKey()
    {
        return $this->publicKey;
    }

    /**
     * @return string
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * @return int
     */
    public function getCounter()
    {
        return $this->counter;
    }

}
