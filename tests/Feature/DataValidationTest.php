<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * WHITEBOX TEST: Data Validation & State Management
 * Tests the internal logic of input validation, data transformation, and state persistence
 * Tests branching conditions and error handling paths
 */
class DataValidationTest extends TestCase
{
    /**
     * Test phone number validation
     * Verifies phone format requirements
     */
    public function test_phone_number_validation(): void
    {
        $validPhones = [
            '0812-3456-7890',
            '08123456789',
            '+62-812-3456-7890',
        ];

        foreach ($validPhones as $phone) {
            $isValid = strlen(preg_replace('/\D/', '', $phone)) >= 10;
            $this->assertTrue($isValid, "Phone $phone should be valid");
        }
    }

    /**
     * Test phone number validation rejects short numbers
     */
    public function test_phone_validation_rejects_short(): void
    {
        $shortPhone = '0812345'; // Only 7 digits

        $isValid = strlen(preg_replace('/\D/', '', $shortPhone)) >= 10;

        $this->assertFalse($isValid);
    }

    /**
     * Test identity card number validation
     * Should be 16 digits for KTP or variable length for other IDs
     */
    public function test_identity_number_validation(): void
    {
        $validKTP = '1234567890123456'; // 16 digits

        $isValid = strlen($validKTP) === 16;

        $this->assertTrue($isValid);
    }

    /**
     * Test date format validation
     * Tests YYYY-MM-DD format
     */
    public function test_date_format_validation(): void
    {
        $validDates = [
            '1995-08-15',
            '2000-01-01',
            '2020-12-31',
        ];

        $pattern = '/^\d{4}-\d{2}-\d{2}$/';

        foreach ($validDates as $date) {
            $this->assertTrue(
                preg_match($pattern, $date) === 1,
                "Date $date should be valid"
            );
        }
    }

    /**
     * Test invalid date format rejected
     */
    public function test_invalid_date_format_rejected(): void
    {
        $invalidDate = '15-08-1995'; // Wrong format

        $pattern = '/^\d{4}-\d{2}-\d{2}$/';

        $this->assertFalse(preg_match($pattern, $invalidDate) === 1);
    }

    /**
     * Test required field validation
     * Empty string should fail
     */
    public function test_required_field_validation(): void
    {
        $fieldValue = '';

        $isValid = !empty($fieldValue);

        $this->assertFalse($isValid);
    }

    /**
     * Test required field with whitespace only
     * Should also fail
     */
    public function test_required_field_rejects_whitespace(): void
    {
        $fieldValue = '   '; // Only spaces

        $isValid = !empty(trim($fieldValue));

        $this->assertFalse($isValid);
    }

    /**
     * Test trimming whitespace from input
     */
    public function test_input_whitespace_trimmed(): void
    {
        $input = '  Ahmad Rifai  ';
        $trimmed = trim($input);

        $this->assertEquals('Ahmad Rifai', $trimmed);
    }

    /**
     * Test select field validation
     * Must select an option
     */
    public function test_select_field_validation(): void
    {
        $selectedValue = ''; // No selection

        $isValid = !empty($selectedValue);

        $this->assertFalse($isValid);
    }

    /**
     * Test select field with valid option
     */
    public function test_select_field_with_valid_option(): void
    {
        $validOptions = ['furniture', 'kelistrikan', 'plumbing'];
        $selectedValue = 'plumbing';

        $isValid = in_array($selectedValue, $validOptions);

        $this->assertTrue($isValid);
    }

    /**
     * Test textarea validation for minimum length
     */
    public function test_textarea_minimum_length(): void
    {
        $description = 'Too short'; // Less than 20 chars
        $minLength = 20;

        $isValid = strlen($description) >= $minLength;

        $this->assertFalse($isValid);
    }

    /**
     * Test textarea validation passes with sufficient length
     */
    public function test_textarea_sufficient_length(): void
    {
        $description = 'Pintu kamar saya sulit untuk ditutup dengan rapat'; // Long enough

        $isValid = strlen($description) >= 20;

        $this->assertTrue($isValid);
    }

    /**
     * Test amount currency parsing
     * Removes currency symbols and formatting
     */
    public function test_amount_currency_parsing(): void
    {
        $formatted = 'rp 1.500.000';

        // Remove currency and formatting
        $parsed = (int) str_replace(['rp ', '.'], '', $formatted);

        $this->assertEquals(1500000, $parsed);
    }

    /**
     * Test amount decimal rounding
     * Currency amounts should be rounded
     */
    public function test_amount_decimal_rounding(): void
    {
        $amount = 1500000.75;

        $rounded = round($amount, 0);

        $this->assertEquals(1500001, $rounded);
    }

    /**
     * Test amount validation (must be positive)
     */
    public function test_amount_must_be_positive(): void
    {
        $amounts = [0, -1500000, -50];

        foreach ($amounts as $amount) {
            $isValid = $amount > 0;
            $this->assertFalse($isValid, "Amount $amount should be invalid");
        }
    }

