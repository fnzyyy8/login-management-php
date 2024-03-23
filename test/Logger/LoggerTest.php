<?php

namespace Rrim\PhpUserManagement\Logger;

use Monolog\Formatter\JsonFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use PHPUnit\Event\Telemetry\Info;
use PHPUnit\Framework\TestCase;

class LoggerTest extends TestCase
{
    public function testLogger()
    {
        $logger = new Logger(LoggerTest::class);

        self::assertNotNull($logger);

        var_dump($logger);

    }

    public function testHandler()
    {
        $logger = new Logger(LoggerTest::class);
        $logger->pushHandler(new StreamHandler("php://stderr"));
        $logger->pushHandler(new StreamHandler(__DIR__ . "/../../Logging/application.log"));
        $logger->info("Selamat datang di logger handling");

        self::assertEquals(2, sizeof($logger->getHandlers()));

    }

    public function testLevelLogger()
    {
        $logger = new Logger(LoggerTest::class);
        $logger->pushHandler(new StreamHandler("php://stdout", Level::Error));

        $logger->debug("Ini debug");
        $logger->info("Ini info");
        $logger->notice("Ini Notice");
        $logger->warning("Ini Warning");
        $logger->error("Ini Error");
        $logger->critical("Ini Critical");
        $logger->alert("Ini Alert");
        $logger->emergency("Ini Emergency");

        self::assertNotNull($logger);
    }

    public function testContext()
    {
        $logger = new Logger(LoggerTest::class);
        $logger->pushHandler(new StreamHandler("php://stdout", Level::Info));

        $user = "Farhan";
        $logger->info("Login", ["username" => $user]);

        self::assertNotNull($logger);

    }

    public function testProcessor()
    {
        $logger = new Logger(LoggerTest::class);
        $logger->pushHandler(new StreamHandler("php://stdout", Level::Info));

        $logger->pushProcessor(function ($records) {

            $records['extra']['rrim'] = [
                "app" => "Login Management"
            ];

            return $records;
        });

        $logger->info("Hello", ["username" => "Farhan"]);

        self::assertNotNull($logger);

    }

    public function testFormatter()
    {
        $logger = new Logger(LoggerTest::class);
        $stdoutHandler = new StreamHandler(__DIR__ . "/../../Logging/log.json", Level::Info);
        $stdoutHandler->setFormatter(new JsonFormatter());

        $logger->pushHandler($stdoutHandler);

        $id = uniqid();
        $logger->info("id", ["username" => [
            "id" => $id,
            "user" => "Farhan"
        ]]);
        self::assertNotNull($logger);

    }


}