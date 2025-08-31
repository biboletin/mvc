<?php

namespace Tests\Unit;

use Bibo\Mvc\Core\Cache\FileCache;
use Bibo\Mvc\Core\Config\ConfigHandler;
use Bibo\Mvc\Core\Exception\Custom\Application\ConfigException;
use Bibo\Mvc\Core\Interfaces\ConfigInterface;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ConfigHandler::class)]
#[UsesClass(ConfigHandler::class)]
class ConfigTest extends TestCase
{
    private ?ConfigHandler $config = null;

    /**
     * Sets up the test environment before each test.
     *
     * @throws ConfigException
     */
    public function setUp(): void
    {
        $configDir = sys_get_temp_dir() . '/mvc/config/';

        $this->config = new ConfigHandler();
        $this->config->setConfigPath();
        $this->config->setFileCaching(new FileCache(
            sys_get_temp_dir() . '/mvc/cache/config/'
        ));
        $this->config->loadFromFile(ROOT_PATH . '.env');
        $this->config->load();
    }

    /**
     * Cleans up the test environment after each test.
     */
    public function tearDown(): void
    {
        $this->config = null;
    }

    /**
 * Test Methods
*/
    #[TestDox('Config instance should implement ConfigInterface')]
    public function testConfigInstance()
    {
        $this->assertInstanceOf(ConfigInterface::class, $this->config);
    }

    /**
     * Test loading configuration files.
     *
     * @throws ConfigException
*/
    #[TestDox('Config load() should load all configuration sections')]
    public function testLoad()
    {
        $this->config->load();

        $sections = [
            'api',
            'app',
            'backup',
            'cache',
            'cookies',
            'cors',
            'db',
            'encryption',
            'endpoints',
            'error',
            'ftp',
            'hash',
            'log',
            'mail',
            'security',
            'session',
            'web',
        ];

        $this->assertSame($sections, array_keys($this->config->all()));
    }

    #[TestDox('Config all() should return an array of all configurations')]
    public function testAll()
    {
        $this->assertIsArray($this->config->all(), 'Config all() should return an array');
    }

    #[TestDox('Config get() should retrieve configuration values')]
    public function testGet()
    {
        $this->assertEquals('MyApp', $this->config->get('app.name'));
    }

    #[TestDox('Config has() should check for existence of configuration keys')]
    public function testHas()
    {
        $this->assertTrue($this->config->has('app.name'));
        $this->assertFalse($this->config->has('nonexistent.key'));
    }
}
