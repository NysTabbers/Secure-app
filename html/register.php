<?php

require_once("../php/Procedures/storedProcedures.php");

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/register.css">
</head>

<body>

<div class="register-wrapper">
    <div class="register">
        <p>Maak uw account</p>
        <form action="../php/Procedures/storedProcedures.php" method="post">
            <input type="text" id="name" placeholder="Vul uw naam in" required>
            <input type="email" id="email" placeholder="Vul uw email in" required>
            <input type="password" id="password" placeholder="Vul uw wachtwoord in" required>
            <button class="button">Maak account</button>
        </form>
    </div>
</div>


</body>

</html>