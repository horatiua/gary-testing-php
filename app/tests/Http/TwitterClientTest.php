<?php

namespace App\Tests\Http;

use App\Http\ApplicationClientInterface;
use App\Http\TwitterClient;
use PHPUnit\Framework\TestCase;

class TwitterClientTest extends TestCase
{
    /**
     * @test
     *
     * We want to test in isolation
     * There is a dependency which hits a 3rd party service
     * ApplicationClientInterface makes it easy to switch for a mock
     * Would not work without an internet connection
     */
    function getUserById_returns_correctly_formatted_user_data()
    {
        // $this->markTestIncomplete('Work in progress...');

        // Setup
        // The createStub() and createMock() methods return a test double object
        // The __construct (and __clone()) method of the original class is not executed
        // By default, all methods of the original class are replaced with a
        // dummy implementation that returns null
        $applicationClient = $this->createMock(ApplicationClientInterface::class);
        $twitterClient = new TwitterClient($applicationClient);
        $accountId = 1234;
        $applicationClient
            ->expects($this->once())
            // ->expects($this->exactly(2))
            // ->expects($this->atLeastOnce())
            ->method('get')
            // ->with('https://api.twitter.com/2/users/' . $accountId . '?user.fields=public_metrics')
            // ->with($this->stringContains($accountId))
            ->with($this->anything())
            ->willReturn('{"data":{"name":"PHPUnit","username":"phpunit","public_metrics": {"followers_count":2227,"following_count":0,"tweet_count":525,"listed_count":107},"id":"1234"}}');

        // Do something
        $user = $twitterClient->getUserById($accountId);

        // Make assertions
        $this->assertEquals(
            [
                "followers_count" => 2227,
                "following_count" => 0,
                "tweet_count"     => 525,
                "listed_count"    => 107,
                "name"            => "PHPUnit",
                "id"              => "1234",
                "username"        => "phpunit",
            ],
            $user
        );
    }
}