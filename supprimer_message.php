<?php
session_start();
if (isset($_SESSION['email']) && isset($_GET['index']) && isset($_GET['recipient'])) {
    $filename = 'messages.txt';
    if (file_exists($filename)) {
        $messages = file($filename, FILE_IGNORE_NEW_LINES);
        $index = intval($_GET['index']);
        if (isset($messages[$index])) {
            $message_data = explode('|', $messages[$index]);
            if (count($message_data) === 4) {
                $sender = $message_data[0];
                $recipient_in_message = $message_data[1];
                if (($sender === $_SESSION['email'] && $recipient_in_message === $_GET['recipient']) ||
                    ($sender === $_GET['recipient'] && $recipient_in_message === $_SESSION['email'])) {
                    unset($messages[$index]);
                    file_put_contents($filename, implode(PHP_EOL, $messages) . PHP_EOL);
                    header("Location: boite_messagerie.php?recipient=" . urlencode($_GET['recipient']) . "&success=1");
                    exit();
                }
            }
        }
    }
}
header("Location: boite_messagerie.php?error=1");
exit();
?>
