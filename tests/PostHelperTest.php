<?php 
// tests/PostHelperTest.php
use PHPUnit\Framework\TestCase;

class PostHelperTest extends TestCase
{
    public function testSlugFormatting()
    {
        require_once __DIR__ . '/../includes/helpers.php'; // adapte le chemin

        $slug = formatSlug('Hello World !');
        $this->assertEquals('hello-world', $slug);
    }
}
