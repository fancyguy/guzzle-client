<?php

/**
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 * 
 *    Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 * 
 *    Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 * 
 *    Neither the name of FancyGuy Technologies nor the names of its
 *    contributors may be used to endorse or promote products derived from this
 *    software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 * 
 * Copyright © 2013, FancyGuy Technologies
 * All rights reserved.
 */

namespace FancyGuy\Component\GuzzleClient\Client;

use FancyGuy\Component\GuzzleClient\Exception;
use Guzzle\Service\Client;
use Guzzle\Common\Collection;
use Guzzle\Service\Description\ServiceDescription;

/**
 * Description of GuzzleClient
 *
 * @author Steve Buzonas <steve@fancyguy.com>
 */
abstract class GuzzleClient extends Client {
    
    public static function factory($config = array(), $required = array()) {
        if (!defined('static::ENDPOINT')) {
            throw new Exception\ServiceEndpointException('A client must have an endpoint');
        }
        
        $default = array(
            'base_url'  => '{scheme}://{domain}' . static::ENDPOINT,
            'class'     => 'FancyGuy\\Component\\GuzzleClient\\GuzzleClient',
        );
        
        $required = array_merge(array('scheme', 'domain', 'base_url'), $required);
        $config = Collection::fromConfig($config, $default, $required);
        
        $client = new self($config->get('base_url'), $config);
        
        $refClass = new \ReflectionClass(get_called_class());
        
        $serviceDefinitionPath = dirname(dirname($refClass->getFileName()));
        $serviceDefinitionFile = array_pop(explode('\\', get_called_class())) . '.json';
        
        $serviceDefinition = $serviceDefinitionPath . DIRECTORY_SEPARATOR . 'Resources' . DIRECTORY_SEPARATOR . $serviceDefinitionFile;
        
        if (!is_readable($serviceDefinition)) {
            throw new Exception\ClientConfigurationException('A client must have a service definition. Could not read the file "' . $serviceDefinition . '"');
        }
        
        $description = ServiceDescription::factory($serviceDefinition);
        $client->setDescription($description);
        
        return $client;
    }
    
}
