<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $answer = isset($_POST['answer']) ? $_POST['answer'] : '';
    echo "You entered: " . htmlspecialchars($answer);
} else {
    echo "No answer received.";
}
?>
