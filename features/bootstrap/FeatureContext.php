<?php

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\MinkContext;

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends MinkContext implements Context
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

    /**
     * @When I submit the form with a list name :listName
     */
    public function iSubmitTheFormWithAListName($listName)
    {
        $this->fillField('list_name', $listName);
        $this->pressButton('list_name'); // Der Name des Submit-Buttons im Formular
    }

    /**
     * @Then I should be redirected to the list overview page
     */
    public function iShouldBeRedirectedToTheListOverviewPage()
    {
        $this->assertPageAddress('/1_list_overview.php'); // Pfad zur Listenübersichtsseite
    }

    /**
     * @Then I should see the list :listName in the database
     */
    public function iShouldSeeTheListInTheDatabase($listName)
    {
        $stmt = $this->conn->prepare("SELECT name FROM lists WHERE name = ?");
        $stmt->bind_param("s", $listName);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows === 0) {
            throw new Exception("List '$listName' not found in the database.");
        }
        $stmt->close();
    }

    public function __destruct()
    {
        $this->conn->close();
    }



}
