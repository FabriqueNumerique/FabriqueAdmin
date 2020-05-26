$('#add-offre').click(function () {

    const index = +$('#widgets-counter').val()

    // ici on récupere le prototype des entrées
    const tmpl = $('#entreprise_Offres').data('prototype').replace(/__name__/g, index)

    //ici, j'injecte le code récupéré dans mon fromulaire
    $('#entreprise_Offres').append(tmpl)

    $('#widgets-counter').val(index + 1)
    deleteSousFrom()
})

//supprimer un sous form
function deleteSousFrom() {
    $('button[data-action="delete"]').click(function () {
        const target = this.dataset.target
        console.log(target)
        $(target).remove()
    })
}

//recalculer combien j'ai de sous from
function updateCounter() {
    const count = +$('#entreprise_Offres div.form-group').length
    $('#widgets-counter').val(count)
}
updateCounter()
deleteSousFrom()