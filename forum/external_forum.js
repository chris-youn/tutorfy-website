document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.archive-reply-button').forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation(); // Prevent event from bubbling up to other elements
            const replyId = this.dataset.replyId;
            archiveReply(replyId);
        });
    });
});

function archiveReply(replyId) {
    fetch('archive_reply.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ reply_id: replyId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Failed to archive reply: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}
