<?php
session_start();
include "0_database_connection.php";

if (isset($_GET['id'])) {
    $list_id = intval($_GET['id']);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_name = trim($_POST['list_name']);

        // Erlaubte Zeichen: alphanumerisch und ?! , ( ) -
        if (!preg_match('/^[a-zA-Z0-9?!,()\- ]*$/', $new_name)) {
            $_SESSION['error'] = 'invalid_symbol';
            header("Location: 9_edit_list.php?id=$list_id");
            exit;
        }

        // Überprüfen, ob der Name bereits existiert
        $stmt_check = $conn->prepare("SELECT id FROM lists WHERE name = ? AND id != ?");
        $stmt_check->bind_param("si", $new_name, $list_id);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $_SESSION['error'] = 'duplicate_name';
            header("Location: 9_edit_list.php?id=$list_id");
            exit;
        }

        // Update der Liste
        $stmt_update = $conn->prepare("UPDATE lists SET name = ? WHERE id = ?");
        $stmt_update->bind_param("si", $new_name, $list_id);
        $stmt_update->execute();

        $_SESSION['success'] = 'update_success';
        header("Location: 1_list_overview.php");
        exit;
    }

    // Hole die aktuelle Liste zum Bearbeiten
    $stmt = $conn->prepare("SELECT name FROM lists WHERE id = ?");
    $stmt->bind_param("i", $list_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $current_name = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
    } else {
        echo "<p style='text-align: center;'>Liste nicht gefunden</p>";
        exit;
    }
} else {
    echo "<p style='text-align: center;'>Keine Liste ausgewählt</p>";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Liste Bearbeiten</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        h1 {
            text-align: center;
        }

        .input-box {
            position: relative;
            max-width: 560px;
            width: 100%;
            background: #fff;
            padding: 10px;
            border-radius: 6px;
            box-shadow: 0 5px 10px rgba(0,0,0,0.2);
            margin: 20px auto;
        }

        .input-box input {
            width: 100%;
            outline: none;
            border: 2px solid rgba(0,0,0,0.2);
            border-radius: 6px;
            padding: 15px;
            font-size: 15px;
            font-weight: 500;
            caret-color: rgb(197, 193, 193);
            box-sizing: border-box;
        }

        .input-box input:focus,
        .input-box input:valid {
            border-color: rgb(197, 193, 193);
        }

        .input-box input::placeholder {
            font-size: 15px;
            font-weight: 500;
        }

        .input-box .title {
            margin-bottom: 10px;
            display: block;
            color: rgba(162,135,235);
        }

        .input-box .character {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            color: rgba(162,135,235);
            margin-top: 10px;
        }

        .input-box.error input {
            border-color: red;
            color: red;
        }

        .input-box.error .title {
            color: red;
        }

        .input-box.error .character {
            color: red;
        }

        .input-box button {
            display: block;
            margin: 20px auto;
            padding: 10px 20px;
            background-color: rgba(162,135,235);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 15px;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .input-box button:hover {
            background-color: rgba(162,135,235, 0.8);
        }

        .error-message, .invalid-symbol-message, .duplicate-name-message {
            display: none; /* Hide by default */
            color: red;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
        }

    </style>
</head>
<body>
    <h1>Liste Bearbeiten</h1>

    <div class="input-box">
        <form action="9_edit_list.php?id=<?php echo htmlspecialchars($list_id, ENT_QUOTES, 'UTF-8'); ?>" method="POST">
            <span class="title">Bearbeiten Sie den Listennamen</span>
            <input type="text" name="list_name" id="list_name" value="<?php echo $current_name; ?>" required maxlength="50" placeholder="Listennamen eingeben">
            <div class="character">
                <span id="char_count"><?php echo strlen($current_name); ?></span>
                <span>/50</span>
            </div>
            <p class="error-message">Achtung! Zeichenlimit erreicht (50).</p>
            <p class="invalid-symbol-message">Ungültige Zeichen verwendet. Erlaubt sind nur alphanumerische Zeichen und ?! , ( ) -.</p>
            <p class="duplicate-name-message">Eine Liste mit diesem Namen existiert bereits.</p>
            <button type="submit">Aktualisieren</button>
        </form>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const errorType = "<?php echo $_SESSION['error']; ?>";
                const errorMessages = {
                    "duplicate_name": ".duplicate-name-message",
                    "invalid_symbol": ".invalid-symbol-message",
                    "empty_title": ".empty-title-message"
                };
                const messageClass = errorMessages[errorType];

                if (messageClass) {
                    const messageElement = document.querySelector(messageClass);
                    if (messageElement) {
                        messageElement.style.display = 'block';
                        setTimeout(() => {
                            messageElement.style.display = 'none';
                        }, 5000); // Hide after 5 seconds
                    }
                }
            });
        </script>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const inputBox = document.querySelector(".input-box");
        const inputField = document.getElementById('list_name');
        const charCount = document.getElementById('char_count');
        const errorMessage = inputBox.querySelector(".error-message");
        const maxLength = 50;

        // Initial character count
        charCount.textContent = inputField.value.length;

        inputField.addEventListener('input', () => {
            const currentLength = inputField.value.length;
            charCount.textContent = currentLength;

            if (currentLength >= maxLength) {
                inputBox.classList.add('error');
                errorMessage.style.display = 'block'; // Show the error message
            } else {
                inputBox.classList.remove('error');
                errorMessage.style.display = 'none'; // Hide the error message
            }
        });
      });
    </script>
</body>
</html>
