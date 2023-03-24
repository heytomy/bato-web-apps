document.querySelectorAll('[data-reply]').forEach(function(button) {
    button.addEventListener('click', function(event) {
        event.preventDefault();
        var commentId = button.getAttribute('data-id');
        var formHtml = "{{ form(replyForm, {'attr': {'class': 'row col p-2'}}) }}";
        formHtml = formHtml.replace(/__parentId__/g, commentId);
        var formContainer = document.querySelector('#form-container-' + commentId);
        formContainer.innerHTML = formHtml;

        document.querySelector("#rep_commentaires_sav_parentid").value = this.dataset.id;
    });
});