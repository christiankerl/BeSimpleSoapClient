<?php

/*
 * This file is part of the BeSimpleSoapClient.
 *
 * (c) Christian Kerl <christian-kerl@web.de>
 * (c) Francis Besset <francis.besset@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace BeSimple\SoapClient;

use BeSimple\SoapCommon\SoapBaseBuilder;

/**
 * SoapClientBuilder provides a fluent interface to configure and create a SoapClient instance.
 * 
 * @author Christian Kerl
 */
class SoapClientBuilder extends SoapBaseBuilder
{
    public static function createEmpty()
    {
        return new self();
    }
    
    public static function createWithDefaults()
    {
        $builder = new self();
        $builder
            ->withSoapVersion12()
            ->withEncoding('UTF-8')
            ->withNoWsdlCache()
            ->withSingleElementArrays()
        ;
        
        return $builder;
    }
    
    /**
     * Initializes all options with the defaults used in the ext/soap SoapClient.
     */
    protected function __construct()
    {
        parent::__construct();
    }
    
    public function build()
    {
        $this->validateOptions();
        
        $client = new \SoapClient($this->optionWsdl, $this->options);
        
        return $client;
    }
    
    private function validateOptions()
    {
        if(null === $this->optionWsdl)
        {
            throw new \InvalidArgumentException('The WSDL has to be configured!');
        }
    }
}
