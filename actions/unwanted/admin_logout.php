<!-- 
session_start();
// class logout



    // public function __construct()
    // {


        if (session_status() === PHP_SESSION_ACTIVE) {
            // Clear session variables
            $_SESSION = [];
            session_unset();
            // Destroy the session
            session_destroy();
            $_SESSION['message'] ='logout successfully';

            // location.reload();
            header("Location: ../admin_login.php");
            exit;
        }

        echo "You have been logged out successfully.";
     -->
<!-- // } -->
