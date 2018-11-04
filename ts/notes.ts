/*
 *	DT173G - Projekt (Notes)
 * 	Fredrik Blank
 */

interface Note {
	 text : string;
	 noteId: number;
	 userId: number;
}

class NotesManager {
	private user : number;

	//URL för att hämt data
	private url = 'api.php';

	constructor(user: number) {
		this.user = user;
	}

	//Hämta alla anteckningar
	public getNotes(user: number, completion: (error: boolean, data: Note[]) => void) {
		this.ajaxRequest('GET', this.url + '/notes/' + user, null, (error, data) => {
			if (!error && data) {
				let notes : Note[] = [];
				var obj = JSON.parse(data);

				for (var i = 0; i < obj.length; i++) {
					let note : Note = { 
						text: obj[i].text,
						noteId: obj[i].note_id,
						userId: obj[i].user_id 
					};
					notes.push(note);
				}
			
				completion(false, notes);
			} else {
				completion(true, null);
			}
		});
	}

	//Lägg till ny anteckning
	public createNote(user: number, completion: (error: boolean, data: any) => void) {
		this.ajaxRequest('POST', this.url + '/notes/' + user, null, (error, data) => {
			if (!error) {
				completion(false, data);
			}
		});
	}

	//Uppdatera anteckning
	public updateNote(user: number, note: number, text: string, completion: (error: boolean, data: any) => void) {
		let obj = { 'text' : text } ;
		let data = JSON.stringify(obj);

		this.ajaxRequest('PUT', this.url + '/notes/' + user + '/' + note, data, (error, data) => {
			if (!error) {
				completion(false, data);
			}
		});
	}

	//Ta bort anteckning
	public deleteNote(user: number, note: number, completion: (error: boolean, data: any) => void) {
		this.ajaxRequest('DELETE', this.url + '/notes/' + user + '/' + note, null, (error, data) => {
			if (!error) {
				completion(false, data);
			}
		});
	}

	//Tar hand om ajax-anrop
	private ajaxRequest(method: string, url : string, data: any, completion: (error: boolean, data: any) => void) {
		let request = new XMLHttpRequest();

		//Förhindra cachning
		url += ('?d=' + new Date().getTime());

		request.open(method, url, true /* async */);
		request.setRequestHeader('Content-type','application/json; charset=utf-8');
		request.setRequestHeader('Cache-Control', 'no-cache');

		request.onreadystatechange = function() {
			if (this.readyState == 4) {
				if (this.status == 200) {
					completion(false, this.responseText);
				} else {
					completion(true, null);
				}
			}
		}
		
		request.send(data);
	}
}