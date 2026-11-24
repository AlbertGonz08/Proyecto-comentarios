const commentStorageKey = 'clean-blog-comments';
const commentForm = document.getElementById('comment-form');
const nameField = commentForm.elements.namedItem('name');
const messageField = commentForm.elements.namedItem('message');
const commentList = document.getElementById('comment-list');
const commentCount = document.getElementById('comment-count');
const emptyMessage = document.getElementById('comment-empty');
const commentScroll = document.getElementById('comment-scroll');
const storageError = document.getElementById('comment-error');
const dateFormat = new Intl.DateTimeFormat('es-CL', { dateStyle: 'medium', timeStyle: 'short' });

function readComments() {
    try {
        const stored = JSON.parse(localStorage.getItem(commentStorageKey));
        return Array.isArray(stored) ? stored.filter((comment) =>
            typeof comment?.name === 'string' &&
            typeof comment.message === 'string' &&
            typeof comment.date === 'string' &&
            Number.isFinite(Date.parse(comment.date))
        ) : [];
    } catch {
        return [];
    }
}

function createComment({ name, message, date }) {
    const item = document.createElement('li');
    const avatar = document.createElement('span');
    const content = document.createElement('div');
    const author = document.createElement('strong');
    const timestamp = document.createElement('time');
    const body = document.createElement('p');

    item.className = 'comment-entry d-flex align-items-start gap-2';
    avatar.className = 'comment-avatar rounded-circle bg-light small';
    avatar.setAttribute('aria-hidden', 'true');
    avatar.textContent = name.split(/\s+/u).slice(0, 2).map((word) => Array.from(word)[0]).join('').toLocaleUpperCase('es-CL');
    author.textContent = name;
    timestamp.className = 'd-block small text-muted';
    timestamp.dateTime = date;
    timestamp.textContent = dateFormat.format(new Date(date));
    body.className = 'comment-message mt-1 mb-0';
    body.textContent = message;

    content.append(author, timestamp, body);
    item.append(avatar, content);
    return item;
}

function renderComments() {
    commentList.replaceChildren(...comments.map(createComment));
    commentList.hidden = comments.length === 0;
    emptyMessage.hidden = comments.length > 0;
    commentCount.textContent = comments.length;
}

function validateField(field, message) {
    field.setCustomValidity(field.value && !field.value.trim() ? message : '');
}

nameField.addEventListener('input', () => validateField(nameField, 'Escribe tu nombre.'));
messageField.addEventListener('input', () => validateField(messageField, 'Escribe un comentario.'));

let comments = readComments();
renderComments();

commentForm.addEventListener('submit', (event) => {
    event.preventDefault();
    validateField(nameField, 'Escribe tu nombre.');
    validateField(messageField, 'Escribe un comentario.');
    if (!commentForm.reportValidity()) {
        return;
    }

    const comment = {
        name: nameField.value.trim(),
        message: messageField.value.trim(),
        date: new Date().toISOString()
    };
    const updatedComments = [comment, ...readComments()];

    try {
        localStorage.setItem(commentStorageKey, JSON.stringify(updatedComments));
    } catch {
        storageError.textContent = 'No se pudo guardar el comentario en este navegador.';
        storageError.hidden = false;
        return;
    }

    storageError.hidden = true;
    comments = updatedComments;
    renderComments();
    commentForm.reset();
    commentScroll.scrollTop = 0;
});
