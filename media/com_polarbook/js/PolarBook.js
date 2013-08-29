var pbboard = null;
var is_reading=0;
var is_writing=0;

// Strings used in this sript
var missing_name='You must give a name for your new book';

var movePath = new Array();

/**
 * Keep info about current book
 * .id						; Book id
 * .name					; Name of the book
 * .user					; Joomla user id of the bookowner
 * .trashed				; 1=Book are deleted
 * .public				; Readaccess of guests (0=no, 1=yes)
 * .member				; Access of members (0=none, 1=read, 2=read/write)
 * .readusers			; Array of inividual users with readaccess.
 * .writeusers		; Array of inividual users with writeaccess.
 * .created				; Created time
 * .comment				; Description of the book
 */
var currentBook = new Object();
/**
 * Keep the current position with board, moves comment etc.
 * A full object would look like:
 * .id        ; Database id for this position
 * .book_id   ; Database id for the book this position belong to.
 * .fen       ; Fen string for this position
 * .moves[]   ; Array of moves in this position [[(int)Move 1,(string)comment 1,(int)Repertoire 1,(int)statistics 1],[]]
 *            ; Format hven saved are string (move1|comment1|repertoire1|statistic1;move2|comment2|repertoire2|statistic2)
 *            ; | -> ! and ; -> ; when saved if used in movecomment
 * .comment   ; Comment
 * .error     ; If not 0 or not defined, Error number from the database search (Controller or Model) 
 * .
 */
var currentPosition = new Object();
/**
 * Keep user preference
 * .user // Joomla user id
 * .name // not saved
 */
var currentUser = new Object();
var writemode = false;

/**
 * Hold the state of practice mode.
 * .run   ;
 * .color ;
 * .start ;
 * .level ;
 * .random ;
 * .test ;
 * .score ;
 * .error ;
 */
var practice = new Object();

window.onload=function(){init();};
//window.addEvent('load', function() {init();});
	


function init()
{
	currentUser.user = userid;
	currentUser.name = username;

	jQuery('#username').text(currentUser.name);

	pbboard = new ChessBoardView();
	pbboard.setPieceChar(piecechar);
	pbboard.id = 'chessboard';
	pbboard.setDefaultSize();
	pbboard.imagedir = imagedir;
	pbboard.moveCallback = moveFromBoard;
	pbboard.boardCallback = positionFromBoard;

	pbboard.create();
	addMovePath(0);
	setMenu(0);
	writing(0);
	reading(0);
	if (moves != '')
		setMovePathList(moves);
	if (book)
		openBook(book);
	if (status != '')
		jQuery('#status').text(status);

};

function menuFileNew() {
	var s;
	var name = (jQuery('#filenewname').val());
	if (name == '') {
		jQuery("#status").text(missing_name);
		return;
	}
	writing(1);
	jQuery.ajax({
		cache : false,
		type : 'POST',
		dataType : 'json',
		url : responseUrl + 'task=response.createbook&format=json',
		data : 'name=' + encodeURIComponent(name),
		timeout : 90000,
		success : function(json) {
			writing(0);
			if (!json || (json.error && (json.error>0))) {
				jQuery("#status").text('Error: ' + (json ? json.error : 'Missing answer'));
			} else {
				pbboard.setFen('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -');
				currentBook = json;
				currentPosition=new Object;
				addMovePath(0);
				update();
				setWriteMode(true);
				setMenu(1);
			}
		}
	});
}

function menuFileOpen(func) {
	switch (func){
		case 0:
			jQuery('#fileopen').modal();
			reading(1);
			jQuery.ajax({
				cache : false,
				type : 'POST',
				dataType : 'json',
				url : responseUrl + 'task=response.getbooklist&format=json',
				timeout : 90000,
				success : function(json) {
					reading(0);
					if (!json || (json.error && (json.error>0))) {
						jQuery("#status").text('Error: ' + (json ? json.error : 'Missing answer'));
					} else {
						var s="<select class='form-control' id='fileopenname'>\n";
						for ( var i=0; i<json.length;i++) {
							if (json[i]['id']){
								s+="<option value='"+json[i]['id']+"'";
								if (json[i]['owner']==currentUser.user)
									s+=" class='polarbookowner'";
								else 
									s+=" class='"+(json[i]['access']==2?'polarbookwriter':'polarbookreader')+"'";
								s+=">";
								s+=json[i]['name'];
								s+="</option>\n";
							}
						}
						s+="</select>\n";
						jQuery('#fileopenselect').html(s);
					}
				}
			});
			break;
		case 1:
			var id=jQuery('#fileopenname option:selected').val();
			openBook(id);
			break;
	}
}

function openBook(id){
	reading(1);
	jQuery.ajax({
		cache : false,
		type : 'POST',
		dataType : 'json',
		url : responseUrl + 'task=response.openbook&format=json',
		data : 'id=' + id,
		timeout : 90000,
		success : function(json) {
			reading(0);
			if (!json || (json.error && (json.error>0))) {
				jQuery("#status").text('Error: ' + (json ? json.error : 'Missing answer'));
			} else {
				var r = json.readusers.split(';');
				var w = json.writeusers.split(';');
				json.readusers=r;
				json.writeusers=w;
				currentBook = json;
				currentPosition['fen'] = pbboard.getFen();
				getPositionFromServer();
				setWriteMode(false);
				setMenu(1);
			}
		}
	});
}

