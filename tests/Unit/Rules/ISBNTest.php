<?php

namespace Tests\Unit\Rules;

use App\Rules\ISBN;
use Tests\TestCase;
class ISBNTest extends TestCase
{
    /** @test */
    public function the_isbn_rule_can_be_validated()
    {
        $rule = ['isbn' => [new ISBN]];

        $this->assertFalse(validator(['isbn' => '1'], $rule)->passes());
        $this->assertFalse(validator(['isbn' => '1#'], $rule)->passes());
        $this->assertFalse(validator(['isbn' => 'a1#'], $rule)->passes());
        $this->assertFalse(validator(['isbn' => '853590277500'], $rule)->passes());
        $this->assertTrue(validator(['isbn' => '0005534186'], $rule)->passes());
        $this->assertTrue(validator(['isbn' => '0005534186'], $rule)->passes());
        $this->assertTrue(validator(['isbn' => '05-931-3913-5'], $rule)->passes());
        $this->assertTrue(validator(['isbn' => '05 931 3913 5'], $rule)->passes());
    }
}
