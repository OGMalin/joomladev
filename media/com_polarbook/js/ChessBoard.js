//function encodeChessMove(from,to,promote){return from+to<<8+promote<<16;};
//function decodeChessMove(from,to,promote){return from+to<<8+promote<<16;};
var knightPath = new Array(-2, 1, -1, 2, 1, 2, 2, 1, 2, -1, 1, -2, -1, -2, -2, -1);
var kingPath = new Array(-1, 0, -1, 1, 0, 1, 1, 1, 1, 0, 1, -1, 0, -1, -1, -1);
var bishopPath = new Array(-1, 1, 1, 1, 1, -1, -1, -1);
var rookPath = new Array(-1, 0, 0, 1, 1, 0, 0, -1);

function ChessBoard() {
	// The board in memory
	// Pieces: wp=1, wn=2, wb=3, wr=4, wq=5, wk=6,
	// bp=7, bn=8, bb=9, br=10, bq=11, bk=12
	this.board = new Array(64);
	this.toMove = 0; // White=0, Black=1
	this.castle = 16; // Wks=1, Wqs=2, Bks=4, Bqs= 8
	this.enPassant = 0;
	this.pieceChar = 'NBRQK';
  this.id='chessboard';       // Id on the displayelement

  // Methods
	this.addKingMoves = addKingMoves;
	this.addNoSlideMoves = addNoSlideMoves;
	this.addPawnMoves = addPawnMoves;
	this.addSlideMoves = addSlideMoves;
	this.clear = clear;
	this.copy = copy;
	this.doMove = doMove;
	this.generateMoves = generateMoves;
	this.getFen = getFen;
	this.getMoveText = getMoveText;
	this.getPiece = getPiece;
	this.isAttacked = isAttacked;
	this.isFileChar = isFileChar;
	this.isLegal = isLegal;
	this.isPieceChar = isPieceChar;
	this.isRowChar = isRowChar;
	this.makeMove = makeMove;
	this.pieceFromChar = pieceFromChar;
	this.pieceValue = pieceValue;
	this.setFen = setFen;
	this.setPiece = setPiece;
	this.stripMovetext = stripMovetext;
	
	function clear() {
		var sq;
		for (sq = 0; sq < 64; sq++)
			this.board[sq] = 0;
		this.castle = 0;
		this.enPassant = 0;
		this.toMove = 0;
	}

	function copy(cb) {
		var sq;
		for (sq = 0; sq < 64; sq++)
			this.board[sq] = cb.board[sq];
		this.castle = cb.castle;
		this.enPassant = cb.enPassant;
		this.toMove = cb.toMove;
		this.pieceChar=cb.pieceChar;
	}

	function getPiece(sq) {
		return this.board[sq];
	}

	function setPiece(sq, piece) {
		this.board[sq] = piece;
	}

	function setFen(fen) {
		this.clear();
		var idx = 0;
		var len = fen.length;
		var row, file;
		var rows = "12345678";
		var files = "abcdefgh";
		var c;
		while (idx < len && fen.charAt(idx) == ' ')
			++idx;
		if (idx >= len)
			return;

		row = 7;
		file = 0;
		while ((idx < len) && ((c = fen.charAt(idx)) != ' ')) {
			switch (c) {
			case 'P':
				this.board[(row * 8) + file] = 1;
				++file;
				break;
			case 'N':
				this.board[(row * 8) + file] = 2;
				++file;
				break;
			case 'B':
				this.board[(row * 8) + file] = 3;
				++file;
				break;
			case 'R':
				this.board[(row * 8) + file] = 4;
				++file;
				break;
			case 'Q':
				this.board[(row * 8) + file] = 5;
				++file;
				break;
			case 'K':
				this.board[(row * 8) + file] = 6;
				++file;
				break;
			case 'p':
				this.board[(row * 8) + file] = 7;
				++file;
				break;
			case 'n':
				this.board[(row * 8) + file] = 8;
				++file;
				break;
			case 'b':
				this.board[(row * 8) + file] = 9;
				++file;
				break;
			case 'r':
				this.board[(row * 8) + file] = 10;
				++file;
				break;
			case 'q':
				this.board[(row * 8) + file] = 11;
				++file;
				break;
			case 'k':
				this.board[(row * 8) + file] = 12;
				++file;
				break;
			case '/':
				break;
			case '1':
				file += 1;
				break;
			case '2':
				file += 2;
				break;
			case '3':
				file += 3;
				break;
			case '4':
				file += 4;
				break;
			case '5':
				file += 5;
				break;
			case '6':
				file += 6;
				break;
			case '7':
				file += 7;
				break;
			case '8':
				file += 8;
				break;
			default:
				return;
			}
			if (c != '/') {
				if (file > 7) {
					--row;
					file = 0;
				}
			}
			++idx;
			if (row < 0)
				break;
		}

		while (idx < len && fen.charAt(idx) == ' ')
			++idx;
		if (idx >= len)
			return;

		if (fen.charAt(idx) == 'b')
			this.toMove = 1;
		else
			this.toMove = 0;
		++idx;

		while (idx < len && fen.charAt(idx) == ' ')
			++idx;
		if (idx >= len)
			return;

		while ((idx < len) && ((c = fen.charAt(idx)) != ' ')) {
			switch (c) {
			case 'K':
				this.castle |= 1;
				break;
			case 'Q':
				this.castle |= 2;
				break;
			case 'k':
				this.castle |= 4;
				break;
			case 'q':
				this.castle |= 8;
				break;
			default:
				break;
			}
			++idx;
		}

		while ((idx < len) && (fen.charAt(idx) == ' '))
		{
			++idx;
		}
		if (idx >= len)
			return;

		if ((c = fen.charAt(idx)) != '-') {
			if (files.indexOf(c) >= 0) {
				this.enPassant = 0 + files.indexOf(c);
				++idx;
				c = fen.charAt(idx);
				if ((idx < len) && (rows.indexOf(c) >= 0)) {
					this.enPassant += 8 * rows.indexOf(c);
				} else {
					this.enPassant = 0;
				}
			}
		}
	}

	function getFen() {
		var fen = "";// rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP w KQkq -";
		var row, file, empty;
		var pieces = " PNBRQKpnbrqk";
		var files = "abcdefgh";
		var rows = "12345678";
		var piece;
		for (row = 7; row >= 0; row--) {
			empty = 0;
			for (file = 0; file < 8; file++) {
				piece = pieces.charAt(this.board[(row * 8) + file]);
				if (piece != ' ') {
					if (empty > 0) {
						fen += empty;
						empty = 0;
					}
					fen += piece;
				} else {
					++empty;
				}
			}
			if (empty)
				fen += empty;
			if (row != 0)
				fen += '/';
		}
		fen += ' ';

		// Who to move
		if (this.toMove == 0)
			fen += 'w';
		else
			fen += 'b';
		fen += ' ';

		// Castle
		if (this.castle != 0) {
			if (this.castle & 1)
				fen += 'K';
			if (this.castle & 2)
				fen += 'Q';
			if (this.castle & 4)
				fen += 'k';
			if (this.castle & 8)
				fen += 'q';
		} else {
			fen += '-';
		}
		fen += ' ';

		if (this.enPassant != 0) {
			row = this.enPassant >> 3;
			file = this.enPassant & 7;
			fen += files[file];
			fen += rows[row];
		} else {
			fen += '-';
		}

		// Move clock
		return fen;
	}

	function doMove(move, legaltest) {
		var piece, file, /*row,*/ from, to, promote;
		if (legaltest)
			if (!this.isLegal(move))
				return false;

		from = move & 255;
		to = (move >> 8) & 255;
		promote = move >> 16;

		// Move the piece
		piece = this.board[from];
		this.board[from] = 0;
		this.board[to] = (promote > 0) ? promote : piece;

		// Castle
		// White castle
		if (piece == 6) {
			if (to == (from + 2)) {
				this.board[7] = 0;
				this.board[5] = 4;
			} else if (to == (from - 2)) {
				this.board[0] = 0;
				this.board[3] = 4;
			}
			this.castle &= 12;
		}

		// Black castle
		if (piece == 12) {
			if (to == (from + 2)) {
				this.board[63] = 0;
				this.board[61] = 10;
			} else if (to == (from - 2)) {
				this.board[56] = 0;
				this.board[59] = 10;
			}
			this.castle &= 3;
		}

		// Castlerights
		if (piece == 4) {
			if (from == 0)
				this.castle &= 13;
			else if (from == 7)
				this.castle &= 14;
		}
		if (piece == 10) {
			if (from == 56)
				this.castle &= 7;
			else if (from == 63)
				this.castle &= 11;
		}
		if (to == 0)
			this.castle &= 13;
		if (to == 7)
			this.castle &= 14;
		if (to == 56)
			this.castle &= 7;
		if (to == 63)
			this.castle &= 11;

		// EnPassant
		if (piece == 1) {
			if (this.enPassant  && (this.enPassant == to))
				this.board[to - 8] = 0;
			this.enPassant = 0;
			if (to == (from + 16)) {
				file = to & 7;
				if ((file > 0) && (this.board[to - 1] == 7))
					this.enPassant = to - 8;
				else if ((file < 7) && (this.board[to + 1] == 7))
					this.enPassant = to - 8;
			}
		} else if (piece == 7) {
			if (this.enPassant && (this.enPassant == to))
				this.board[to + 8] = 0;
			this.enPassant = 0;
			if (to == (from - 16)) {
				file = to & 7;
				if ((file > 0) && (this.board[to - 1] == 1))
					this.enPassant = to + 8;
				else if ((file < 7) && (this.board[to + 1] == 1))
					this.enPassant = to + 8;
			}
		} else {
			this.enPassant = 0;
		}

		// Change color
		this.toMove = this.toMove ? 0 : 1;

		return true;
	}

	function isLegal(move) {
		if ((move & 255) == ((move >> 8) & 255))
			return false;

		var i;

		var ml = this.generateMoves();

		for (i = 0; i < ml.length; i++) {
			if (ml[i] == move)
				return true;
		}
		return false;
	}

	function isAttacked(sq, color) {
		var i, tofile, torow, tosq;
		var pawn = color ? 7 : 1;
		var knight = color ? 8 : 2;
		var bishop = color ? 9 : 3;
		var rook = color ? 10 : 4;
		var queen = color ? 11 : 5;
		var king = color ? 12 : 6;
		var file = sq & 7;

		// Attacked from diagonals
		i = 0;
		while (i < 8) {
			tofile = file + bishopPath[i];
			torow = (sq >> 3) + bishopPath[i + 1];
			tosq = tofile + (torow * 8);
			while ((tofile >= 0) && (tofile < 8) && (torow >= 0) && (torow < 8)) {
				if (this.board[tosq]) {
					if ((this.board[tosq] == bishop) || (this.board[tosq] == queen))
						return true;
					break;
				}
				;
				tofile += bishopPath[i];
				torow += bishopPath[(i + 1)];
				tosq = tofile + (torow * 8);
			}
			i += 2;
		}

		// Attacked from rank and file
		i = 0;
		while (i < 8) {
			tofile = file + rookPath[i];
			torow = (sq >> 3) + rookPath[i + 1];
			tosq = tofile + (torow * 8);
			while ((tofile >= 0) && (tofile < 8) && (torow >= 0) && (torow < 8)) {
				if (this.board[tosq]) {
					if ((this.board[tosq] == rook) || (this.board[tosq] == queen))
						return true;
					break;
				}
				tofile += rookPath[i];
				torow += rookPath[(i + 1)];
				tosq = tofile + (torow * 8);
			}
			i += 2;
		}

		// Attacked from knight and king
		i = 0;
		while (i < 16) {
			tofile = file + knightPath[i];
			torow = (sq >> 3) + knightPath[i + 1];
			tosq = tofile + (torow * 8);
			if ((tofile >= 0) && (tofile < 8) && (torow >= 0) && (torow < 8)) {
				if (this.board[tosq] == knight)
					return true;
			}
			;
			tofile = file + kingPath[i];
			torow = (sq >> 3) + kingPath[i + 1];
			tosq = tofile + (torow * 8);
			if ((tofile >= 0) && (tofile < 8) && (torow >= 0) && (torow < 8)) {
				if (this.board[tosq] == king)
					return true;
			}
			i += 2;
		}

		// Pawns
		tofile = file - 1;
		torow = (sq >> 3) + (color ? 1 : -1);
		tosq = tofile + (torow * 8);
		for (i = 0; i < 2; i++) {
			if ((tofile >= 0) && (tofile < 8) && (torow >= 0) && (torow < 8)) {
				if (this.board[tosq] == pawn)
					return true;
			}
			tofile = file + 1;
			tosq = tofile + (torow * 8);
		}

		return false;
	}

	function generateMoves() {
		var i, j, sq, piece, fromsq, tosq, /*file, tofile, row, torow, capfile,*/ frompiece, topiece;
		var other = this.toMove ? 0 : 1;
		var king = this.toMove ? 12 : 6;
		var ksq;
		for (ksq = 0; ksq < 64; ksq++)
			if (this.board[ksq] == king)
				break;
		var ml = new Array();
		if (ksq > 63)
			return ml;

		for (sq = 0; sq < 64; sq++) {
			piece = this.board[sq];
			if (piece && (this.toMove == (piece > 6 ? 1 : 0))) {
				if (piece > 6)
					piece -= 6;

				switch (piece) {
				case 1:
					this.addPawnMoves(ml, sq);
					break;
				case 2:
					this.addNoSlideMoves(ml, sq, knightPath);
					break;
				case 3:
					this.addSlideMoves(ml, sq, bishopPath);
					break;
				case 4:
					this.addSlideMoves(ml, sq, rookPath);
					break;
				case 5:
					this.addSlideMoves(ml, sq, bishopPath);
					this.addSlideMoves(ml, sq, rookPath);
					break;
				case 6:
					this.addNoSlideMoves(ml, sq, kingPath);
					this.addKingMoves(ml, sq);
					break;
				}
			}
		}

		// Remove illegal moves
		i = 0;
		while (i < ml.length) {
			fromsq = ml[i] & 255;
			tosq = (ml[i] >> 8) & 255;
			if (fromsq == ksq) // Kingmove
			{
				this.board[fromsq] = 0;
				if ((ksq + 2) == tosq) // Castle kingside
				{
					for (j = 0; j < 3; j++) {
						if (this.isAttacked(ksq + j, other)) {
							ml[i] = 0;
							break;
						}
					}
				} else if ((ksq - 2) == tosq) // Castle queenside
				{
					for (j = -2; j < 1; j++) {
						if (this.isAttacked(ksq + j, other)) {
							ml[i] = 0;
							break;
						}
					}
				} else {
					if (this.isAttacked(tosq, other))
						ml[i] = 0;
				}
				this.board[fromsq] = this.toMove ? 12 : 6;
			} else {
				// Check if the king is in check after the move.
				frompiece = this.board[fromsq];
				topiece = this.board[tosq];
				this.board[fromsq] = 0;
				this.board[tosq] = frompiece;
				// Remove pawn when this is an enpassant move
				if ((frompiece == ((this.toMove == 1) ? 7 : 1)) && this.enPassant && (this.enPassant == tosq))
					this.board[tosq + (this.toMove ? 8 : -8)] = 0;
				if (this.isAttacked(ksq, other))
					ml[i] = 0;
				this.board[fromsq] = frompiece;
				this.board[tosq] = topiece;
				if ((frompiece == ((this.toMove == 1) ? 7 : 1)) && this.enPassant && (this.enPassant == tosq))
					this.board[tosq + (this.toMove ? 8 : -8)] = (other ? 7 : 1);
			}
			++i;
		}

		var moves = new Array();
		for (i = 0; i < ml.length; i++)
			if (ml[i] != 0)
				moves.push(ml[i]);
		return moves;
	}

	function addPawnMoves(ml, sq) {
		var tosq, file, capfile, i;
		var pawnrow = this.toMove ? -8 : 8;

		// One square forward
		tosq = sq + pawnrow;
		if (this.board[tosq] == 0) {
			if ((tosq < 8) || (tosq > 55))
				ml.push(sq + (tosq << 8) + ((2 + this.toMove * 6) << 16), sq
						+ (tosq << 8) + ((3 + this.toMove * 6) << 16), sq + (tosq << 8)
						+ ((4 + this.toMove * 6) << 16), sq + (tosq << 8)
						+ ((5 + this.toMove * 6) << 16));
			else
				ml.push(sq + (tosq << 8));
		}

		// Capture
		file = sq & 7;
		capfile = -1;
		for (i = 0; i < 2; i++) {
			if (((file + capfile) >= 0) && ((file + capfile) < 8)) {
				tosq = sq + capfile + pawnrow;
				if ((this.board[tosq] != 0)
						&& ((this.board[tosq] > 6 ? 1 : 0) != this.toMove)) {
					if ((tosq < 8) || (tosq > 55))
						ml.push(sq + (tosq << 8) + ((2 + this.toMove * 6) << 16), sq
								+ (tosq << 8) + ((3 + this.toMove * 6) << 16), sq + (tosq << 8)
								+ ((4 + this.toMove * 6) << 16), sq + (tosq << 8)
								+ ((5 + this.toMove * 6) << 16));
					else
						ml.push(sq + (tosq << 8));
				} else if (this.enPassant && (tosq == this.enPassant)) {
					ml.push(sq + (tosq << 8));
				}
			}
			capfile = 1;
		}

		// Two square forward
		if ((((sq >> 3) == 1) && (this.toMove == 0))
				|| (((sq >> 3) == 6) && (this.toMove == 1)))
			if ((this.board[sq + pawnrow] == 0)
					&& (this.board[sq + (pawnrow * 2)] == 0))
				ml.push(sq + ((sq + (pawnrow * 2)) << 8));
	}

	function addNoSlideMoves(ml, sq, path) {
		var file, i, tofile, torow;
		file = sq & 7;
		i = 0;
		while (i < 16) {
			tofile = file + path[i];
			torow = (sq >> 3) + path[i + 1];
			if ((tofile >= 0) && (tofile < 8) && (torow >= 0) && (torow < 8)) {
				tosq = tofile + (torow << 3);
				if (!this.board[tosq]
						|| ((this.board[tosq] > 6 ? 1 : 0) != this.toMove))
					ml.push(sq + (tosq << 8));
			}
			i += 2;
		}
	}

	function addSlideMoves(ml, sq, path) {
		var file, /*other,*/ i, tofile, torow, tosq;
		file = sq & 7;
		i = 0;
//		other = this.toMove ? 0 : 1;
		while (i < 8) {
			tofile = file + path[i];
			torow = (sq >> 3) + path[i + 1];
			tosq = tofile + (torow << 3);
			while ((tofile >= 0)
					&& (tofile < 8)
					&& (torow >= 0)
					&& (torow < 8)
					&& ((this.board[tosq] == 0) || (((this.board[tosq] > 6) ? 1 : 0) != this.toMove))) {
				ml.push(sq + (tosq << 8));
				if (this.board[tosq])
					break;
				tofile += path[i];
				torow += path[i + 1];
				tosq = tofile + (torow << 3);
			}
			i += 2;
		}
	}

	/**
	 * Internal function for move generator
	 * @param ml Movelist
	 * @param sq Square of the king
	 */
	function addKingMoves(ml, sq) {
		var ctl;

		// castle
		// Make black and white castle rights look the same.
		ctl = (this.toMove) ? this.castle >> 2 : this.castle;

		if (ctl & 1)
			if ((this.board[sq + 1] == 0) && (this.board[sq + 2] == 0))
				ml.push(sq + ((sq + 2) << 8));
		if (ctl & 2)
			if ((this.board[sq - 1] == 0) && (this.board[sq - 2] == 0)
					&& (this.board[sq - 3] == 0))
				ml.push(sq + ((sq - 2) << 8));
	}

	function getMoveText(move) {
		var mt, i;
		var files = 'abcdefgh';
		var pieces = '  ' + this.pieceChar + ' ' + this.pieceChar;
		var fromsq = move & 255;
		var tosq = (move >> 8) & 255;
		var promote = move >> 16;
		var fromfile = fromsq & 7;
		var fromrow = fromsq >> 3;
		var tofile = tosq & 7;
		var torow = tosq >> 3;
		var cpiece = this.board[fromsq];
		var piece = (cpiece > 6) ? cpiece - 6 : cpiece;
		if (!piece)
			return '';
		if (piece == 1) {
			if (this.board[tosq] > 0) // Capture
				mt = files.charAt(fromfile) + 'x' + files.charAt(tofile) + (torow + 1);
			else if (fromfile != tofile) // e.p. capture
				mt = files.charAt(fromfile) + 'x' + files.charAt(tofile) + (torow + 1)
						+ ' e.p.';
			else
				mt = files.charAt(tofile) + (torow + 1);
			if (promote)
				mt += pieces.charAt(promote);
		} else if (piece == 6) {
			if (tosq == (fromsq + 2))
				mt = 'O-O';
			else if (tosq == (fromsq - 2))
				mt = 'O-O-O';
			else
				mt = 'K' + ((this.board[tosq] > 0) ? 'x' : '') + files.charAt(tofile)
						+ (torow + 1);
		} else {
			mt = pieces.charAt(piece);
			var ml1 = this.generateMoves();
			var ml2 = new Array;
			var cntf, cntr;
			for (i = 0; i < ml1.length; i++)
				if (((ml1[i] >> 8) == tosq) && (cpiece == this.board[ml1[i] & 255]))
					ml2.push(ml1[i]);
			if (!ml2.length)
				return '';

			if (ml2.length > 1) {
				cntf = 0;
				cntr = 0;
				for (i = 0; i < ml2.length; i++) {
					if (((ml2[i] & 255) & 7) == fromfile)
						++cntf;
					if (((ml2[i] & 255) >> 3) == fromrow)
						++cntr;
				}
				if (cntf == 1)
					mt += files.charAt(fromfile);
				else if (cntr == 1)
					mt += (fromrow+1);
				else
					mt += files.charAt(fromfile) + (fromrow+1);
			}
			mt += ((this.board[tosq] > 0) ? 'x' : '') + files.charAt(tofile)
					+ (torow + 1);
		}
		// Add check(mate)
		var cb = new ChessBoard;
		cb.copy(this);
		cb.doMove(move);
		var king = cb.toMove ? 12 : 6;
		for (i = 0; i < 64; i++)
			if (cb.board[i] == king)
				break;
		if (i < 64) {
			if (cb.isAttacked(i, (cb.toMove ? 0 : 1))) {
				var ml = cb.generateMoves();
				if (ml.length == 0)
					mt += '#';
				else
					mt += '+';
			}
		}
		return mt;
	}
	
	function isFileChar(c) {
		return ('abcdefgh'.indexOf(c)>=0);
	}

	function isRowChar(c) {
		return ('12345678'.indexOf(c)>=0);
	}

	function isPieceChar(c) {
		return (this.pieceChar.indexOf(c)>=0);
	}

	function pieceValue(p) {
		return (p > 6) ? p - 6 : p;
	}

	function pieceFromChar(c) {
		$i=this.pieceChar.indexOf(c);
		if ($i<0)
			return 0;
		return $i+2;
	}

	// Get an internal move from a textmove
	function makeMove(s) {
		// var s=new String(ss);
		var fileChar = 'abcdefgh';
		var rowChar = '12345678';
		var i, c, moveit, m, fsq, tsq;
		var ml = new Array();
		var allmoves;
		var frow = -1;
		var ffile = -1;
		var trow = -1;
		var tfile = -1;
		var piece = 0;
		var ppiece = 0;
		var mt = this.stripMovetext(s);
		var len = mt.length;
		if (len < 2)
			return 0;

		if (mt.substr(0, 2) == 'OO') {
			ffile = 4;
			tfile = (mt.substr(0, 3) == 'OOO') ? 2 : 6;
			frow = trow = this.toMove ? 7 : 0;
			piece = 6;
		} else {
			piece = this.pieceFromChar(mt.charAt(0));
			i = len - 1;
			if (this.isPieceChar(mt.charAt(i))) {
				ppiece = this.pieceFromChar(mt.charAt(i));
				--i;
			}
			while (i >= 0) {
				c = mt.charAt(i);
				if (this.isFileChar(c)) {
					if (tfile == -1)
						tfile = fileChar.indexOf(c);
					else
						ffile = fileChar.indexOf(c);
				} else if (isRowChar(c)) {
					if (trow == -1)
						trow = rowChar.indexOf(c);
					else
						frow = rowChar.indexOf(c);
				}
				--i;
			}
			if ((piece == 0) && (ffile == -1))
				ffile = tfile;
			if ((piece == 1) && (ffile == -1))
				ffile = tfile;
			if ((ffile >= 0) && (frow >= 0))
				piece = this.pieceValue(this.board[ffile + (8 * frow)]);
			if (piece == 0)
				piece = 1;
		}
		piece = this.toMove ? piece + 6 : piece;
		if (ppiece)
			ppiece = this.toMove ? ppiece + 6 : ppiece;
		allmoves = this.generateMoves();
		moveit = 0;
		while (moveit < allmoves.length) {
			m = allmoves[moveit];
			fsq = m & 255;
			tsq = (m >> 8) & 255;
			if (piece == this.board[fsq]) {
				if (ffile >= 0) {
					if (ffile != (fsq & 7)) {
						++moveit;
						continue;
					}
				}
				if (tfile >= 0) {
					if (tfile != (tsq & 7)) {
						++moveit;
						continue;
					}
				}
				if (frow >= 0) {
					if (frow != ((fsq >> 3) & 7)) {
						++moveit;
						continue;
					}
				}
				if (trow >= 0) {
					if (trow != ((tsq >> 3) & 7)) {
						++moveit;
						continue;
					}
				}
				if (ppiece != (m >> 16)) {
					++moveit;
					continue;
				}
				ml.push(m);
			}
			++moveit;
		}
		;
		if (ml.length == 1)
			m = ml[0];
		else
			m = 0;
		return m;
	}

	function stripMovetext(s) {
		var i = 0;
		var len = s.length;
		var mt = '';
		var c;
		c = s.charAt(i);
		while (!this.isFileChar(c) && !this.isPieceChar(c) && (c != 'O')) {
			if (i > len)
				return '';
			++i;
			c = s.charAt(i);
		}
		while (i < len) {
			c = s.charAt(i);
			if (this.isFileChar(c) || this.isRowChar(c) || this.isPieceChar(c) || (c == 'O'))
				mt += c;
			++i;
		}
		len = mt.length;
		if (len < 2)
			return '';
		if ((len > 2) && (mt.charAt(len - 1) == 'e'))
			return (mt.substr(0, len - 1));
		return mt;
	}

};

