// Máscara para nome: só permite letras e espaços e evita espaços duplos
function maskNome(v) {
    return (v || '')
        .replace(/[^a-zA-ZÀ-ÿ\s]/g, '') // remove números e símbolos
        .replace(/\s{2,}/g, ' ')        // evita espaços duplos
        .replace(/^\s+|\s+$/g, '');     // remove espaços no início e no fim
}

// Função para aplicar a máscara
function applyMask(el) {
    const type = el.getAttribute('data-mask');
    if (!type) return;

    let masked = el.value;
    if (type === "nome") masked = maskNome(el.value);

    if (el.value !== masked) el.value = masked;
}


document.querySelectorAll('[data-mask="nome"]').forEach(input => {
    input.addEventListener('input', () => applyMask(input));
});

  

  function attachMasks() {
    const inputs = document.querySelectorAll('input[data-mask]');
    inputs.forEach((input) => {
      applyMask(input);
  
      input.addEventListener('input', () => applyMask(input));
      input.addEventListener('blur', () => applyMask(input));
      input.addEventListener('paste', () => setTimeout(() => applyMask(input), 0));
    });
  }
  
  document.addEventListener('DOMContentLoaded', attachMasks);
  



 
function formatarValor(input) {
    let valor = input.value.replace(/\D/g, ''); 
    valor = (valor/100).toFixed(2).replace('.', ','); 
    valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, "."); 
    input.value = valor;
}


function formatarQuantidade(input) {
    input.value = input.value.replace(/\D/g, ''); 
}


window.addEventListener('DOMContentLoaded', () => {
    const qtdeInput = document.getElementById('qtde');
    const valorInput = document.getElementById('valor_unit');

    if (qtdeInput) qtdeInput.addEventListener('keyup', () => formatarQuantidade(qtdeInput));
    if (valorInput) valorInput.addEventListener('keyup', () => formatarValor(valorInput));

   
    if (qtdeInput && qtdeInput.value) formatarQuantidade(qtdeInput);
    if (valorInput && valorInput.value) formatarValor(valorInput);
});






function formatarValor(input) {
    let valor = input.value.replace(/\D/g, ''); 
    valor = (valor/100).toFixed(2).replace('.', ','); 
    valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, "."); 
    input.value = valor;
}


function formatarQuantidade(input) {
    input.value = input.value.replace(/\D/g, ''); 
}


window.addEventListener('DOMContentLoaded', () => {
    const qtdeInput = document.getElementById('quantidade');
    const valorInput = document.getElementById('valor_unitario');

    if (qtdeInput) qtdeInput.addEventListener('keyup', () => formatarQuantidade(qtdeInput));
    if (valorInput) valorInput.addEventListener('keyup', () => formatarValor(valorInput));

    
    if (qtdeInput && qtdeInput.value) formatarQuantidade(qtdeInput);
    if (valorInput && valorInput.value) formatarValor(valorInput);
});

