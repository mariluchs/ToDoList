<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $conn;
    public function __construct()
    {
        // Initialisieren der Datenbankverbindung für Tests
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "test_db"; // Test-Datenbank

        $this->conn = new mysqli($servername, $username, $password, $dbname);

        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    /**
     * @Given I am on the list overview page
     */
    public function iAmOnTheListOverviewPage()
    {
        $this->visitPath('/1_list_overview.php'); // Pfad zur Seite, auf der das Formular zum Erstellen der Liste ist
    }
}
