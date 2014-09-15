<?php
/**
 * This file is part of the phpunit-mink library.
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @copyright Alexander Obuhovich <aik.bold@gmail.com>
 * @link      https://github.com/aik099/phpunit-mink
 */

namespace aik099\PHPUnit\Session;


use Behat\Mink\Driver\SahiDriver;

use Behat\Mink\Driver\Goutte\Client;

use Behat\Mink\Driver\GoutteDriver;

use aik099\PHPUnit\BrowserConfiguration\BrowserConfiguration;
use Behat\Mink\Driver\DriverInterface;
use Behat\Mink\Driver\Selenium2Driver;
use Behat\Mink\Session;

/**
 * Produces sessions.
 *
 * @method \Mockery\Expectation shouldReceive
 */
class SessionFactory implements ISessionFactory
{

	/**
	 * Creates new session based on browser configuration.
	 *
	 * @param BrowserConfiguration $browser Browser configuration.
	 *
	 * @return Session
	 */
	public function createSession(BrowserConfiguration $browser)
	{
		return new Session($this->_createDriver($browser));
	}

	/**
	 * Creates driver based on browser configuration.
	 *
	 * @param BrowserConfiguration $browser Browser configuration.
	 *
	 * @return DriverInterface
	 */
	private function _createDriver(BrowserConfiguration $browser)
	{
		$browser_name = $browser->getBrowserName();
		$capabilities = $browser->getDesiredCapabilities();
		$capabilities['browserName'] = $browser_name;

		// TODO: maybe doesn't work!
		ini_set('default_socket_timeout', $browser->getTimeout());
		
	
		switch ($browser->getDriverName()){
			case 'Selenium2Driver':
				$driver = new Selenium2Driver (
					$browser_name,
					$capabilities,
					'http://' . $browser->getHost() . ':' . $browser->getPort() . '/wd/hub'
				);
				break;
			case 'GoutteDriver':
				$driver = new GoutteDriver(new Client());
				break;
			case 'SahiDriver':
				$driver = new SahiDriver($browser_name);
				break;
			default:
				$driver = new GoutteDriver(new Client());	
				break;
		}

		return $driver;
	}

}
