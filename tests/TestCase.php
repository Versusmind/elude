<?php

class TestCase extends Laravel\Lumen\Testing\TestCase
{
    const STRING = '#.*#';
    const NUMBER = '#[0-9]+#';
    const FLOAT = '#[0-9]+\.[0-9]+#';

    public static $regex = [
        self::STRING,
        self::NUMBER,
        self::FLOAT
    ];

    public function __construct($name = null, array $data = array(), $dataName = '')
    {

        $this->baseUrl = env('BASE_URL_TESTING');

        parent::__construct($name, $data, $dataName);
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

    public function assertMatchPattern(array $pattern)
    {
        $data = (array)json_decode($this->response->getContent());
        $this->assertArrayMatchPattern($pattern, $data);

        return $this;
    }

    public function assertArrayMatchPattern(array $pattern, $data)
    {
        foreach ($pattern as $key => $element) {
            $this->assertArrayHasKey($key, $data);
            if (is_array($element)) {
                $this->assertInternalType('array', $data[$key]);
                $this->assertArrayMatchPattern($data[$key], $element);
            } else {
                if (in_array($element, self::$regex, true)) {
                    $this->assertRegExp($element, $data[$key], $key . ' must match pattern ' . $element . ' found ' . $data[$key]);

                } else {
                    $this->assertEquals($element, $data[$key]);
                }
            }
        }
    }
}
