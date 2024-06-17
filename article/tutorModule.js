document.getElementById('new-article-form').addEventListener('submit', submitForm);

function submitForm(event) {
    event.preventDefault();

    const form = document.getElementById('new-article-form');
    const formData = new FormData(form);

    fetch('createArticle.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Article successfully saved to server');
            location.reload();
        } else {
            console.error('Error:', data.error);
        }
    })
    .catch(error => console.error('Error:', error));
}
