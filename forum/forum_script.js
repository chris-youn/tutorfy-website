document.getElementById('new-thread-form').addEventListener('submit', submitForm);

function submitForm(event) {
    event.preventDefault();

    const form = document.getElementById('new-thread-form');
    const formData = new FormData(form);

    const newPost = document.createElement('div');
    newPost.classList.add('posts');
    newPost.dataset.timestamp = new Date().getTime();

    const date = new Date().toLocaleString();

    let postHTML = `
        <div class="profile-picture">
            <img src="profile1.png" alt="pfp">
        </div>
        <div class="username">
            <p>Username Here</p>
        </div>
        <div class="date-posted">
            <p>${date}</p>
        </div>
        <h2>${formData.get('thread-title')}</h2>
        <div class="content-text">
            <p>${formData.get('thread-content')}</p>
        </div>`;

    function appendNewPost(imageUrl = '', threadId = '') {
        if (imageUrl) {
            postHTML += `
            <div class="content-image">
                <img src="${imageUrl}" alt="Post Image" style="max-width: 200px; max-height: 150px; cursor: pointer;">
            </div>`;
        }
        postHTML += `<button class="archive-button" data-thread-id="${threadId}">Archive</button>`;
        newPost.innerHTML = postHTML;
        document.querySelector('.forum-posts').prepend(newPost);

        if (imageUrl) {
            newPost.querySelector('.content-image img').addEventListener('click', function() {
                showModal(imageUrl);
            });
        }

        newPost.querySelector('.archive-button').addEventListener('click', function(event) {
            event.stopPropagation();
            const threadId = this.dataset.threadId;
            archivePost(threadId);
        });
    }

    if (formData.get('thread-image').size > 0) {
        const reader = new FileReader();
        reader.onload = function(event) {
            const imageUrl = event.target.result;
            appendNewPost(imageUrl, '');
        };
        reader.readAsDataURL(formData.get('thread-image'));
    } else {
        appendNewPost();
    }

    fetch('create_thread.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            console.log('Post successfully saved to server');
            document.querySelector('.forum-posts .posts .archive-button').dataset.threadId = data.thread_id;
            location.reload();
        } else {
            console.error('Error:', data.error);
        }
    })
    .catch(error => console.error('Error:', error));

    form.reset();
}

function sortPosts(order) {
    const postsContainer = document.querySelector('.forum-posts');
    const posts = Array.from(postsContainer.querySelectorAll('.posts'));

    posts.sort((a, b) => {
        const timestampA = parseInt(a.dataset.timestamp);
        const timestampB = parseInt(b.dataset.timestamp);

        return order === 'newest' ? timestampB - timestampA : timestampA - timestampB;
    });

    postsContainer.innerHTML = '';
    posts.forEach(post => postsContainer.appendChild(post));
}

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.archive-button').forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation();
            const threadId = this.dataset.threadId;
            archivePost(threadId);
        });
    });
});

function archivePost(threadId) {
    fetch('../forum/archive.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ thread_id: threadId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Post Archived Successfully!");
            location.reload();
        } else {
            location.reload();
        }
    });
}

function fetchThreads(search = '') {
    const order = new URLSearchParams(window.location.search).get('order') || 'newest';
    fetch(`forum.php?search=${encodeURIComponent(search)}&order=${order}`)
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newPosts = doc.querySelector('.forum-posts').innerHTML;
            document.querySelector('.forum-posts').innerHTML = newPosts;
            attachArchiveEventListeners();
        })
        .catch(error => console.error('Error:', error));
}

function attachArchiveEventListeners() {
    document.querySelectorAll('.archive-button').forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation();
            const threadId = this.dataset.threadId;
            archivePost(threadId);
        });
    });
}