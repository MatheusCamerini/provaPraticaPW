document.addEventListener('DOMContentLoaded', function () {
    setupFlashAutoDismiss();
    setupConfirmForms();
    setupImagePreviews();
    setupCharCounters();
    setupPasswordMatchCheck();
});

// Fecha o alerta de sucesso/erro automaticamente após alguns segundos
function setupFlashAutoDismiss() {
    var alertBox = document.getElementById('flash-alert');
    if (!alertBox) return;

    setTimeout(function () {
        alertBox.style.transition = 'opacity 0.4s ease';
        alertBox.style.opacity = '0';
        setTimeout(function () {
            alertBox.remove();
        }, 400);
    }, 4000);
}

// Pede confirmação antes de enviar formulários marcados com data-confirm
// (usado nos botões de excluir / lixeira / excluir permanentemente)
function setupConfirmForms() {
    document.querySelectorAll('form[data-confirm]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            var message = form.getAttribute('data-confirm');
            if (!window.confirm(message)) {
                event.preventDefault();
            }
        });
    });
}

// Mostra uma prévia da imagem selecionada em inputs type="file"
// (capa do filme, foto de perfil)
function setupImagePreviews() {
    document.querySelectorAll('input[type="file"][data-preview]').forEach(function (input) {
        var previewId = input.getAttribute('data-preview');
        var preview = document.getElementById(previewId);
        if (!preview) return;

        input.addEventListener('change', function () {
            var file = input.files && input.files[0];
            if (!file) {
                preview.style.display = 'none';
                return;
            }
            var reader = new FileReader();
            reader.onload = function (e) {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        });
    });
}

// Contador de caracteres para campos com limite (ex.: nome do filme, máx. 30)
function setupCharCounters() {
    document.querySelectorAll('.char-counter').forEach(function (counter) {
        var fieldId = counter.getAttribute('data-for');
        var input = document.getElementById(fieldId);
        if (!input) return;

        var maxLength = input.getAttribute('maxlength') || '';

        function update() {
            counter.textContent = input.value.length + '/' + maxLength;
        }

        input.addEventListener('input', update);
        update();
    });
}

// Avisa em tempo real se a confirmação de senha bate com a senha digitada (cadastro)
function setupPasswordMatchCheck() {
    var password = document.getElementById('password');
    var confirmation = document.getElementById('password_confirmation');
    var hint = document.getElementById('password-match-hint');
    if (!password || !confirmation || !hint) return;

    function check() {
        if (!confirmation.value) {
            hint.textContent = '';
            return;
        }
        hint.textContent = password.value === confirmation.value
            ? 'As senhas coincidem.'
            : 'As senhas não coincidem.';
    }

    password.addEventListener('input', check);
    confirmation.addEventListener('input', check);
}
