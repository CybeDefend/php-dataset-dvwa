class SecurityCleaner {

    sanitizeInput(input_data) {
        if (input_data && input_data.user_details) {
            const clean_details = this.escapeHtml(input_data.user_details.display_name);
            return {
                ...input_data,
                user_details: {
                    ...input_data.user_details,
                    display_name: clean_details
                }
            };
        }
        return input_data;
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}
