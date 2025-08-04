<!-- 
require_once "../db/config.php";
require_once "../includes/accessHandler.php";


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['code'])) {
    $code = $_POST['code'];

    $db = new dbConnect();
    $conn = $db->connect();

    $handler = new AccessHandler($conn);
    $status = $handler->checkCode($code);
    $handler->logAttempt($code, $status);

    if ($status === 'Success') {
        echo "<script>
            alert('Access Granted ✅');
            window.location.href = '../lock.php';
        </script>";
    } else {
        echo "<script>
            alert('Access Denied ❌');
            window.location.href = '../lock.php';
        </script>";
    }
} else {
    echo "<script>
        alert('No code submitted.');
        window.location.href = '../lock.php';
    </script>";
} -->
