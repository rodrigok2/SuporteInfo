$('#deletar-registro').on('submit', function () {
    var confirmado = confirm('Tem certeza que seja remover esse registro?');
    if (!confirmado) return false;
});
