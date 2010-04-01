<?php
require_once dirname(__FILE__) . '/../../bootstrap.php';
require_once dirname(__FILE__) . '/../../../src/lib/FileSystem/Interface.php';
require_once dirname(__FILE__) . '/../../../src/lib/FileSystem/Error.php';
require_once dirname(__FILE__) . '/../../../src/lib/FileSystem/Driver.php';
require_once dirname(__FILE__) . '/../../../src/lib/FileSystem/Ftp.php';
require_once dirname(__FILE__) . '/../../../src/lib/FileSystem/Facade/Ftp.php';
require_once dirname(__FILE__) . '/../../../src/lib/FileSystem/Configuration.php';
require_once dirname(__FILE__) . '/../../../src/lib/FileSystem/Configuration/Ftp.php';
require_once dirname(__FILE__) . '/../../../src/lib/FileSystem/Configuration/Sftp.php';

/**
 * FileSystem_Error test case.
 */
class FileSystem_DriverTest extends PHPUnit_Framework_TestCase
{
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
    }
    
    

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated FileSystem_ErrorTest::tearDown()

        parent::tearDown();
    }

    /**
     * Constructs the test case.
     */
    public function __construct()
    {
        // TODO Auto-generated constructor
    }

    public function test_construct()
    {
    	try {
    	   $config = new FileSystem_Configuration_Ftp();
            $driverAbstract = new FileSystem_Ftp($config);
    	} catch (InvalidArgumentException $expected) {
            $this->fail('Should not be an exception here');
        }
    	$this->setExpectedException('InvalidArgumentException');
        $config = new FileSystem_Configuration_Sftp();
        $driverAbstract = new FileSystem_Ftp($config);
    }
    

}