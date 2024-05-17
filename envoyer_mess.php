<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Envoyer un message</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
            background-image: url('voiture2.jpg');
        }
        .bhead {
            background-color: black;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-title {
            color: white;
            margin: 0;
        }
        .header-buttons {
            display: flex;
        }
        .bhead button {
            background-color: red;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 10px;
        }
        .bhead button:hover {
            background-color: darkred;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
        }
        input[type="text"],
        textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="bhead">
        <h1 class="header-title">Envoyer un message</h1>
        <div class="header-buttons">
            <button onclick="window.location.href='boite_messagerie.php'">Retour à la messagerie</button>
        </div>
    </div>
    <div class="container">
        <h1>Envoyer un message</h1>
        <?php
        $recipient_email = isset($_GET['recipient']) ? htmlspecialchars($_GET['recipient']) : '';
        ?>
        <form action="envoyer_message.php" method="post">
            <label for="recipient">Destinataire:</label>
            <input type="text" id="recipient" name="recipient" value="<?php echo $recipient_email; ?>" required>
            <label for="message">Message:</label>
            <textarea id="message" name="message" rows="5" required></textarea>
            <input type="submit" value="Envoyer">
        </form>
    </div>
</body>
</html>
