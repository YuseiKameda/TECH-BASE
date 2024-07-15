document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', event => {
            event.preventDefault(); // リンクのデフォルト動作を防止
            const postId = button.dataset.postId;

        });
    });
});
