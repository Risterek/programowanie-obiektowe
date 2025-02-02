<?php
class User {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function login($username, $password) {
        try {
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
            if (!$stmt) {
                error_log("Błąd przygotowania zapytania: " . $conn->error);
                return false;
            }

            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                // TODO: W przyszłości zmienić na password_verify($password, $row['password'])
                if ($row['password'] === $password) {
                    $_SESSION["username"] = $row['username'];
                    $stmt->close();
                    return true;
                }
            }
            $stmt->close();
            return false;
        } catch (Exception $e) {
            error_log("Błąd logowania: " . $e->getMessage());
            return false;
        }
    }

    public function logout() {
        try {
            // Czyszczenie wszystkich danych sesji
            $_SESSION = array();

            // Usuwanie ciasteczka sesji jeśli istnieje
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time() - 3600, '/');
            }

            // Zniszczenie sesji
            session_destroy();
            return true;
        } catch (Exception $e) {
            error_log("Błąd podczas wylogowywania: " . $e->getMessage());
            return false;
        }
    }

    public function isLoggedIn() {
        return isset($_SESSION['username']) && !empty($_SESSION['username']);
    }

    // Metoda do sprawdzania czy użytkownik istnieje
    public function userExists($username) {
        try {
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM users WHERE username = ?");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();
            return $row['count'] > 0;
        } catch (Exception $e) {
            error_log("Błąd sprawdzania użytkownika: " . $e->getMessage());
            return false;
        }
    }

   
}
?>