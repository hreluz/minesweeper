<?php

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        return $app;
    }

    public function getTestMinesPositions()
    {
        return [  
            1 => [
                1 => true
            ],
            3 => [
                3 => true
            ],
            4 => [
                3 => true
            ]
        ];
    }

    public function getTestGrid()
    {
        return [  
            0 => [
                0 => 1,
                1 => 1,
                2 => 1,
                3 => 0,
                4 => 0
            ],
            1 => [
                0 => 1,
                1 => -1,
                2 => 1,
                3 => 0,
                4 => 0
            ],
            2 => [
                0 => 1,
                1 => 1,
                2 => 2,
                3 => 1,
                4 => 1
            ],
            3 => [
                0 => 0,
                1 => 0,
                2 => 2,
                3 => -1,
                4 => 2
            ],
            4 => [
                0 => 0,
                1 => 0,
                2 => 2,
                3 => -1,
                4 => 2
            ]
        ];
    }
}
