<?php
use PHPUnit\Framework\TestCase;

class ListOverviewTest extends TestCase
{
    public function testSessionStarted()
    {
        // Startet Output Buffering, um HTML-Ausgabe zu verhindern
        ob_start();
        // Inkludiert die zu testende Datei
        include '1_list_overview.php';
        // Reinigt den Buffer und beendet ihn
        ob_end_clean();

        // Überprüfung, ob die Session gestartet wurde
        $this->assertTrue(session_status() === PHP_SESSION_ACTIVE);
    }
}
