<?php
/*
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
 * Copyright (c) 2013, FancyGuy Technologies
 * All rights reserved.
 */
namespace FancyGuy\Component\GuzzleClient\Client;

use Guzzle\Common\Collection;
use Guzzle\Service\Client as BaseClient;
use JMS\Serializer\SerializerInterface;

/**
 * @author Steve Buzonas <steve@fancyguy.com>
 */
class Client extends BaseClient {

	/**
	 * @var SerializerInterface
	 */
	protected $serializer;

	public function __construct(SerializerInterface $serializer) {
		parent::__construct();
		$this->serializer = $serializer;
	}

	public function executeCommand($commandName, $args = array()) {
		$args = json_decode($this->serializer->serialize($args, 'json'), true);

		$command	 = $this->getCommand($commandName, (array) $args);
		$operation	 = $command->getOperation();

		if ($command instanceof Collection && count($paramNames = $operation->getParamNames())) {
			$wireName = $operation->getParam($paramNames[0])->getWireName();

			if (count($paramNames) == 1 && !$command->hasKey($wireName)) {
				$command->set($wireName, $args);
			}
		}

		return $command->execute();
	}

	public function __call($command, $arguments = array()) {
		$args = isset($arguments[0]) ? $arguments[0] : $arguments;
		try {
			return $this->executeCommand(ucwords($command), $args);
		} catch (\InvalidArgumentException $e) {
			return $this->executeCommand($command, $args);
		}
	}

}
