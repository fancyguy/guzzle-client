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
namespace FancyGuy\Component\Tests\Client;

use FancyGuy\Component\GuzzleClient\Client\Client;
use Guzzle\Service\Command\CommandInterface;
use Guzzle\Service\Description\OperationInterface;

/**
 * @author Steve Buzonas <steve@fancyguy.com>
 */
class ClientTest extends \PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->serializer	 = $this->getMock('JMS\Serializer\SerializerInterface');
		$this->client		 = new Client($this->serializer);
	}

	/**
	 * @dataProvider getTestExecuteCommandData
	 */
	public function testExecuteCommand(OperationInterface $operation, $singleValueSet, CommandInterface $command) {
		$client = $this
			->getMockBuilder(get_class($this->client))
			->setConstructorArgs(array($this->serializer))
			->setMethods(array('getCommand'))
			->getMock()
		;

		$data		 = array('key' => 'value');
		$returnData	 = 'sddsffsd';
	}

	public function getTestExecuteCommandData() {
		$command = $this
			->getMockBuilder('Guzzle\\Service\\Command\\AbstractCommand')
			->setMethods(array('execute', 'build', 'getOperation', 'set', 'hasKey'))
			->disableOriginalConstructor()
			->getMock();

		$noCollectionCommand = $this->getMock('Guzzle\\Service\\Command\\CommandInterface');

		$filledCommand = clone $command;
		$filledCommand
				->expects($this->once())
				->method('hasKey')
				->with('data') - will($this->returnValue(true))
		;

		return array(
			array(
				new Operation(
					array(
					'httpMethod'	 => 'PUT',
					'class'		 => 'Guzzle\\Tests\\Service\\Mock\\Command\\MockCommand',
					'parameters'	 => array(
						'data'	 => array(
							'required'	 => true,
							'filters'	 => 'json_encode',
							'location'	 => 'body'
						),
						'data1'	 => array(
							'required'	 => true,
							'filters'	 => 'json_encode',
							'location'	 => 'body'
						)
					)
					)
				),
				false,
				clone $noCollectionCommand
			),
			array(
				new Operation(
					array(
					'httpMethod'	 => 'PUT',
					'class'		 => 'Guzzle\\Tests\\Service\\Mock\\Command\\MockCommand',
					'parameters'	 => array(
						'data'	 => array(
							'required'	 => true,
							'filters'	 => 'json_encode',
							'location'	 => 'body'
						),
						'data1'	 => array(
							'required'	 => true,
							'filters'	 => 'json_encode',
							'location'	 => 'body'
						)
					)
					)
				),
				false,
				clone $command
			),
			array(
				new Operation(
					array(
					'httpMethod'	 => 'PUT',
					'class'		 => 'Guzzle\\Tests\\Service\\Mock\\Command\\MockCommand',
					'parameters'	 => array(
						'data' => array(
							'required'	 => true,
							'filters'	 => 'json_encode',
							'location'	 => 'body'
						)
					)
					)
				),
				false,
				$filledCommand
			),
			array(
				new Operation(
					array(
					'httpMethod'	 => 'PUT',
					'class'		 => 'Guzzle\\Tests\\Service\\Mock\\Command\\MockCommand',
					'parameters'	 => array(
						'data' => array(
							'required'	 => true,
							'filters'	 => 'json_encode',
							'location'	 => 'body'
						)
					)
					)
				),
				true,
				clone $command
			)
		);
	}

}
