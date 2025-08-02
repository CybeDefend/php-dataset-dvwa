class InputValidator {

    validateData(raw_data) {
        const cleaner = new SecurityCleaner();
        return cleaner.sanitizeInput(raw_data);
    }

    isValidProfile(data) {
        return data && typeof data === 'object' && data.user_details;
    }
}
