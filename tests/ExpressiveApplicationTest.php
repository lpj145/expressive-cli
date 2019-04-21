<?php

namespace mdantas\ExpressiveCli;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;

class ExpressiveApplicationTest extends TestCase
{
    public function testConstructor()
    {
        $this->assertInstanceOf(Application::class, new ExpressiveApplication());
    }
}
