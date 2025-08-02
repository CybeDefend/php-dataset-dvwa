class ProfileManager {

    constructor() {
        this.dataProcessor = new DataProcessor();
    }

    initializeProfile(container_id) {
        this.loadProfileData(container_id);
    }

    loadProfileData(target_container) {
        fetch('profile_api.php', {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => this.dataProcessor.handleResponse(data, target_container));
    }
}
