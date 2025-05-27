<?php
use PHPUnit\Framework\TestCase;

class GetPublishedPostsTest extends TestCase
{
    private mysqli $conn;

    protected function setUp(): void
    {
        $this->conn = new mysqli('localhost', 'root', 'root', 'fake_weblog');
        $GLOBALS['conn'] = $this->conn;
        require_once __DIR__ . '/../includes/all_functions.php';
    }

    public function testGetPublishedPostsReturnsArray()
    {
        $posts = getPublishedPosts($this->conn);
        $this->assertIsArray($posts);
    }

    protected function tearDown(): void
    {
        $this->conn->close();
    }
}