function menuFileClose() {
	currentBook = new Object();
	currentPosition = new Object();
	setWriteMode(false);
	update();
	setMenu(3);
}

function menuFileDelete(func) {
	switch (func){
		case 0:
			jQuery("#filedeletename").val(currentBook.name);
			jQuery('#filedelete').modal()
			break;
		case 1:
			writing(1);
			jQuery.ajax({
				cache : false,
				type : 'POST',
				dataType : 'json',
				url : responseUrl + 'task=response.updatebook&format=json',
				data : 'id=' + currentBook.id + '&trashed=1',
				timeout : 90000,
				success : function(json) {
					writing(0);
					if (!json || (json.error && (json.error>0))) {
						jQuery("#status").text('Error: ' + (json ? json.error : 'Missing answer'));
					} else {
						currentBook = new Object();
						currentPosition = new Object();
						pbboard.setFen('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -');
						update();
						setWriteMode(false);
						setMenu(3);
					}
				}
			});
			break;
	}
}

function menuFileTrash(func) {
	switch (func) {
		case 0 :
			jQuery('#filetrash').modal();
			reading(1);
			jQuery.ajax({
				cache : false,
				type : 'POST',
				dataType : 'json',
				url : responseUrl + 'task=response.gettrashlist&format=json',
				timeout : 90000,
				success : function(json) {
					reading(0);
					if (!json || (json.error && (json.error>0))) {
						jQuery("#status").text('Error: ' + (json ? json.error : 'Missing answer'));
					} else {
						var s="<select class='form-control' id='filetrashname'>\n";
						for ( var i=0; i<json.length;i++) {
							if (json[i]['id'])
								s+="<option value='"+json[i]['id']+"'>"+json[i]['name']+"</option>\n";
						}
						s+="</select>\n";
						jQuery('#filetrashselect').html(s);
					}
				}
			});
			break;
		case 1: 
			var id=jQuery('#filetrashname option:selected').val();
			writing(1);
			jQuery.ajax({
				cache : false,
				type : 'POST',
				dataType : 'json',
				url : responseUrl + 'task=response.trashbook&format=json',
				data : 'id=' + id,
				timeout : 90000,
				success : function(json) {
					writing(0);
					if (!json || (json.error && (json.error>0)))
						jQuery("#status").text('Error: ' + (json ? json.error : 'Missing answer'));
				},
			});
			break;
		case 2:
			var id=jQuery('#filetrashname option:selected').val();
			writing(1);
			jQuery.ajax({
				cache : false,
				type : 'POST',
				dataType : 'json',
				url : responseUrl + 'task=response.updatebook&format=json',
				data : 'id=' + id + '&trashed=0',
				timeout : 90000,
				success : function(json) {
					writing(0);
					if (!json || (json.error && (json.error>0)))
						jQuery("#status").text('Error: ' + (json ? json.error : 'Missing answer'));
				}
			});
			break;
	}
}

function menuBookExport(func)
{
	switch (func){
		case 0:	// Display dialog (not in use)
			break;
		case 1:
			window.open(responseUrl+'task=export.pgn&id='+currentBook.id);
			break;
		case 2:
			window.open(responseUrl+'task=export.epd&id='+currentBook.id);
			break;
		case 3:
			window.open(responseUrl+'task=export.backup&id='+currentBook.id);
			break;
	}
}
function menuBookImportBook(func)
{
	switch (func){
		case 0:	// Display dialog
//			<li><a id="menubookimport" href="index.php?option=com_polarbook&amp;view=import" class="modal" rel="{handler: 'iframe',size: {x:680,y:370}}"><?php echo JText::_('COM_POLARBOOK_MENU_BOOK_IMPORT'); ?></a></li>
//			window.open(responseUrl+'view=import','_self');
//			break;
			jQuery('#bookImportBook').modal();
			reading(1);
			jQuery.ajax({
				cache : false,
				type : 'POST',
				dataType : 'json',
				url : responseUrl + 'task=response.getbooklist&format=json',
				timeout : 90000,
				success : function(json) {
					reading(0);
					if (!json || (json.error && (json.error>0))) {
						jQuery("#status").text('Error: ' + (json ? json.error : 'Missing answer'));
					} else {
						var s="<select class='form-control' id='bookimportbookname' multiple>\n";
						for ( var i=0; i<json.length;i++) {
							if (json[i]['id'] && (json[i]['id']!=currentBook.id)){
								s+="<option value='"+json[i]['id']+"'";
								if (json[i]['owner']==currentUser.user)
									s+=" class='polarbookowner'";
								else 
									s+=" class='"+(json[i]['access']==2?'polarbookwriter':'polarbookreader')+"'";
								s+=">";
								s+=json[i]['name'];
								s+="</option>\n";
							}
						}
						s+="</select>\n";
						jQuery('#bookimportbookselect').html(s);
					}
				}
			});
			break;
		case 1: // Save (import book).
			var ids=[];
			var idst=[];
			jQuery("#bookimportbookname :selected").each(function(i,selected){
				ids[i]=jQuery(selected).val();
				idst[i]=jQuery(selected).text();
			});
			var i;
			for (i=0;i<ids.length;i++){
				jQuery("#status").text('Leser: ' + idst[i]);
				writing(1);
				jQuery.ajax({
					cache : false,
					async : false,
					type : 'POST',
					dataType : 'json',
					url : responseUrl + 'task=response.importbook&format=json',
					data : 'id=' + currentBook.id + '&import=' + ids[i],
					timeout : 90000,
					success : function(json) {
						writing(0);
						if (!json || (json.error && (json.error>0)))
							jQuery("#status").text('Error: ' + (json ? json.error : 'Missing answer'));
					}
				});
			}
			jQuery("#status").text('Import ferdig.');
			writing(0);
			currentPosition['fen'] = pbboard.getFen();
			getPositionFromServer();
			break;
	}
}

