<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use League\CommonMark\Environment\Environment;
use Tests\TestCase;

class EnvironmentTest extends TestCase
{
    public function testEnv()
    {
        $appName = env("YOUTUBE");

        self::assertEquals("Programmer Zaman Now", $appName);
    
    }

    public function testDefaultValue()
    {
        $author = env("AUTHOR", "Eko");

        self::assertEquals("Eko", $author);
    
    }

    // public function testEnvironment(){
    //     if (App:environment("testing")){
    //         echo "LOGIC IN TESTONG ENV" . PHP_OEL;
    //         self::assertEquals()
    //     }
    // }

    
}


