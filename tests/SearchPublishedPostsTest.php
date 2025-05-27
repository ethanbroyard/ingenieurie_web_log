<?php
use PHPUnit\Framework\TestCase;

class SearchPublishedPostsTest extends TestCase
{
    private mysqli $conn;

    protected function setUp(): void
    {
        $this->conn = new mysqli('localhost', 'root', 'root', 'fake_weblog');
        require_once __DIR__ . '/../includes/all_functions.php';
    }

    public function testSearchByTitleKeyword()
    {
        $filters = ['search' => 'habits', 'topic_id' => ''];
        $posts = searchPublishedPosts($this->conn, $filters);

        $this->assertIsArray($posts);
        foreach ($posts as $post) {
            $this->assertStringContainsStringIgnoringCase('habits', $post['title'] . $post['body']);
        }
    }

    public function testSearchByTopicId()
    {
        $filters = ['search' => '', 'topic_id' => 1];
        $posts = searchPublishedPosts($this->conn, $filters);

        $this->assertIsArray($posts);
        foreach ($posts as $post) {
            $this->assertArrayHasKey('title', $post);
        }
    }
}
