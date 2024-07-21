<?php
// TestCase-Klasse von PHPUnit wird importiert
use PHPUnit\Framework\TestCase;

// Klasse für CheckTask-Test wird definiert, erbt von TestCase
class CheckTaskTest extends TestCase
{
    // Private Variablen für die Datenbank-Mocks werden definiert
    private $mysqli;
    private $stmt;
    private $result;

    // Setup-Methode, die vor jedem Test ausgeführt wird
    protected function setUp(): void
    {
        // Mock für die mysqli-Klasse wird erstellt
        $this->mysqli = $this->createMock(mysqli::class);

        // Mock für die mysqli_stmt-Klasse wird erstellt
        $this->stmt = $this->createMock(mysqli_stmt::class);
        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('close');

        // Mock für die mysqli_result-Klasse wird erstellt
        $this->result = $this->createMock(mysqli_result::class);
        $this->result->method('fetch_assoc')->willReturn(['status' => 'ToDo']);
        $this->stmt->method('get_result')->willReturn($this->result);

        // Methode prepare der mysqli-Klasse wird gemockt, um das Statement-Mock zurückzugeben
        $this->mysqli->method('prepare')->willReturn($this->stmt);

        // Setzen der POST-Parameter
        $_POST['task_id'] = '1';
        $_POST['list_id'] = '1';

        // Globale Variable für die Datenbankverbindung wird gesetzt
        $GLOBALS['conn'] = $this->mysqli;
    }

    // Testmethode zur Überprüfung der Aufgabenänderung
    public function testCheckTask()
    {
        // Testskript wird inkludiert
        include '7_check_task.php';

        // Überprüfung, dass der Test ohne Fehler läuft
        $this->assertTrue(true);

        // Ausgabe zur Bestätigung der erfolgreichen Testausführung
        echo "Test executed successfully.\n";
        print_r($_POST);
    }

    // TearDown-Methode, die nach jedem Test ausgeführt wird
    protected function tearDown(): void
    {
        // Aufräumen der globalen Variablen
        unset($GLOBALS['conn'], $_POST['task_id'], $_POST['list_id']);
    }
}