function menuBookImportFile()
{
	jQuery('#bookimportfilebook').val(currentBook.id);
	jQuery('#bookImportFile').modal();
}

function menuBookProperty(func) {
	switch (func){
	case 0:	// Display dialog	
		if (currentBook.access==2){
			jQuery("#bookpropertyaccess").show();
			
			jQuery("#bookpropertypublic").val(currentBook.public);
			jQuery("#bookpropertymember").val(currentBook.member);

		}else{
			jQuery("#bookpropertyaccess").hide();
			jQuery("#bookpropertystatistics").hide();
		}
		jQuery("#bookpropertysave").attr('disabled',(currentBook.access==1?true:false));
		jQuery("#bookpropertyname").attr('disabled',(currentBook.access==1?true:false));
		jQuery("#bookpropertycomment").attr('disabled',(currentBook.access==1?true:false));
		jQuery("#bookpropertyname").val(currentBook.name);
		jQuery("#bookpropertycomment").val(currentBook.comment);
		jQuery("#bookpropertycreated").text(SQLDateToString(currentBook.created));

		reading(1);
		jQuery.ajax({
			cache : false,
			type : 'POST',
			dataType : 'json',
			url : responseUrl + 'task=response.getuserlist&format=json',
			timeout : 90000,
			success : function(json) {
				reading(0);
				if (!json || (json.error && (json.error>0))) {
					jQuery("#status").text('Error: ' + (json ? json.error : 'Missing answer'));
				} else {
					var i;
					var s="<select class='form-control' id='bookpropertyuserread' multiple>\n";
					for ( i=0; i<json.length;i++) {
						if ((json[i]['id'])&&(json[i]['id']!=currentUser.user)&&(json[i]['id']!=currentBook.user)){
							s+="<option value='"+json[i]['id']+"'";
							if (currentBook.readusers.indexOf(json[i]['id'])>=0)
								s+= " selected";
							s+=">";
							s+=json[i]['name'];
							s+="</option>\n";
						}
					}
					s+="</select>\n";
					jQuery('#bookpropertyuserreadselect').html(s);
					var s="<select id='bookpropertyuserwrite' multiple>\n";
					for ( i=0; i<json.length;i++) {
						if ((json[i]['id'])&&(json[i]['id']!=currentUser.user)&&(json[i]['id']!=currentBook.user)){
							s+="<option value='"+json[i]['id']+"'";
							if (currentBook.writeusers.indexOf(json[i]['id'])>=0)
								s+= " selected";
							s+=">";
							s+=json[i]['name'];
							s+="</option>\n";
						}
					}
					s+="</select>\n";
					jQuery('#bookpropertyuserwriteselect').html(s);
					for ( i=0; i<json.length;i++) {
						if (json[i]['id']==currentBook.user){
							jQuery('#bookpropertyauthor').html(json[i]['name']);
							break;
						}
					}
					
					// Get number of positions
				  reading(1);
				  jQuery.ajax({
				  	cache : false,
				  	type : 'POST',
				  	dataType : 'json',
				  	url : responseUrl + 'task=response.countposition&format=json',
						data : 'id=' + currentBook.id,
				  	timeout : 90000,
				  	success : function(json) {
				  		reading(0);
				  		if (!json || (json.error && (json.error>0)))
				  			jQuery("#status").text('Error: ' + (json ? json.error : 'Missing answer'));
				  		else
				  			jQuery("#bookpropertypositions").text(json.position);
				  	}
				  });
				}
			}
 		});
		
		jQuery('#bookProperty').modal();
		break;
	case 1: // Change book info
		var changed=false;
		var n=jQuery('#bookpropertyname').val();
		var c=jQuery('#bookpropertycomment').val().replace(/[<>]/gi,' ');
		var p=parseInt(jQuery("#bookpropertypublic").val());
		var m=parseInt(jQuery("#bookpropertymember").val());
		var ru=jQuery("#bookpropertyuserread").val()||[];
		var wu=jQuery("#bookpropertyuserwrite").val()||[];
		var param='id='+currentBook.id;
		if (n!=currentBook.name){
			param+="&name="+encodeURIComponent(n);
			changed=true;
			currentBook.name=n;
		}
		if (c!=currentBook.comment){
			param+="&comment="+encodeURIComponent(c);
			changed=true;
			currentBook.comment=c;
		}
		if (p!=currentBook.public){
			param+="&public="+p;
			changed=true;
			currentBook.public=p;
		}
		if (m!=currentBook.member){
			param+="&member="+m;
			changed=true;
			currentBook.member=m;
		}
		if (ru!=currentBook.readusers){
			param+="&readusers="+encodeURIComponent(ru.join(";"));
			changed=true;
			currentBook.readusers=ru
		}
		if (wu!=currentBook.writeusers){
			param+="&writeusers="+encodeURIComponent(wu.join(";"));
			changed=true;
			currentBook.writeusers=wu
		}
		if (changed){
			writing(1);
			jQuery.ajax({
				cache : false,
				type : 'POST',
				dataType : 'json',
				url : responseUrl + 'task=response.updatebook&format=json',
				data : param,
				timeout : 90000,
				success : function(json) {
					writing(0);
					if (!json || (json.error && (json.error>0))) {
						jQuery("#status").text('Error: ' + (json ? json.error : 'Missing answer'));
					} else {
						currentBook=json;
						update();
					}
				}
			});
		}
		break;
	}
}

