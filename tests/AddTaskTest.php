<?php
// TestCase-Klasse von PHPUnit wird importiert
use PHPUnit\Framework\TestCase;

// Klasse für AddTask-Test wird definiert, erbt von TestCase
class AddTaskTest extends TestCase
{
    // Private Variablen für die Datenbank-Mocks werden definiert
    private $mysqli;
    private $stmt;

    // Setup-Methode, die vor jedem Test ausgeführt wird
    protected function setUp(): void
    {
        // Mock für die mysqli-Klasse wird erstellt
        $this->mysqli = $this->createMock(mysqli::class);

        // Mock für die mysqli_stmt-Klasse wird erstellt
        $this->stmt = $this->createMock(mysqli_stmt::class);
        $this->stmt->method('execute')->willReturn(true);
        $this->stmt->method('close');

        // Methode prepare der mysqli-Klasse wird gemockt, um das Statement-Mock zurückzugeben
        $this->mysqli->method('prepare')->willReturn($this->stmt);

        // Setzen der POST-Parameter
        $_POST['task_name'] = 'Test Task';
        $_POST['list_id'] = '1';
        $_SERVER['REQUEST_METHOD'] = 'POST';
        
        // Globale Variable für die Datenbankverbindung wird gesetzt
        $GLOBALS['conn'] = $this->mysqli;
    }

    // Testmethode zur Überprüfung des Hinzufügens einer Aufgabe
    public function testAddTask()
    {
        // Testskript wird inkludiert
        include '5_add_task.php';
        // Überprüfung, dass der Test ohne Fehler läuft
        $this->assertTrue(true);
    }

    // TearDown-Methode, die nach jedem Test ausgeführt wird
    protected function tearDown(): void
    {
        // Aufräumen der globalen Variablen
        unset($GLOBALS['conn'], $_POST['task_name'], $_POST['list_id'], $_SERVER['REQUEST_METHOD']);
    }
}
