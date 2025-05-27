<?php
use PHPUnit\Framework\TestCase;

class GetPostBySlugTest extends TestCase
{
    private mysqli $conn;

    protected function setUp(): void
    {
        $this->conn = new mysqli('localhost', 'root', 'root', 'fake_weblog');
        require_once __DIR__ . '/../includes/all_functions.php';

        $this->conn->query("DELETE FROM posts WHERE slug = 'habits-to-improve-my-life'");

        // Ajoute un post fictif
        $this->conn->query("
            INSERT INTO posts (user_id, title, slug, image, body, published, created_at, updated_at)
            VALUES (1, 'Habits to improve my life', 'habits-to-improve-my-life', 'banner.jpg', 'Read daily', 1, NOW(), NOW())
        ");
    }

    public function testReturnsCorrectPostBySlug()
    {
        $post = getPostBySlug($this->conn, 'habits-to-improve-my-life');

        $this->assertIsArray($post);
        $this->assertEquals('habits-to-improve-my-life', $post['slug']);
        $this->assertEquals(1, $post['published']);
    }

    public function testReturnsNullIfNotFound()
    {
        $post = getPostBySlug($this->conn, 'non-existent-slug-xyz');
        $this->assertNull($post);
    }

    protected function tearDown(): void
    {
        $this->conn->query("DELETE FROM posts WHERE slug = 'habits-to-improve-my-life'");
        $this->conn->close();
    }
}