function menuBookWritemode() {
	if (currentBook.access==2)
		setWriteMode(writemode ? false : true);
}

function menuPractice(n) {
	switch (n){
		case 0: // Stop
			practice.run=0;
			jQuery("#movelist").slideDown();
			getPositionFromServer();
			break;
		case 1: // Start
			if (jQuery("#startpracticewhite:radio[name=practicecolor]:checked").val()==1){
				practice.color=0;
				pbboard.invert(false);
			}else if (jQuery("#startpracticeblack:radio[name=practicecolor]:checked").val()==1){
				practice.color=1;
				pbboard.invert(true);
			}else{
				break;
			}
			practice.level=parseInt(jQuery('#startpracticeselect option:selected').val());
	
			jQuery("#movelist").slideUp();
			setWriteMode(false);
			practice.run=1;
			practice.test=0;
			practice.start=movePath.length-1;
			practice.random=false;
		case 2: // Restart jumps in here
			if (pbboard.board.toMove!=practice.color)
				makePracticeMove();
			break;
		case 3: // Start på nytt meny
			if (currentPosition.comment)
				jQuery("#restartpracticecomment").val(currentPosition.comment);
			else
				jQuery("#restartpracticecomment").val('');
			jQuery('#restartpractice').modal();
			break;
		case 4: // Restart
			fromMovePath(practice.start);
			menuPractice(1);
			break;
			
	}
	setMenu(2);
}

function menuToolsCompress(){
	writing(1);
	jQuery.ajax({
		cache : false,
		type : 'POST',
		dataType : 'json',
		url : responseUrl + 'task=response.compress&format=json',
		data : 'id='+currentBook.id,
		timeout : 90000, // Give it a minute
		success : function(json) {
			writing(0);
			if (!json || (json.error && (json.error>0)))
				jQuery("#status").text('Error: ' + (json ? json.error : 'Missing answer'));
		}
	});
}

function menuToolsLink()
{
	jQuery('#booklink').val(responseUrl+'book='+currentBook.id);
	jQuery('#positionlink').val(responseUrl+'book='+currentBook.id+'&amp;moves=' + encodeURIComponent(getMovePathList()));
	jQuery('#toolsLink').modal();
}

function menuToolsRepertoire()
{
	if (currentBook.access<2)
		return;
	
	var s;
	s = 'white='+((jQuery("#toolsrepertoirewhite:radio[name=repertoirecolor]:checked").val()==1)?'1':'0');
	s+= '&black='+((jQuery("#toolsrepertoireblack:radio[name=repertoirecolor]:checked").val()==1)?'1':'0');
	s+= '&level='+jQuery('#toolsrepertoireselect option:selected').val();
	s+= '&id='+currentBook.id;
	writing(1);
	jQuery.ajax({
		cache : false,
		type : 'POST',
		dataType : 'json',
		url : responseUrl + 'task=response.createrepertoire&format=json',
		data : s,
		timeout : 90000, // Give it a minute
		success : function(json) {
			writing(0);
			if (!json || (json.error && (json.error>0))) 
				jQuery("#status").text('Error: ' + (json ? json.error : 'Missing answer'));
		}
	});
}

function menuToolsStatistics(){
	writing(1);
	jQuery.ajax({
		cache : false,
		type : 'POST',
		dataType : 'json',
		url : responseUrl + 'task=response.createstatistics&format=json',
		data : 'id='+currentBook.id,
		timeout : 90000, // Give it a minute
		success : function(json) {
			writing(0);
			if (!json || (json.error && (json.error>0)))
				jQuery("#status").text('Error: ' + (json ? json.error : 'Missing answer'));
		}
	});
}

function moveExist(move) {
	if (!currentPosition.moves){
		currentPosition.moves=new Array();
		return false;
	}
	
	for ( var i=0; i<currentPosition.moves.length;i++) {
		if (currentPosition.moves[i][0] == move)
			return true;
	}
	return false;
}