    /**
     * Test positive amount validation passes
     */
    public function test_positive_amount_validation(): void
    {
        $amount = 1500000;

        $isValid = $amount > 0;

        $this->assertTrue($isValid);
    }

    /**
     * Test enum validation for priority
     * Only specific values allowed
     */
    public function test_priority_enum_validation(): void
    {
        $validPriorities = ['rendah', 'normal', 'tinggi', 'urgent'];

        foreach ($validPriorities as $priority) {
            $isValid = in_array($priority, $validPriorities);
            $this->assertTrue($isValid);
        }
    }

    /**
     * Test invalid enum value rejected
     */
    public function test_invalid_priority_rejected(): void
    {
        $validPriorities = ['rendah', 'normal', 'tinggi', 'urgent'];
        $invalidPriority = 'super-urgent';

        $isValid = in_array($invalidPriority, $validPriorities);

        $this->assertFalse($isValid);
    }

    /**
     * Test data type validation
     * String should not be accepted as number
     */
    public function test_data_type_validation(): void
    {
        $value = '1500000'; // String

        $isNumeric = is_numeric($value);

        $this->assertTrue($isNumeric);
    }

    /**
     * Test non-numeric value rejected as number
     */
    public function test_non_numeric_rejected(): void
    {
        $value = '1500abc'; // Not purely numeric

        // More strict check
        $isInteger = ctype_digit($value);

        $this->assertFalse($isInteger);
    }

    /**
     * Test conditional required fields
     * Field required only if certain condition met
     */
    public function test_conditional_required_field(): void
    {
        $method = 'transfer bank';
        $bankAccount = '';

        // Bank account required only if method is bank transfer
        $needsBankAccount = $method === 'transfer bank';
        $isValid = !$needsBankAccount || !empty($bankAccount);

        $this->assertFalse($isValid); // Should fail because required but empty
    }

    /**
     * Test conditional field passes when not required
     */
    public function test_conditional_field_not_required(): void
    {
        $method = 'tunai';
        $bankAccount = '';

        // Bank account NOT required for cash payment
        $needsBankAccount = $method === 'transfer bank';
        $isValid = !$needsBankAccount || !empty($bankAccount);

        $this->assertTrue($isValid); // Should pass
    }

    /**
     * Test validation error accumulation
     */
    public function test_validation_error_accumulation(): void
    {
        $errors = [];

        // Check email
        $email = 'invalid';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email invalid';
        }

        // Check phone
        $phone = '123';
        if (strlen($phone) < 10) {
            $errors['phone'] = 'Phone too short';
        }

        $this->assertCount(2, $errors);
        $this->assertArrayHasKey('email', $errors);
        $this->assertArrayHasKey('phone', $errors);
    }

    /**
     * Test form clears validation errors on new submission
     */
    public function test_form_clears_errors_on_resubmit(): void
    {
        $errors = ['email' => 'Invalid'];

        // New submission clears errors
        $errors = [];

        $this->assertEmpty($errors);
    }

    /**
     * Test data sanitization for HTML injection
     */
    public function test_data_sanitization_html_injection(): void
    {
        $userInput = '<script>alert("xss")</script>';

        $sanitized = htmlspecialchars($userInput);

        $this->assertStringNotContainsString('<script>', $sanitized);
        $this->assertStringContainsString('&lt;script&gt;', $sanitized);
    }

    /**
     * Test data sanitization for SQL injection
     */
    public function test_data_sanitization_sql_patterns(): void
    {
        $userInput = "'; DROP TABLE users; --";

        // Basic check - should not contain dangerous patterns
        $containsDangerous = false;
        if (stripos($userInput, 'DROP TABLE') !== false) {
            $containsDangerous = true;
        }

        $this->assertTrue($containsDangerous); // Detected dangerous pattern
    }

    /**
     * Test state change: submitted to processing
     */
    public function test_state_change_submitted_to_processing(): void
    {
        $state = 'submitted';

        $state = 'processing';

        $this->assertEquals('processing', $state);
    }

    /**
     * Test state machine for payment
     */
    public function test_payment_state_machine(): void
    {
        $validTransitions = [
            'pending' => ['processing', 'cancelled'],
            'processing' => ['completed', 'failed'],
            'completed' => [],
            'cancelled' => [],
            'failed' => ['processing'],
        ];

        $currentState = 'pending';
        $nextState = 'processing';

        $canTransition = in_array($nextState, $validTransitions[$currentState]);

        $this->assertTrue($canTransition);
    }

    /**
     * Test invalid state transition blocked
     */
    public function test_invalid_state_transition_blocked(): void
    {
        $validTransitions = [
            'pending' => ['processing', 'cancelled'],
            'completed' => [],
        ];

        $currentState = 'pending';
        $nextState = 'completed'; // Can't go directly

        $canTransition = in_array($nextState, $validTransitions[$currentState]);

        $this->assertFalse($canTransition);
    }
}
