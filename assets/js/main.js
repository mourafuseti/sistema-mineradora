// main.js - Funções Globais

// Confirmação antes de excluir qualquer item
function confirmarExclusao(event) {
    if (!confirm("Tem certeza que deseja excluir este item? Esta ação não pode ser desfeita.")) {
        event.preventDefault();
    }
}

// Formatar moeda (BRL) automaticamente nos inputs
document.addEventListener('DOMContentLoaded', function() {
    const inputsValor = document.querySelectorAll('.input-money');
    inputsValor.forEach(input => {
        input.addEventListener('input', function(e) {
            // Lógica simples de máscara pode ser adicionada aqui
        });
    });
});

// Ativar Tooltips do Bootstrap (se usar)
var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
  return new bootstrap.Tooltip(tooltipTriggerEl)
})