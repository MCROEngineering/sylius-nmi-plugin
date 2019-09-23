@managing_payment_method_nmi
Feature: Adding a new payment method
    In order to pay for orders in different ways
    As an Administrator
    I want to add a new payment method to the registry

    Background:
        Given the store operates on a single channel in "United States"
        And I am logged in as an administrator

    @ui
    Scenario: Adding a new NMI payment method
        Given I want to create a new NMI payment method
        When I name it "NMI" in "English (United States)"
        And I specify its code as "nmi_test"
        And I configure it with test NMI credentials
        And I add it
        Then I should be notified that it has been successfully created
        And the payment method "NMI" should appear in the registry
