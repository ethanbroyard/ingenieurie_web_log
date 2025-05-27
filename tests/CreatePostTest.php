<?php
use PHPUnit\Framework\TestCase;

class CreatePostTest extends TestCase
{
    private mysqli $conn;

    protected function setUp(): void
    {
        // Connexion DB test
        $this->conn = new mysqli('localhost', 'root', 'root', 'fake_weblog');
        if ($this->conn->connect_error) {
            throw new \RuntimeException('Erreur de connexion DB test : ' . $this->conn->connect_error);
        }

        // Inclure les fonctions PHP utilisées
        require_once __DIR__ . '/../admin/post_functions.php';
        $GLOBALS['conn'] = $this->conn;

        // Supprimer les posts liés à l'utilisateur pour éviter les conflits FK
        $this->conn->query("DELETE FROM posts WHERE user_id = 1");

        // Recréer l'utilisateur avec ID 1
        $this->conn->query("DELETE FROM users WHERE id = 1");
        $this->conn->query("
            INSERT INTO users (id, username, email, role, password, created_at) 
            VALUES (1, 'admin', 'admin@example.com', 'Admin', MD5('admin'), NOW())
        ");

        // Nettoyage du post de test (au cas où)
        $this->conn->query("DELETE FROM posts WHERE slug = 'test-post'");

        // Simule la session
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $_SESSION['user'] = ['id' => 1, 'username' => 'admin', 'role' => 'Admin'];
    }

    public function testCreatePost()
    {
        $fakeImage = __DIR__ . '/fake.jpg';
        if (!file_exists($fakeImage)) {
            file_put_contents($fakeImage, 'fake-image-content');
        }

        $_FILES['featured_image'] = [
            'name' => 'fake.jpg',
            'tmp_name' => $fakeImage
        ];

        $data = [
            'title' => 'Test Post',
            'body' => 'Ceci est un test.',
            'topic_id' => 1
        ];

        $this->assertTrue(createPost($data, $_FILES));

        $stmt = $this->conn->prepare("SELECT * FROM posts WHERE slug = ?");
        $slug = 'test-post';
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        $this->assertNotEmpty($res);
        $this->assertSame('Test Post', $res['title']);
    }

    protected function tearDown(): void
    {
        $this->conn->query("DELETE FROM posts WHERE slug = 'test-post'");
        $this->conn->close();
    }
}