function moveFromBoard(move) {
	if (practice.run){
		if (pbboard.board.toMove!=practice.color)
			return true;
		if (!currentPosition.moves)
			return false;
//		if (!moveExist(move)){
//			return false;
//		}
		var correct=0;
		for ( var i=0; i<currentPosition.moves.length;i++) {
			if (currentPosition.moves[i][2]<=practice.level){
				correct=currentPosition.moves[i][0];
				if (correct == move){
					practice.score++;
					if (practice.test>1)
						pbboard.display();
					practice.test=0;
					addMovePath(move);
					return true;
				}
			}
		}
		practice.error++;
		practice.test++;
		if (correct && (practice.test>1))
			pbboard.highlight(correct & 0xff);
		if (correct && (practice.test>3))
			pbboard.highlight((correct >> 8) & 0xff);
		return false;
	}
	
	addMovePath(move);
	if (!currentBook.id || currentBook.id == 0)
		return true;

	if (!writemode)
		return true;

	if (moveExist(move))
		return true;

	if (!currentPosition.moves)
		currentPosition.moves=new Array();
	currentPosition.moves.push(new Array(move,'',0,1));
	
	var dbmoves='';
	for (var i=0;i<currentPosition.moves.length;i++){
		if (dbmoves.length)
			dbmoves+=';';
		dbmoves+=currentPosition.moves[i][0]+'|'+currentPosition.moves[i][1].replace(/[|;]/gi,'/!:')+'|'+currentPosition.moves[i][2]+'|'+currentPosition.moves[i][3];
	}

	if (!currentPosition.fen)
		currentPosition.fen = pbboard.getFen();

	if (!currentPosition.book_id || currentPosition.book_id == 0)
		currentPosition.book_id = currentBook.id;

	if (!currentPosition.id)
		currentPosition.id = 0;

	writing(1);
	jQuery.ajax({
		cache : false,
		type : 'POST',
		dataType : 'json',
		url : responseUrl + 'task=response.updateposition&format=json',
		data : 'id=' + currentPosition.id + '&book_id=' + currentPosition.book_id
				+ '&fen=' + encodeURIComponent(currentPosition.fen) + '&moves=' + encodeURIComponent(dbmoves),
		timeout : 90000,
		success : function(json) {
			writing(0);
			if (!json || (json.error && (json.error>0)))
				jQuery("#status").text('Error: ' + (json ? json.error : 'Missing answer'));
		}
	});

	return true;
}

function getPositionFromServer() {
	if (!currentBook.id) {
		update();
		return;
	}
	 // Wait for a write to be finish
	if (is_writing){
		setTimeout(function(){getPositionFromServer()},250);
		return;
	}
	currentPosition.id = 0;
	currentPosition.moves = new Array();
	currentPosition.comment = '';
	var request = 'book_id=' + currentBook.id;
	if (practice && practice.run && (pbboard.board.toMove!=practice.color))
		request += '&practice=1&level=' + practice.level; 
	if (currentPosition.fen)
		request += '&fen=' + encodeURIComponent(currentPosition.fen);
	reading(1);
	jQuery.ajax({
		cache : false,
		type : 'POST',
		dataType : 'json',
		url : responseUrl + 'task=response.getposition&format=json',
		data : request,
		timeout : 90000,
		success : function(json) {
			reading(0);
			if (!json || (json.error && (json.error>0))) {
				jQuery("#status").text('Error: ' + (json ? json.error : 'Missing answer'));
			} else {
				if ((!json.moves)||(json.moves=='')){
					json.moves=new Array();
				}else{
					var a = json.moves.split(';');
					json.moves=new Array();
					for ( var i=0; i< a.length;i++)
						json.moves.push(a[i].split('|'));
					json.moves.sort(function(a,b){return b[3]-a[3]});
				}
				currentPosition = json;
				
				// Training mode
				if (practice.run)
				{
					if (pbboard.board.toMove==practice.color){
						var p=false;
						for ( var i=0; i<currentPosition.moves.length;i++) {
							if (currentPosition.moves[i][2]<=practice.level){
									p=true;
									break;
							}
						}
						if (!p)
							menuPractice(3);
					}else{ // Make a move.
						if (!makePracticeMove())
							menuPractice(3);
					}
				}
			}
			update();
		}
	});
	update();
}

function positionFromBoard(fen) {
	currentPosition.fen = fen;
	getPositionFromServer(fen);
}

function update() {
	// Book info
	if (currentBook.name)
		jQuery("#bookname").text(currentBook.name);
	else
		jQuery("#bookname").text('');

	if (currentPosition.comment)
		jQuery("#comment").val(currentPosition.comment);
	else
		jQuery("#comment").val('');

	displayMovePath();
	displayMoveList();
}

