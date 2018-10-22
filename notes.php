<?php 
session_start();
if (empty($_SESSION['user'])) {
    header('location: index.php');
}
?>
<?php require_once('include/header.php'); ?>

<div>
    <div class="container">
        <nav>
            <div class="navigation">
                <div>
                    <div class="logo-container">
                        <img src="img/logo.png" alt="Notes logo" class="logo" />
                    </div>
                </div>

                <div class="logout-container">
                    <i class="fa fa-user"></i>
                    <a href="logout.php">Logga ut</a>
                </div>
            </div>
        </nav>
    </div>

    <div class="container">
        <div class="widget-area">
            <div id="notes-data"></div>
        </div>
    </div>
</div>

<div class="pane">
    <div class="pane-content">
        <div>
            <span>&nbsp;</span>
        </div>

        <div>
            <a href="javascript:;" onclick="createNote();">
                <i class="fa fa-plus-circle"></i>
            </a>
        </div>

        <div>
            <div id="trash-bin">
                <span class="fa fa-trash"></span>
            </div>
        </div>
    </div>
</div>

<div style="display:none;">
    <div class="note" id="note-template">
        <div class="note-content"></div>
        <div class="note-editor"></div>
    </div>
</div>

<script>    
    var notesObj = {
        user: <?php echo $_SESSION['user']; ?>,
        ver: '1.0',

        //Initera manager
        manager: new NotesManager(this.user),

        //Lite kontroll över när anteckningar ska sparas/uppdateras
        saveQueue: null
    };

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
        content.style.visibility = 'visible';
    }

    function showEditor(elem) {
        var id = elem.getAttribute('data-id');
        var editor = elem.querySelector('.note-editor');
        var content = document.getElementById('cont-' + id);
        var textarea = document.getElementById('editor-' + id);

        editor.style.visibility = 'visible';
        content.style.visibility = 'hidden';

        textarea.focus();
        elem.preventDefault();
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
</script>
<?php require_once('include/footer.php'); ?>