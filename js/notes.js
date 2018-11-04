/*
 *	DT173G - Projekt (Notes)
 * 	Fredrik Blank
 */
var NotesManager = /** @class */ (function () {
    function NotesManager(user) {
        //URL för att hämt data
        this.url = 'api.php';
        this.user = user;
    }
    //Hämta alla anteckningar
    NotesManager.prototype.getNotes = function (user, completion) {
        this.ajaxRequest('GET', this.url + '/notes/' + user, null, function (error, data) {
            if (!error && data) {
                var notes = [];
                var obj = JSON.parse(data);
                for (var i = 0; i < obj.length; i++) {
                    var note = {
                        text: obj[i].text,
                        noteId: obj[i].note_id,
                        userId: obj[i].user_id
                    };
                    notes.push(note);
                }
                completion(false, notes);
            }
            else {
                completion(true, null);
            }
        });
    };
    //Lägg till ny anteckning
    NotesManager.prototype.createNote = function (user, completion) {
        this.ajaxRequest('POST', this.url + '/notes/' + user, null, function (error, data) {
            if (!error) {
                completion(false, data);
            }
        });
    };
    //Uppdatera anteckning
    NotesManager.prototype.updateNote = function (user, note, text, completion) {
        var obj = { 'text': text };
        var data = JSON.stringify(obj);
        this.ajaxRequest('PUT', this.url + '/notes/' + user + '/' + note, data, function (error, data) {
            if (!error) {
                completion(false, data);
            }
        });
    };
    //Ta bort anteckning
    NotesManager.prototype.deleteNote = function (user, note, completion) {
        this.ajaxRequest('DELETE', this.url + '/notes/' + user + '/' + note, null, function (error, data) {
            if (!error) {
                completion(false, data);
            }
        });
    };
    //Tar hand om ajax-anrop
    NotesManager.prototype.ajaxRequest = function (method, url, data, completion) {
        var request = new XMLHttpRequest();
        //Förhindra cachning
        url += ('?d=' + new Date().getTime());
        request.open(method, url, true /* async */);
        request.setRequestHeader('Content-type', 'application/json; charset=utf-8');
        request.setRequestHeader('Cache-Control', 'no-cache');
        request.onreadystatechange = function () {
            if (this.readyState == 4) {
                if (this.status == 200) {
                    completion(false, this.responseText);
                }
                else {
                    completion(true, null);
                }
            }
        };
        request.send(data);
    };
    return NotesManager;
}());