function addMovePath(move) {
	var m = new Array();
	if (move == 0) {
		movePath = new Array();
		m['move'] = '';
		m['fen'] = pbboard.getFen();
	} else {
		var i = movePath.length;
		if ((i % 2) != 0)
			m['move'] = ' ' + parseInt(1 + i / 2) + '.';
		else
			m['move'] = ' ';
		m['move'] += pbboard.board.getMoveText(move);
		var cb = new ChessBoard();
		cb.copy(pbboard.board);
		cb.doMove(move);
		m['fen'] = cb.getFen();
	}
	movePath.push(m);
	displayMovePath();
}

function displayMovePath() {
	var i;
//	if (movePath.length == 0)
//		return;
	var s = "<a href='#' onclick='fromMovePath(0);return false;'><i class='icon-home'></i></a>";
	for (i = 1; i < movePath.length; i++) {
		s += " <a class='move' href='#' onclick='fromMovePath(" + i + ");return false;'>"
				+ movePath[i].move + "</a>";
	}
	document.getElementById('movepath').innerHTML = s;
}

function fromMovePath(n) {
	if (n >= movePath.length)
		return;
	movePath = movePath.slice(0, n + 1);
	pbboard.setFen(movePath[n]['fen']);
}

function getMovePathList()
{
	var ml='';
	var i;
	var m,im;
	if (!movePath || (movePath.length<1))
		return '';
	var cb = new ChessBoard();
	cb.pieceChar=pbboard.pieceChar;
	cb.setFen(movePath[0]['fen']);
	for (i=1; i< movePath.length; i++){
		if (i > 1)
			ml += ' ';
		m=movePath[i]['move'];
		im=cb.makeMove(m);
		ml += cb.stripMovetext(m);
		cb.doMove(im);
	}
	return ml;
}

function setMovePathList(s)
{
	var m=s.split(' ');
	pbboard.setFen('rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -');
	addMovePath(0);
	var i;
	for (i=0;i<m.length;i++){
		addMovePath(pbboard.board.makeMove(m[i]));
		pbboard.setFen(movePath[i+1]['fen'])
	}
}

function displayMoveList() {
	// Finn trekknummer
	var i = movePath.length;
	var mnr='';
	if ((i % 2) == 0)
		mnr = parseInt(i / 2) + '...';
	else
		mnr = parseInt(1 + i / 2) + '.';
	var s = "<table class='table table-condensed'>\n<thead>\n<tr>\n"
			+ "<th>Trekk</th><th>#</th><th></th><th></th></tr></thead><tbody>";
	if (currentPosition.moves) {
		for ( var i = 0; i < currentPosition.moves.length; i++) {
			var moveclass='level_' + currentPosition.moves[i][2] + '_move';
			s += "<tr>" + "<td><a href='#' class='" + moveclass + "' onClick='fromMoveList(" + currentPosition.moves[i][0] 
					+ ");return false;'>" + mnr + pbboard.board.getMoveText(currentPosition.moves[i][0]) + currentPosition.moves[i][1]
					+ "</a></td>" + "<td>" + currentPosition.moves[i][3] + "</td>"
					+ "<td>" + (writemode?"<a href='#' onclick='editMove(" + i + ");return false;'>E</a></td>"
					+	"<td><a href='#' onclick='deleteMove(" + i + ");return false;'>S</a>":"") + "</td>"
					+ "</tr>";
		}
	}
	s += "</tbody>" + "</table>";
	document.getElementById('movelist').innerHTML = s;
};
function fromMoveList(move) {
	addMovePath(move);
	pbboard.board.doMove(move);
	pbboard.update();
}

function setWriteMode(b)
{
	writemode=b;
	pbboard.danger(b);
	if (b){
		jQuery("#comment").removeAttr('readonly');
	}else{
		jQuery("#comment").attr('readonly','readonly');
	}
	displayMoveList();
}

function textCommentChanged()
{
	if (!currentBook.id || currentBook.id == 0)
		return;
	if (!writemode)
		return;
	
	var newText=jQuery("#comment").val().replace(/<>/gi,' ');
	newText.replace();
	if (newText!=currentPosition.comment)
	{
		currentPosition.comment=newText;
		
		if (!currentPosition.fen)
			currentPosition.fen = pbboard.getFen();

		if (!currentPosition.book_id || currentPosition.book_id == 0)
			currentPosition.book_id = currentBook.id;

		if (!currentPosition.id)
			currentPosition.id = 0;
		
		update();

		writing(1);
		jQuery.ajax({
			cache : false,
			type : 'POST',
			dataType : 'json',
			url : responseUrl + 'task=response.updateposition&format=json',
			data : 'id=' + currentPosition.id + '&book_id=' + currentPosition.book_id
					+ '&comment=' + encodeURIComponent(currentPosition.comment) + '&fen=' + encodeURIComponent(currentPosition.fen),
			timeout : 90000,
			success : function(json) {
				writing(0);
				if (!json || (json.error && (json.error>0)))
					jQuery("#status").text('Error: ' + (json ? json.error : 'Missing answer'));
			}
		});
	}
}

