<?php
//demarre une session
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['report_message'])) {
    if (isset($_SESSION['email'])) {
        //recupere les données du formulaire
        $reporter = $_SESSION['email'];
        $sender = $_POST['sender'];
        $recipient = $_POST['recipient'];
        $date = $_POST['date'];
        $message = $_POST['message'];
        $reason = $_POST['reason'];
        $details = $_POST['details'];

        //creation du fichier de stockage des banissement
        $reportFile = 'signalements.txt';
        $report_data = $reporter . '|' . $sender . '|' . $recipient . '|' . $date . '|' . $message . '|' . $reason . '|' . $details . '|' . date('Y-m-d H:i:s') . PHP_EOL;
        if (file_put_contents($reportFile, $report_data, FILE_APPEND | LOCK_EX) !== false) {
            header("Location: boite_messagerie.php?reported=1");
            exit();
        } else {
            echo "Une erreur s'est produite lors du signalement du message.";
        }
    } else {
        echo "Vous devez être connecté pour signaler un message.";
    }
} else {
    header("Location: boite_messagerie.php");
    exit();
}