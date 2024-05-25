<?php

/**
 * Validator class for form validation using a set of preconfigured rules.
 */
class Validator {
    /**
     * Array of Rule instances.
     * @var array<string,array<string,mixed>>
     */
    private array $ruleset = [];

    /**
     * Accumulated validation error messages in an associative array.
     * Key is input name, value is an array of validation message strings.
     * @var array<string,array<string>>
     */
    private array $messages = [];

    /**
     * Validator class for form validation using a set of preconfigured rules.
     * @param array<string,array<string,mixed>> $rules The validation rules.
     */
    public function __construct(array $rules) {
        // Set up the validator
        $this->ruleset = $rules;
    }

    /**
     * Validates a form coming from a $_POST or $_GET superglobal.
     * @param array<string,string> $form The form to validate.
     * @return bool Validation successful.
     */
    public function validate(array $form) {
        // 1. Clean the error messages
        $this->messages = [];
        // 2. For every rule in the ruleset
        foreach ($this->ruleset as $key => $rules) {
            // Initialize subarray to prevent getting uninitialized variable errors when reading messages.
            $this->messages[$key] = [];
        //      3. If we have an input for the rule
            $value = array_key_exists($key, $form) ? $form[$key] : null;
    //          4. For every rule in the rules for the input except required
            $req = $rules[ValidationRule::Required] === true;
            $pres = !!$value;
            if ($req && !$pres) {
                $this->messages[$key][] = 'Field is required';
                continue;
            } else if (!$req && !$pres) {
                continue;
            }
    //              5. Validate input for rule
            if ($key === 'email' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                // Validate email pattern
            } else if (array_key_exists(ValidationRule::Contains, $rules) && !str_contains($value, $rules[ValidationRule::Contains])) {
                array_push($this->messages[$key], 'Field must contain ' . $rules[ValidationRule::Contains]);
            } else if (array_key_exists(ValidationRule::Min, $rules) && $value < $rules[ValidationRule::Min]) {
                array_push($this->messages[$key], 'Field must be greater than ' . $rules[ValidationRule::Min]);
            } else if (array_key_exists(ValidationRule::Max, $rules) && $value > $rules[ValidationRule::Max]) {
                array_push($this->messages[$key], 'Field must be lesser than ' . $rules[ValidationRule::Max]);
            } else if (array_key_exists(ValidationRule::ValidPassword, $rules) && !password_verify($value, $rules[ValidationRule::ValidPassword])) {
                array_push($this->messages[$key], 'Username or password is invalid');
            } else if (array_key_exists(ValidationRule::ConfirmPassword, $rules) && $value !== $form[$rules[ValidationRule::ConfirmPassword]]) {
                array_push($this->messages[$key], 'Password and Confirm Password must match');
            }
        //              6. If input is invalid and required and present?
        //                  7. Add error message to messages for input

            if (count($this->messages[$key]) === 0) {
                unset($this->messages[$key]);
            }
        }

        return count($this->messages) === 0;
    }

    /**
     * Returns the accumulated validation errors.
     * @return array<string,array<string>> The messages for every input.
     */
    public function getMessages() {
        return $this->messages;
    }

    /**
     * Checks if the current validator instance is in a valid state.
     * @return bool The validator's state is valid.
     */
    public function isValid() {

    }

    
}

class ValidationRule {
    const Required = 'required';

    const Contains = 'contains';

    const Min = 'min';

    const Max = 'max';

    const Email = 'email';

    const ValidPassword = 'validPassword';

    const Date = 'date';

    const ConfirmPassword = 'confirmPassword';

    const HexColor = 'hexColor';
    
}