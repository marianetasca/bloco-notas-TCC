//mensagem de sucesso
//auto-close após 4 segundos
setTimeout(() => {
    document
        .querySelectorAll(".alert .btn-close")
        .forEach((btn) => btn.click());
}, 4000);



//quill -> formatação para o checklist
document.querySelectorAll('.conteudo-nota li[data-list]').forEach(li => {
    const type = li.getAttribute('data-list');

    // Verifica se já não tem o ícone
    if (!li.querySelector('i')) {
        let icon = document.createElement('i');
        icon.style.marginRight = '0.5em';
        icon.style.fontSize = '1rem';

        if (type === 'checked') icon.className = 'bi bi-check-square-fill', icon.style.color = 'green';
        else if (type === 'unchecked') icon.className = 'bi bi-square';
        else if (type === 'ordered') icon.className = ''; // não precisa, números aparecem via CSS

        li.prepend(icon);
    }
});

// VIZUALIZAR SENHA
document.addEventListener('DOMContentLoaded', function() {
    const toggleButtons = document.querySelectorAll('.password-toggle');

    toggleButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();

            const container = this.closest('.position-relative');
            if (!container) {
                console.error('Container não encontrado');
                return;
            }

            // Tenta encontrar o input de várias formas
            let passwordInput = container.querySelector('.password-input');

            // Se não encontrar com classe, tenta por tipo
            if (!passwordInput) {
                passwordInput = container.querySelector('input[type="password"]');
            }

            // Se ainda não encontrar, tenta qualquer input
            if (!passwordInput) {
                passwordInput = container.querySelector('input');
            }

            if (!passwordInput) {
                console.error('Input não encontrado de forma alguma');
                console.log('Container:', container);
                return;
            }

            const icon = this.querySelector('.toggle-icon');
            if (!icon) {
                console.error('Ícone não encontrado');
                return;
            }

            // Toggle da senha
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });
    });
});


// ERROS DE VALIDAÇÃO DE SENHA
document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('password');

    if (!passwordInput) {
        // Não há campo de senha nesta página -> não anexa listeners
        return;
    }

    passwordInput.addEventListener('input', () => {
        const value = passwordInput.value;

        // Critérios
        const rules = [
            { regex: /[a-z]/, el: 'lowercase' },
            { regex: /[A-Z]/, el: 'uppercase' },
            { regex: /[0-9]/, el: 'number' },
            { regex: /[@$!%*#?&]/, el: 'symbol' },
            { regex: /.{8,}/, el: 'minlength' }
        ];

        rules.forEach(rule => {
            const li = document.getElementById(rule.el);
            if (!li) return; // some pages may not include the criteria list
            if (rule.regex.test(value)) {
                li.classList.remove('text-danger');
                li.classList.add('text-success');
            } else {
                li.classList.remove('text-success');
                li.classList.add('text-danger');
            }
        });
    });
});



//Notificação acessada pelo email
document.addEventListener('DOMContentLoaded', function() {
    const highlightedNote = document.querySelector('.highlight-nota');

    if (highlightedNote) {
        // Remove o highlight após 5 segundos
        setTimeout(() => {
            highlightedNote.classList.add('fade-out');
        }, 3000); // 3 segundos antes de começar a desaparecer

        // Remove a classe completamente após a animação
        setTimeout(() => {
            highlightedNote.classList.remove('highlight-nota', 'fade-out');
        }, 5000); // 5 segundos total
    }
});
