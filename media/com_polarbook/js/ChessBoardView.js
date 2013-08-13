
var classChessBoardView=new Array;
function getChessBoardView(id)
{
  var i;
  for (i=0;i<classChessBoardView.length;i++)
    if (classChessBoardView[i].id==id)
      return classChessBoardView[i];
  return null;
};

function ChessBoardView() {
	this.pieceChar='NBRQK';
	this.sizes = new Array(20, 25, 30, 35, 40, 50, 55);
	this.board = new ChessBoard;
	this.board.setFen("rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq -");
	// Default id for document element to display the chessboard
	this.id = "chessboard";
	 // Default piecesize
	this.size = 50;
	// Default size for the border arount the chessboard
	this.border = 20;
	// Set white at bottom
	this.inverted = false;
	// Directory for the pieces
	this.imagedir="images/";
	// Add a red border around the bord
	this.dangermode=false;
	this.light = "#FFE7AC";
	this.dark = "#AF7A53";
	this.background = "#8c4a1a";
	// Callback functions
	this.moveCallback = null; // Called before a move is made to confirm
	this.boardCallback = null; // Called when a new position is on the board
//	this.legal = true;
	this.disable = false;
	
	this.fromSquare=-1;
	this.toSquare=-1;

//	// Methods
	this.setFen = setFen;
	this.getFen = getFen;
	this.create = create;
	this.display = display;
	this.invert = invert;
	this.danger = danger;
	this.drawBoard = drawBoard;
	this.setPiece = setPiece;
	this.getPiece = getPiece;
	this.update = update;
	this.domove = domove;
	this.width = width;
	this.height = height;
	this.dropEvent = dropEvent;
	this.promotionDialog = promotionDialog;
	this.resize = resize;
	this.highlight = highlight;
	this.onClickSquare = onClickSquare;
	this.clickMove = clickMove;
	this.mark = mark;
	this.unmark=unmark;
	this.setDefaultSize=setDefaultSize;
	this.setPieceChar=setPieceChar;
	classChessBoardView.push(this);
	
	function danger(b)
	{
		this.dangermode=b;
		document.getElementById(this.id).style.backgroundColor=(b?'darkred':this.background);
	}
	
	function setFen(fen) {
		this.board.setFen(fen);
		this.update();
	}

	function getFen() {
		return this.board.getFen();
	}

	function create() {
		this.display();
	}

	function highlight(sq)
	{
		document.getElementById(this.id + '_' + sq).style.backgroundColor="#f00";
	}
	
	function mark(sq)
	{
		jQuery("#"+this.id + '_' + sq).addClass('marked');
	}
	
	function unmark(sq)
	{
		jQuery("#"+this.id + '_' + sq).removeClass('marked');
	}
	
	function display() {
		var sq;
		this.border = this.size / 2;
		this.drawBoard();
		for (sq = 0; sq < 64; sq++)
			this.setPiece(sq, this.board.getPiece(sq));
		if (this.board.toMove == 0)
			document.getElementById(this.id + '_white').style.display='block';
		else
			document.getElementById(this.id + '_black').style.display='block';
		document.getElementById(this.id).style.backgroundColor=(this.dangermode?'darkred':this.background);
	}

	function update() {
		var piece, sq;
		var changed=false;
		for (sq = 0; sq < 64; sq++) {
			piece = this.getPiece(sq);
			if (piece != this.board.getPiece(sq))
			{
				this.setPiece(sq, this.board.getPiece(sq));
				changed=true;
			}
		}
		if (this.board.toMove == 0) {
			if (document.getElementById(this.id + '_white').style.display != 'block')
				document.getElementById(this.id + '_white').style.display = 'block';
			if (document.getElementById(this.id + '_black').style.display != 'none')
				document.getElementById(this.id + '_black').style.display = 'none';
		} else {
			if (document.getElementById(this.id + '_white').style.display != 'none')
				document.getElementById(this.id + '_white').style.display = 'none';
			if (document.getElementById(this.id + '_black').style.display != 'block')
				document.getElementById(this.id + '_black').style.display = 'block';
		}
		if (changed && this.boardCallback)
			this.boardCallback(this.board.getFen());
			
	}

	function drawBoard() {
		var sq;
		var row, file;
		var x, y;
		var sqcolor;
		var win;
		var files = "abcdefgh";

		win = document.getElementById(this.id);

		if (!win)
			return;
		
		win.innerHTML='';
		win.style.position='relative';
		win.style.left='0px';
		win.style.top='0px';
		win.style.width=this.border * 2 + this.size * 8 + 'px';
		win.style.height=this.border * 2 + this.size * 8 + 'px';
		win.style.backgroundColor=this.background;
		win.style.border='2px solid #5e3212';

		// Add squares
		for (sq = 0; sq < 64; sq++) {
			file = sq & 7;
			row = sq >> 3;
			if (this.inverted) {
				x = (7 - file) * this.size;
				y = row * this.size;
			} else {
				x = file * this.size;
				y = (7 - row) * this.size;
			}
			sqcolor = (((row + file) % 2) == 0) ? this.dark : this.light;
			var square=document.createElement("div");
			square.id=this.id + "_" + sq;
			square.style.height=this.size+'px';
			square.style.width=this.size+'px';
			square.style.position='absolute';
			square.style.left=this.border+x+'px';
			square.style.top=this.border+y+'px';
			square.style.backgroundColor=sqcolor;
			square.boardid=this.id;
			square.sq=sq;
			win.appendChild(square);
			jQuery("#"+square.id).droppable({
				drop : this.dropEvent
			});
			jQuery("#"+square.id).click(this.onClickSquare);
		}

//		// Coordinates
		for (file = 0; file < 8; file++) {
			if (this.inverted)
				x = this.border + (7 - file) * this.size;
			else
				x = this.border + file * this.size;
			y = this.border + 8 * this.size;
			var coor=document.createElement("div");
			coor.style.height=this.border+'px';
			coor.style.width=this.size+'px';
			coor.style.position='absolute';
			coor.style.left=x+'px';
			coor.style.top=y+'px';
			coor.style.color='white';
			coor.style.fontSize=this.border*3/4+'px';
			coor.style.fontFamily='sans-serif';
			coor.style.fontWeight='bold';
			coor.style.textAlign='center';
			coor.innerHTML=files.charAt(file);
			win.appendChild(coor);
		}
		for (row = 1; row < 9; row++) {
			if (this.inverted)
				y = this.border + (row - 1) * this.size;
			else
				y = this.border + (8 - row) * this.size;
			y += this.size / 4; 
			var coor=document.createElement("div");
			coor.style.height=this.size+'px';
			coor.style.width=this.border+'px';
			coor.style.position='absolute';
			coor.style.left='0px';
			coor.style.top=y+'px';
			coor.style.color='white';
			coor.style.fontSize=this.border*3/4+'px';
			coor.style.fontFamily='sans-serif';
			coor.style.fontWeight='bold';
			coor.style.textAlign='center';
			coor.innerHTML=row;
			win.appendChild(coor);
		}
		
		// Who to move
		if (this.inverted) {
			var white=document.createElement("div");
			white.id=this.id+'_white';
			white.style.display='none';
			white.style.height=this.border - 8 + 'px';
			white.style.width=this.border - 8 + 'px';
			white.style.position='absolute';
			white.style.backgroundColor='white';
			white.style.border='1px solid black';
			white.style.left=this.border+this.size*8+4+'px';
			white.style.top=this.size+'px';
			win.appendChild(white);
			var black = white.cloneNode(true);
			black.id=this.id+'_black';
			black.style.backgroundColor='black';
			black.style.border='1px solid white';
			black.style.top=this.border+this.size*7+'px';
			win.appendChild(black);
		} else {
			var white=document.createElement("div");
			white.id=this.id+'_white';
			white.style.display='none';
			white.style.height=this.border - 8 + 'px';
			white.style.width=this.border - 8 + 'px';
			white.style.position='absolute';
			white.style.backgroundColor='white';
			white.style.border='1px solid black';
			white.style.left=this.border+this.size*8+4+'px';
			white.style.top=this.border+this.size*7+'px';
			win.appendChild(white);
			var black = white.cloneNode(true);
			black.id=this.id+'_black';
			black.style.backgroundColor='black';
			black.style.border='1px solid white';
			black.style.top=this.size+'px';
			win.appendChild(black);
		}
	}

	function setPiece(sq, piece) {
		var pieces = new Array("", "wp", "wn", "wb", "wr", "wq", "wk", "bp", "bn", "bb", "br", "bq", "bk");
		var elsq = document.getElementById(this.id+'_'+sq);
		if (!elsq)
			return;
		if (piece == 0) {
			elsq.innerHTML='';
		} else {
			elsq.innerHTML='';
			var img="<img";
			img += " id='"+this.id+"_Piece_"+sq+"'";
			img += " src='"+this.imagedir + pieces[piece] + this.size + ".gif'";
			img += " width='"+this.size+"'";
			img += " height='"+this.size+"'";
			img += " hspace='0' vspace='0'";
			img += ">";
			jQuery(img).data('piece',this.id+"_Piece_"+sq).data('board',this.id).appendTo("#"+this.id+"_"+sq).draggable({
				containment: '#'+this.id,
//			cursor : 'move',
				stack : '#'+this.id,
				revert : true
			});
//			jQuery("#"+this.id+"_Piece_"+sq).click(this.onClickSquare);
		}
	}

	function getPiece(sq) {
		var pieces = new Array("", "wp", "wn", "wb", "wr", "wq", "wk", "bp", "bn", "bb", "br", "bq", "bk");
		var elsq = document.getElementById(this.id + "_" + sq);
		if (!elsq)
			return 0;
		var i;
		if (elsq.hasChildNodes()){
			for (i = 1; i < 13; i++)
				if (elsq.firstChild.src.match(this.imagedir + pieces[i] + this.size + '.gif'))
					return i;
		}
		return 0;
	}

	function invert(b) {
		this.inverted = b;
		this.display();
	}

	function domove(move) {
		if (move >> 16) {
			var promid = document.getElementById('Promotion');
			this.disable = false;
			if (promid)
				promid.parentNode.removeChild(promid);
		}
//		if (!this.legal) {
//			this.board.setPiece(move & 255, 0);
//			this.board.setPiece((move >> 8) & 255, this.movePiece);
//			return;
//		}
		if (this.fromSquare>=0)
			this.unmark(this.fromSquare);
		if (this.toSquare>=0)
			this.unmark(this.toSquare);
		this.fromSquare=this.toSquare=-1;
		if (this.moveCallback)
			if (!this.moveCallback(move))
				return;
		if (!this.board.doMove(move, true))
			return;
		this.update();
	}

	function width() {
		return this.border * 2 + this.size * 8;
	}

	function height() {
		return this.border * 2 + this.size * 8;
	}

	function dropEvent(ev, ui){

		var pieceId=(ui.draggable.data('piece'));
		var boardId=(ui.draggable.data('board'));
		var board = getChessBoardView(boardId);
		var newSquare= parseInt(ev.target.id.substr(ev.target.id.lastIndexOf('_')+1));
		var oldSquare = parseInt(pieceId.substr(pieceId.lastIndexOf('_')+1));
		var piece = board.board.getPiece(oldSquare);
		var newRow=newSquare >> 3;

		if ((board.board.toMove==1) && ((piece<7) || (piece>12)))
			return;
		else if ((board.board.toMove==0) && ((piece<1) || (piece>6)))
			return;

		var move=(oldSquare+(newSquare<<8));
			
		// Promotion
		if (((piece==1) && (newRow==7)) || ((piece==7)&&(newRow==0)))
			move += (board.board.toMove?11:5)<<16; // Set it to queen for test, corrected in promotionDialog

		if (!board.board.isLegal(move))
			return;
		
		
		if (move>>16)
			board.promotionDialog(move,board.board.toMove);
		else
			board.domove(move);
	}
	
	function promotionDialog(move,color) {
		var x, y;
		
		var el = document.getElementById('Promotion');
		if (el)
			el.parentNode.removeChild(el);

		// Remove promotion in move
		move=move&0xffff;
		
		el = document.createElement('div');
		el.id = 'Promotion';
		el.style.position = 'absolute';
		el.style.display = 'none';
		el.style.border = '1px solid black';
		el.style.backgroundColor = '#ffffcc';
		el.style.cursor = 'default';
		document.getElementById(this.id).appendChild(el);
		x = this.border + (this.size * 2) - 20;
		y = this.border + (this.size * 4) - 5 - parseInt(this.size / 2);
		el.style.left = x + 'px';
		el.style.top = y + 'px';
		var knight = document.createElement('img');
		knight.setAttribute("onmouseover","this.style.backgroundColor='blue'");
		knight.setAttribute("onmouseout","this.style.backgroundColor='transparent'");
		knight.setAttribute("onclick","getChessBoardView('"+this.id+"').domove("+(move+(color?8<<16:2<<16)) +")");
		knight.src = this.imagedir + (color ? 'bn' : 'wn') + this.size + '.gif';
		knight.width = this.size;
		knight.height = this.size;
		knight.hspace = 5;
		knight.vspace = 5;
		var bishop = knight.cloneNode(true);
		bishop.setAttribute("onclick","getChessBoardView('"+this.id+"').domove("+(move+(color?9<<16:3<<16)) +")");
		bishop.src = this.imagedir + (color ? 'bb' : 'wb') + this.size + '.gif';
		var rook = knight.cloneNode(true);
		rook.setAttribute("onclick","getChessBoardView('"+this.id+"').domove("+(move+(color?10<<16:4<<16)) +")");
		rook.src = this.imagedir + (color ? 'br' : 'wr') + this.size + '.gif';
		var queen = knight.cloneNode(true);
		queen.setAttribute("onclick","getChessBoardView('"+this.id+"').domove("+(move+(color?11<<16:5<<16)) +")");
		queen.src = this.imagedir + (color ? 'bq' : 'wq') + this.size + '.gif';
		el.appendChild(knight);
		el.appendChild(bishop);
		el.appendChild(rook);
		el.appendChild(queen);
		el.style.display = 'block';
		this.disable = true;
	}
	
	function resize(c)
	{
		var n=this.sizes.indexOf(this.size);
		if (n<0){
			this.size=this.setDefaultSize();
		}else {
			if (c==1){
				if (n<(this.sizes.length-1))
						this.size=this.sizes[n+1];
			}else if (c==-1){
				if (n>0)
					this.size=this.sizes[n-1];
			}
		}
		this.display();
	}
	
	function onClickSquare(ev)
	{
		var square= parseInt(ev.target.id.substr(ev.target.id.lastIndexOf('_')+1));
		var boardid=ev.target.id.substr(0,ev.target.id.indexOf('_'));
		var board = getChessBoardView(boardid);
		if (!board)
			return;
		board.clickMove(square);
	}
	
	function clickMove(sq)
	{
		if ((this.fromSquare>=0)&&(sq==this.fromSquare)){
			this.fromSquare=-1;
			this.unmark(sq);
			return;
		}
		var piece;
		if (this.fromSquare<0){
			piece = this.board.getPiece(sq);
			if ((this.board.toMove==1) && ((piece<7) || (piece>12)))
				return;
			else if ((this.board.toMove==0) && ((piece<1) || (piece>6)))
				return;
			this.fromSquare=sq;
			this.mark(sq);
			return;
		}
		this.toSquare=sq;
		this.mark(this.toSquare);
		
		var row=sq >> 3;

		var move=(this.fromSquare+(this.toSquare<<8));
			
		piece = this.board.getPiece(this.fromSquare);

		// Promotion
		if (((piece==1) && (row==7)) || ((piece==7)&&(row==0)))
			move += (this.board.toMove?11:5)<<16; // Set it to queen for test, corrected in promotionDialog

		if (!this.board.isLegal(move)){
			this.unmark(this.fromSquare);
			this.unmark(this.toSquare);
			this.fromSquare=this.toSquare=-1;
			return;
		}
		
		if (move>>16)
			this.promotionDialog(move,this.board.toMove);
		else
			this.domove(move);
		// Remove markup
		if (this.fromSquare>=0)
			this.unmark(this.fromSquare);
		if (this.toSquare>=0)
			this.unmark(this.toSquare);
		this.fromSquare=this.toSquare=-1;
	}
	
	function setDefaultSize()
	{
		var w=jQuery(window).width();
		var h=jQuery(window).height();
		var s=(w>h?h:w)/8;
		var i=this.sizes.length-1;
		pbboard.size=this.sizes[0];
		while (i>0){
			if (this.sizes[i]<s){
				pbboard.size=this.sizes[i];
				return;
			}
			--i;
		}
	}
	
	function setPieceChar(s)
	{
		this.pieceChar=s;
		this.board.pieceChar=s;
	}
};
