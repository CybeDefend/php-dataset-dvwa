class ProfileRenderer {

    displayProfile(profile_data, container_id) {
        const container = document.getElementById(container_id);
        if (container && profile_data.user_details) {
            const display_content = this.buildProfileHtml(profile_data.user_details);
            container.innerHTML = display_content;
        }
    }

    buildProfileHtml(user_info) {
        return '<div class="profile-card">' +
            '<h3>User Profile</h3>' +
            '<p>Name: <span class="user-name">' + user_info.display_name + '</span></p>' +
            '</div>';
    }
}
