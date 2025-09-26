<?php
header('Content-Type: application/json');
define('DB_HOST', 'dlocalhost');
define('DB_NAME', 'thadingyut_db');
define('DB_USER', 'root');
define('DB_PASS', '');

// The $pdo object is available here from db_connect.
function getDbConnection()
{
    static $conn = null;
    if ($conn === null) {
        try {
            $conn = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Initialize database tables if they don't exist
            // initializeDatabase($conn);
        } catch (PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            return false;
        }
    }
    return $conn;
}

// --- GET: Fetch all wishes (Securely fetched) ---
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // No user input, but prepared statement is still best practice
        $conn = getDbConnection();
        $stmt = $conn->prepare("SELECT wish_message, author_name, color_code FROM wishes ORDER BY created_at DESC");
        $stmt->execute();
        $wishes = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($wishes);
    } catch (\PDOException $e) {
        http_response_code(500);
        echo json_encode(["error" => "Failed to fetch wishes.", "details" => $e->getMessage()]);
    }
}

// --- POST: Save a new wish (SECURED VIA PREPARED STATEMENTS) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    $wish_message = $data['wish_message'] ?? '';
    $author_name = $data['author_name'] ?? 'Anonymous';
    $color_code_hex = $data['color_code'] ?? '#ff9800';

    $color_code = hexdec(ltrim($color_code_hex, '#'));

    if (empty($wish_message)) {
        http_response_code(400);
        echo json_encode(["error" => "Wish message cannot be empty."]);
        exit;
    }

    try {
        // Use prepared statements with placeholders (?) for secure insertion
        $conn = getDbConnection();
        $stmt = $conn->prepare("INSERT INTO wishes (wish_message, author_name, color_code) VALUES (?, ?, ?)");

        // Execute the statement. PDO safely quotes and escapes the user-provided variables.
        $stmt->execute([$wish_message, $author_name, $color_code]);

        echo json_encode(["success" => true, "message" => "Wish saved successfully."]);
    } catch (\PDOException $e) {
        http_response_code(500);
        echo json_encode(["success" => false, "error" => "Failed to save wish.", "details" => $e->getMessage()]);
    }
}
