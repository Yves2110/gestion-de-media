document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.password-input-wrapper').forEach(function (wrapper) {
        var input = wrapper.querySelector('input');
        var button = wrapper.querySelector('.password-toggle-btn');

        if (!input || !button || button.dataset.bound === '1') {
            return;
        }

        button.dataset.bound = '1';

        button.addEventListener('click', function () {
            var visible = input.type === 'text';
            input.type = visible ? 'password' : 'text';
            wrapper.classList.toggle('is-visible', !visible);
            button.setAttribute('aria-label', visible ? 'Afficher le mot de passe' : 'Masquer le mot de passe');
            button.setAttribute('title', visible ? 'Afficher le mot de passe' : 'Masquer le mot de passe');
        });
    });
});
