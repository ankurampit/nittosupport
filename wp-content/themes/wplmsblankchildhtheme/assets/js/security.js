function changeMenuOption(id, element) {

    const sections = ['update-profile', 'change-password'];

    sections.forEach(section => {
        const el = document.getElementById(section);
        if (el) {
            el.style.display = (section === id) ? 'block' : 'none';
        }
    });

    document.querySelectorAll('.sidebar-menu-item').forEach(item => {
        item.classList.remove('active');
    });

    element.classList.add('active');
}


document.addEventListener('DOMContentLoaded', () => {

    const form = document.getElementById('profile_form');
    const submitBtn = form.querySelector('.btn-save');
    const loader = form.querySelector('.form-loader');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(form);
        formData.append('action', 'update_user_profile');

        try {
            if (loader) loader.hidden = false;

            submitBtn.textContent = 'Saving...';
            submitBtn.disabled = true;

            const response = await fetch(profile_ajax_obj.ajax_url, {
                method: 'POST',
                body: formData,
            });

            const result = await response.json();

            if (result.success) {
                showMessage(result.data.message, 'success');
            } else {
                showMessage(result.data.message || 'Update failed', 'error');
            }

        } catch (error) {
            showMessage('Something went wrong', 'error');
            console.error(error);
        } finally {
            // ✅ HIDE LOADER
            if (loader) loader.hidden = true;

            submitBtn.textContent = 'Update Profile';
            submitBtn.disabled = false;
        }
    });

    function showMessage(message, type) {
        let msgBox = form.querySelector('.form-message');

        if (!msgBox) {
            msgBox = document.createElement('div');
            msgBox.className = 'form-message';
            form.prepend(msgBox);
        }

        msgBox.textContent = message;
        msgBox.className = `form-message ${type}`;
    }

});