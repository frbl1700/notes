/*
 *	DT173G - Projekt (Notes)
 * 	Fredrik Blank
 */

function loadNotes() {
    document.getElementById('notes-data').innerHTML = '';

    //Hämta anteckningar
    notesObj.manager.getNotes(notesObj.user, function(error, data) {
        if (!error && data && data.length) {
            for (var i = 0; i < data.length; i++) {
                var temp = document.querySelector('#note-template');
                var note = temp.cloneNode(true);
                var cont = note.querySelector('.note-content');
                var editor = note.querySelector('.note-editor');
                var textarea = document.createElement('textarea');

                //ID    
                var id = data[i].noteId;

                //Text
                var text = data[i].text;

                note.id = 'note-' + id;
                note.setAttribute('data-id', id);
                cont.id = 'cont-' + id;
                cont.innerHTML = data[i].text;

                textarea.value = text;
                textarea.id = 'editor-' + id;
                textarea.setAttribute('data-id', id);
                textarea.setAttribute('autofocus', null);

                //Hantera spara automatiskt när man skriver
                textarea.addEventListener('keyup', function(event) {
                    saveNote(this);
                });

                //Dölj editor när man hoppar ur
                textarea.addEventListener('blur', function(event) {
                    hideEditor(this);
                }); 
                
                //Visa editor när man klickar i en anteckning
                note.addEventListener('click', function(event) {
                    showEditor(this);
                });

                //Lägg till element
                editor.appendChild(textarea);
                document.getElementById('notes-data').appendChild(note);
            }

            initNotes();
        }
    });
}

function createNote() {
    //Lägg till ny anteckning
    notesObj.manager.createNote(notesObj.user, function(error, data) {
        //Ladda om
        loadNotes();
    });
}

function deleteNote(id) {
    //Ta bort anteckning
    notesObj.manager.deleteNote(notesObj.user, id, function(error, data) {
        //Ladda om
        loadNotes();
    });
}

function initNotes() {
    $('.note').draggable({
        revert: 'invalid',
        scroll: false 
    });
}

function saveNote(elem) {
    var id = elem.getAttribute('data-id');
    var content = document.getElementById('cont-' + id);
    var text = elem.value;

    if (notesObj.saveQueue && 
        notesObj.saveQueue != null) {
        clearTimeout(notesObj.saveQueue);
    }
    
    notesObj.saveQueue = setTimeout(function() {
        //Uppdatera
        notesObj.manager.updateNote(notesObj.user, id, text, function(error, data) {   });
    }, 300);

    //Uppdatera även texten
    content.innerHTML = text;
}

function hideEditor(elem) {
    var id = elem.getAttribute('data-id');
    var note = document.getElementById('note-' + id);
    var editor = note.querySelector('.note-editor');
    var content = document.getElementById('cont-' + id);

    editor.style.visibility = 'hidden';
    content.style.display = 'block';
}

function showEditor(elem) {
    var id = elem.getAttribute('data-id');
    var editor = elem.querySelector('.note-editor');
    var content = document.getElementById('cont-' + id);
    var textarea = document.getElementById('editor-' + id);

    editor.style.visibility = 'visible';
    content.style.display = 'none';
    textarea.focus();
}

(function() {
    //Hämta anteckningar
    loadNotes();

    //Hantera papperskorg
    $('#trash-bin').droppable({
        tolerance: 'touch',

        drop: function(event, ui) {
            var note = $(ui.draggable);
            var id = $(note).data('id');
            deleteNote(id);
        }
    });
}());