function editMove(index)
{
	if (currentBook.access<2)
		return;
	if (index<0){
		var changed=false;
		var i=jQuery('#moveindex').val();
		var m=jQuery('#movemove').val();
		var c=jQuery('#movecomment').val();
		var r=parseInt(jQuery('#moverepertoire').val());
		var s=parseInt(jQuery('#movestatistics').val());
		if (!r)
			r=0;
		if (!s)
			s=0;
		// Validation
		if ((i<0) || (i>=currentPosition.moves.length))
			return;
		if (m!=currentPosition.moves[i][0])
			return;
		if (c.length>16)
			return;
		if (c!=currentPosition.moves[i][1]){
			currentPosition.moves[i][1]=c;
			changed=true;
		}
		if (r!=currentPosition.moves[i][2]){
			currentPosition.moves[i][2]=r;
			changed=true;
		}
		if (s!=currentPosition.moves[i][3]){
			currentPosition.moves[i][3]=s;
			changed=true;
		}
		if (changed){
			var dbmoves='';
			for (var i=0;i<currentPosition.moves.length;i++){
				if (dbmoves.length)
					dbmoves+=';';
				// Don't allow '|' and ';' in the textcomment since these characters are used as field separator.
				dbmoves+=currentPosition.moves[i][0]+'|'+currentPosition.moves[i][1].replace(/[|;]/gi,'')+'|'+currentPosition.moves[i][2]+'|'+currentPosition.moves[i][3];
			}

			if (!currentPosition.fen)
				currentPosition.fen = pbboard.getFen();

			if (!currentPosition.book_id || currentPosition.book_id == 0)
				currentPosition.book_id = currentBook.id;

			if (!currentPosition.id)
				currentPosition.id = 0;

			writing(1);
			jQuery.ajax({
				cache : false,
				type : 'POST',
				dataType : 'json',
				url : responseUrl + 'task=response.updateposition&format=json',
				data : 'id=' + currentPosition.id + '&book_id=' + currentPosition.book_id
						+ '&fen=' + encodeURIComponent(currentPosition.fen) + '&moves=' + encodeURIComponent(dbmoves),
				timeout : 90000,
				success : function(json) {
					writing(0);
					if (!json || (json.error && (json.error>0)))
						jQuery("#status").text('Error: ' + (json ? json.error : 'Missing answer'));
				}
			});
			update();
		}
	}else{
		if (index>=currentPosition.moves.length)
			return;
		jQuery('#moveindex').val(index);
		jQuery('#movemove').val(currentPosition.moves[index][0]);
		jQuery('#movecomment').val(currentPosition.moves[index][1]);
		jQuery('#moverepertoire').val(currentPosition.moves[index][2]);
		jQuery('#movestatistics').val(currentPosition.moves[index][3]);
		jQuery('#editmove').modal();
	}
}

function deleteMove(index)
{
	if ((index<0) || (index>=currentPosition.moves.length))
		return;
	currentPosition.moves.splice(index,1);

	var dbmoves='';
	for (var i=0;i<currentPosition.moves.length;i++){
		if (dbmoves.length)
			dbmoves+=';';
		dbmoves+=currentPosition.moves[i][0]+'|'+currentPosition.moves[i][1].replace(/[|;]/gi,'/!:')+'|'+currentPosition.moves[i][2]+'|'+currentPosition.moves[i][3];
	}

	if (!currentPosition.fen)
		currentPosition.fen = pbboard.getFen();

	if (!currentPosition.book_id || currentPosition.book_id == 0)
		currentPosition.book_id = currentBook.id;

	if (!currentPosition.id)
		currentPosition.id = 0;

	writing(1);
	jQuery.ajax({
		cache : false,
		type : 'POST',
		dataType : 'json',
		url : responseUrl + 'task=response.updateposition&format=json',
		data : 'id=' + currentPosition.id + '&book_id=' + currentPosition.book_id
				+ '&fen=' + encodeURIComponent(currentPosition.fen) + '&moves=' + encodeURIComponent(dbmoves),
		timeout : 90000,
		success : function(json) {
			writing(0);
			if (!json || (json.error && (json.error>0)))
				jQuery("#status").text('Error: ' + (json ? json.error : 'Missing answer'));
		}
	});
	
	update();
}

