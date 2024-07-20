Feature: Listenverwaltung
    In order to manage my tasks
    As a user
    I want to create a new list 

Scenario: Eine neue Liste erstellen
    Given I am on the create list page
    When I submit the form with a list name "Einkaufsliste"
    Then I should be redirected to the list overview page
    And I should see the list "Einkaufsliste" in the database
    