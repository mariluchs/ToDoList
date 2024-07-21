<?php
// TestCase-Klasse von PHPUnit wird importiert
use PHPUnit\Framework\TestCase;

// Klasse für Datenbanktest wird definiert, erbt von TestCase
class DatabaseTest extends TestCase
{
    // Methode zur Überprüfung der Datenbankverbindung wird definiert
    public function testConnectionIsValid()
    {
        // Datei mit der Datenbankverbindung wird geladen
        require '0_database_connection.php';

        // Überprüfung, ob die Verbindung nicht null ist
        $this->assertNotNull($conn);  
        
        // Überprüfung, ob es keinen Verbindungsfehler gibt
        $this->assertNull($conn->connect_error); 
    }
}
