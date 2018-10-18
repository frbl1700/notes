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
    NotesManager.prototype.getNotes = function (completion) {
        this.ajaxRequest(this.url + '/get/', function (error, data) {
            if (!error) {
                var notes = [];
                var obj = JSON.parse(data);
                if (obj.success && obj.list) {
                    for (var i = 0; i < obj.list.length; i++) {
                        var note = { text: obj.list[i].text };
                        notes.push(note);
                    }
                }
                completion(false, notes);
            }
            else {
                completion(true, null);
            }
        });
    };
    //Ajax-funktion
    NotesManager.prototype.ajaxRequest = function (url, completion) {
        var request = new XMLHttpRequest();
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
        request.open("GET", url, true /* async */);
        request.send();
    };
    return NotesManager;
}());
