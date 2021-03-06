<?php namespace Tests;


class TestCase extends \Laravel\Lumen\Testing\TestCase
{

    protected static $dbRefresh = [];

    public function __construct($name = null, array $data = array(), $dataName = '')
    {
        putenv("APP_ENV=testing");
        putenv("CLOCKWORK_COLLECT_DATA_ALWAYS=false");
        putenv("CLOCKWORK_ENABLE=false");
        $this->baseUrl = env('BASE_URL_TESTING');

        parent::__construct($name, $data, $dataName);
    }

    public function setUp()
    {
        parent::setUp();

        if (!isset(self::$dbRefresh[get_class($this)])) {

            $this->artisan('migrate:refresh');
            $this->seed();
            self::$dbRefresh[get_class($this)] = true;
        }
    }

    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__ . '/../bootstrap/app.php';
    }

    /**
     * @param $text
     *
     */
    public function debug($text)
    {
        fwrite(STDOUT, $text . " \n");
    }
}
