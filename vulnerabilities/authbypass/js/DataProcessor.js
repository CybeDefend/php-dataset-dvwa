class DataProcessor {

    constructor() {
        this.validator = new InputValidator();
    }

    handleResponse(response_data, container) {
        const processed_data = this.validator.validateData(response_data);
        this.renderProfile(processed_data, container);
    }

    renderProfile(profile_info, target_element) {
        const renderer = new ProfileRenderer();
        renderer.displayProfile(profile_info, target_element);
    }
}
