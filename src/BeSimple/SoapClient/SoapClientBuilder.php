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
            ->withExceptions()
        ;
        
        return $builder;
    }
    
    private $optionAuthentication;
    private $optionEndpointLocation;
    
    /**
     * Initializes all options with the defaults used in the ext/soap SoapClient.
     */
    protected function __construct()
    {
        parent::__construct();
        
        $this->options['compression'] = false;
        $this->options['user_agent']  = null;
        $this->options['trace']       = false;
        
        $this->optionAuthentication = array();
        $this->optionEndpointLocation = null;
    }
    
    public function build()
    {
        $this->validateOptions();
        
        $this->options = array_merge($this->options, $this->optionAuthentication);
        
        $client = new \SoapClient($this->optionWsdl, $this->options);
        
        if(null !== $this->optionEndpointLocation)
        {
        	$client->__setLocation($this->optionEndpointLocation);
        }
        
        return $client;
    }
    
    private function validateOptions()
    {
        if(null === $this->optionWsdl)
        {
            throw new \InvalidArgumentException('The WSDL has to be configured!');
        }
    }

    public function withEndpointLocation($location)
    {
    	$this->optionEndpointLocation = $location;
    	
    	return $this;
    }
    
    public function withProxyServer($host, $port, $username, $password)
    { 
        $this->options['proxy_host']     = $host;
        $this->options['proxy_port']     = $port;
        $this->options['proxy_login']    = $username;
        $this->options['proxy_password'] = $password;
        
        return $this; 
    }
    
    public function withHttpSession()
    {
    	// TODO: this should enable sending the session cookie received from the server with every http request
    	
        return $this;
    }
    
    public function withHttpCompression()
    { 
        $this->options['compression'] = true; 
        
        return $this; 
    }

    public function withHttpUserAgent($user_agent)
    { 
        $this->options['user_agent'] = $user_agent; 
        
        return $this; 
    }
    
    public function withHttpBasicAuthentication($username, $password)
    {
    	$this->optionAuthentication = array(
    	    'authentication' => SOAP_AUTHENTICATION_BASIC,
    		'login'          => $username,
    		'password'       => $password
    	);
    	
        return $this;
    }
    
    public function withHttpDigestAuthentication($certificate, $password)
    {
    	$this->optionAuthentication = array(
    	    'authentication' => SOAP_AUTHENTICATION_DIGEST,
    		'local_cert'     => $certificate,
    		'passphrase'     => $password
    	);
    	
        return $this;
    }
    
    public function withRequestAndResponseTracing()
    { 
        $this->options['trace'] = true; 
        
        return $this; 
    }
    
    public function withExceptions()
    { 
        $this->options['exceptions'] = true; 
        
        return $this; 
    }
    
}
