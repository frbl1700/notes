/*
 *	DT173G - Projekt (Notes)
 * 	Fredrik Blank
 */

interface Note {
 	text : string;
}

class NotesManager {
	private user : number;

	//URL för att hämt data
	private url = 'api.php';

	constructor(user: number) {
		this.user = user;
	}

	//Hämta alla anteckningar
	public getNotes(completion: (error: boolean, data: Note[]) => void) {
		this.ajaxRequest(this.url + '/get/', (error, data) => {
			if (!error) {
				let notes : Note[] = [];
				var obj = JSON.parse(data);
				if (obj.success && obj.list) {
					for (var i = 0; i < obj.list.length; i++) {
						let note : Note = { text: obj.list[i].text };
						notes.push(note);
					}
				}
				completion(false, notes);
			} else {
				completion(true, null);
			}
		});
	}


	//Ajax-funktion
	private ajaxRequest(url : string, completion: (error: boolean, data: any) => void) {
		let request = new XMLHttpRequest();
		request.onreadystatechange = function() {
			if (this.readyState == 4) {
				if (this.status == 200) {
					completion(false, this.responseText);
				} else {
					completion(true, null);
				}
			}
		}

		request.open("GET", url, true /* async */);
		request.send();
	}
}