function setMenu(func)
{
	switch (func){
	case 0 : // On init
		if (currentUser.user==0){
			jQuery("#menufilenew").addClass('disabled');
			jQuery("#menufiletrash").addClass('disabled');
		}
		jQuery("#menufiledelete").addClass('disabled');
		jQuery("#menufileclose").addClass('disabled');
		jQuery("#menubookwritemode").addClass('disabled');
		jQuery("#menubookexport").addClass('disabled');
		jQuery("#menubookimportfile").addClass('disabled');
		jQuery("#menubookimportbook").addClass('disabled');
		jQuery("#menubookproperty").addClass('disabled');
		jQuery("#menupracticestart").addClass('disabled');
		jQuery("#menupracticestop").addClass('disabled');
		jQuery("#menutoolsrepertoire").addClass('disabled');
		jQuery("#menutoolsstatistics").addClass('disabled');
		jQuery("#menutoolscompress").addClass('disabled');
		jQuery("#menutoolslink").addClass('disabled');
		break;
	case 1 : // After open/or new book
		if (currentBook.access==2){
			jQuery("#menufiledelete").removeClass('disabled');
			jQuery("#menubookwritemode").removeClass('disabled');
			jQuery("#menubookimportfile").removeClass('disabled');
			jQuery("#menubookimportbook").removeClass('disabled');
			jQuery("#menutoolsrepertoire").removeClass('disabled');
			jQuery("#menutoolsstatistics").removeClass('disabled');
			jQuery("#menutoolscompress").removeClass('disabled');
		}else{
			jQuery("#menufiledelete").addClass('disabled');
			jQuery("#menubookwritemode").addClass('disabled');
			jQuery("#menubookimportfile").addClass('disabled');
			jQuery("#menubookimportbook").addClass('disabled');
			jQuery("#menutoolsstatistics").addClass('disabled');
			jQuery("#menutoolsrepertoire").addClass('disabled');
			jQuery("#menutoolscompress").addClass('disabled');
		}
		jQuery("#menubookexport").removeClass('disabled');
		jQuery("#menufileclose").removeClass('disabled');
		jQuery("#menubookproperty").removeClass('disabled');
		jQuery("#menupracticestart").removeClass('disabled');
		jQuery("#menutoolslink").removeClass('disabled');
		jQuery("#menupracticestop").addClass('disabled');
		break;
	case 2 : // Practice
		if (practice.run){
			jQuery("#menupracticestart").addClass('disabled');
			jQuery("#menupracticestop").removeClass('disabled');
			jQuery("#menufilenew").addClass('disabled');
			jQuery("#menufileopen").addClass('disabled');
			jQuery("#menufiledelete").addClass('disabled');
			jQuery("#menufileclose").addClass('disabled');
			jQuery("#menubookwritemode").addClass('disabled');
			jQuery("#menubookexport").addClass('disabled');
			jQuery("#menubookimportfile").addClass('disabled');
			jQuery("#menubookimportbook").addClass('disabled');
			jQuery("#menubookproperty").addClass('disabled');
			jQuery("#menutoolsrepertoire").addClass('disabled');
			jQuery("#menutoolsstatistics").addClass('disabled');
			jQuery("#menutoolscompress").addClass('disabled');
		}else{
			if (currentUser.user!=0){
				jQuery("#menufilenew").removeClass('disabled');
			}
			jQuery("#menufileopen").removeClass('disabled');
			setMenu(1); 
		}
		break;
	case 3: // After close or delete book
		jQuery("#menufiledelete").addClass('disabled');
		jQuery("#menufileclose").addClass('disabled');
		jQuery("#menubookwritemode").addClass('disabled');
		jQuery("#menubookexport").addClass('disabled');
		jQuery("#menubookimportfile").addClass('disabled');
		jQuery("#menubookimportbook").addClass('disabled');
		jQuery("#menubookproperty").addClass('disabled');
		jQuery("#menupracticestart").addClass('disabled');
		jQuery("#menupracticestop").addClass('disabled');
		jQuery("#menutoolsrepertoire").addClass('disabled');
		jQuery("#menutoolsstatistics").addClass('disabled');
		jQuery("#menutoolscompress").addClass('disabled');
		jQuery("#menutoolslink").addClass('disabled');
		break;
	}
	// not finish
//	jQuery("#menubookexport").addClass('disabled');
}

function makePracticeMove()
{
	var moves=new Array();
	for ( var i=0; i<currentPosition.moves.length;i++) {
		if (currentPosition.moves[i][3]>0)
			moves.push(new Array(currentPosition.moves[i][0],currentPosition.moves[i][3]));
	}
	if (moves.length){
		if (practice.random){
			for (var i=0 ; i<moves.length; i++)
				moves[i][1]=(100/moves.length);
		}else{
			var sum=0;
			for (var i=0 ; i<moves.length; i++)
				sum+=parseInt(moves[i][1]);
			for (var i=0 ; i<moves.length; i++)
				moves[i][1]*=(100/sum);
		}
		var n=0;
		for (var i=0 ; i<moves.length; i++){
			moves[i][1]+=n;
			n=moves[i][1];
		}
		moves[moves.length-1][1]=101;
		var r=Math.random()*100;
		var move=0;
		for (var i=0 ; i<moves.length; i++){
			if (moves[i][1]>r){
				move=moves[i];
				break;
			}
		}
		if (!move)
			return false;
		if (!pbboard.board.isLegal(move[0]))
			return false;
		addMovePath(move[0]);
		pbboard.board.doMove(move[0]);
		pbboard.update();
		return true;
	}
	return false;
}

function SQLDateToString(sqldate)
{
	//            0123456789012345678
	// SQL format YYYY-MM-DD hh:mm:ss
	var s=
		sqldate.substr( 8,2) + "." +
		sqldate.substr( 5,2) + "." +
		sqldate.substr( 0,4) + " " +
		sqldate.substr(11,2) + ":" +
		sqldate.substr(14,2) + ":" +
		sqldate.substr(17,2);
	return s;
}

function writing(b)
{
	is_writing=b;
	document.getElementById("write").style.backgroundColor=(b?'red':'green');
}

function reading(b)
{
	is_writing=b;
	document.getElementById("read").style.backgroundColor=(b?'red':'green');
}
