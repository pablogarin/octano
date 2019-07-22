import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import PlayerForm from './PlayerForm';
import Game from './Game';
import Loader from './Loader';

export default class Main extends Component {
	constructor(props) {
		super(props);
		this.state = {
            loading: false,
            game: {
                id: 0,
                ended: false,
                rounds: []
            },
            playersNum : 0,
            playerOne : {
                id: 0,
                name : ''
            },
            playerTwo : {
                id: 0,
                name : ''
            },
            moves: [],
            winner: null
        };
        this.onPlayerChange      = this.onPlayerChange.bind(this);
        this.onUpdatePlayerCount = this.onUpdatePlayerCount.bind(this);
        this.initializeGame      = this.initializeGame.bind(this);
        this.startRound          = this.startRound.bind(this);
        this.finishRound         = this.finishRound.bind(this);
        this.setLoader           = this.setLoader.bind(this);
        this.getMoveList         = this.getMoveList.bind(this);
        this.setPlayerMove       = this.setPlayerMove.bind(this);
        this.endGame             = this.endGame.bind(this);
	}
    componentDidMount() {
        this.setLoader();
        this.getMoveList(moves => {
            this.setLoader();
        });
    }
    setLoader() {
        this.setState((state) => ({loading:!state.loading}))
    }
    onPlayerChange( name, id = 0 ) {
        const player = {name, id};
        this.state.playersNum == 0
            ? this.setState((state) => ({playerOne:{...state.playerOne, ...player}}))
            : this.setState((state) => ({playerTwo:{...state.playerTwo, ...player}}));
    }
    initializeGame(id) {
        this.setState((state) => ({game:{...state.game, id}}));
    }
    startRound() {
        let   rounds = this.state.game.rounds;
        const round  = {
            id: 0,
            round_number: (rounds.length +1),
            round_winner: 0,
            player_one_move: '',
            player_two_move: ''
        };
        rounds.push(round);
        this.setState((state) => ({game:{...state.game, rounds:rounds}}));
    }
    finishRound(roundIndex) {
        let rounds = this.state.game.rounds;
        let round  = rounds[roundIndex];
        if ( round.player_one_move.kills == round.player_two_move.id ) {
            round.round_winner = this.state.playerOne;
        }
        if ( round.player_two_move.kills == round.player_one_move.id ) {
            round.round_winner = this.state.playerTwo;
        }
        rounds[roundIndex] = round;
        this.setLoader();
        axios.post('/api/rounds', {
            round_number    : round.round_number,
            game_id         : this.state.game.id,
            round_winner    : round.round_winner.id,
            player_one_move : round.player_one_move.name,
            player_two_move : round.player_two_move.name,
        }).then(response=>{
            console.log(response);
            this.setState((state)=>({game:{...state.game, rounds}}));
            if ( rounds.length >= 0 ) {
                let playerOneCount = 0,
                    playerTwoCount = 0;
                rounds.forEach(r=>{
                    if ( this.state.playerOne.id == r.round_winner.id ) {
                        playerOneCount = playerOneCount+1;
                    }
                    if ( this.state.playerTwo.id == r.round_winner.id ) {
                        playerTwoCount = playerTwoCount+1;
                    }
                });
                if ( playerOneCount == 3 || playerTwoCount == 3 ) {
                    let winner = null;
                    if ( playerOneCount == 3 ) {
                        winner = this.state.playerOne.name;
                    }
                    if ( playerTwoCount == 3 ) {
                        winner = this.state.playerTwo.name;
                    }
                    this.setState((state)=>({ game:{...state.game, ended: true}, winner }));
                    this.endGame();
                } else {
                    this.startRound();
                }
            }
        }).catch(error=>{
            console.warn(error);
        }).finally(_=>{
            this.setLoader();
        });
    }
    getMoveList( callback = null ) {
        axios.get('/api/moves').then( response => {
            if ( response.status == 200 ) {
                const data = response.data;
                this.setState({moves:data});
                if ( typeof callback === 'function' ) {
                    callback(data);
                }
            }
            if ( response.status >= 400 ) {
                alert("Unexpected Error");
            }
        }).catch( error => {
            console.warn(error);
            this.setStatus({loading:false});
        });
    }
    onUpdatePlayerCount() {
        const playersNum = this.state.playersNum + 1;
        this.setState({playersNum});
        if ( this.state.playersNum == 2 ) {
            this.setLoader();
            axios.post('/api/games', {
                player_one: this.state.playerOne.id,
                player_two: this.state.playerTwo.id,
            }).then( response => {
                if ( response.status == 201 ) {
                    const data = response.data;
                    this.initializeGame(data.id);
                }
            }).catch( error => {
                console.warn(error);
            }).finally(_=>{
                this.setLoader();
            });
        }
    }
    setPlayerMove(player, move) {
        let   game        = this.state.game;
        const roundIndex  = game.rounds.length-1;
        let   round       = game.rounds[roundIndex];
        let   finishRound = false;
        if( player == this.state.playerOne ) {
            round.player_one_move = move;
        }
        if( player == this.state.playerTwo ) {
            round.player_two_move = move;
            finishRound = true;
        }
        game.rounds[roundIndex] = round;
        this.setState((state)=>({game:game}));
        if ( finishRound ) {
            this.finishRound(roundIndex);
        }
    }
    endGame() {
        const game = {...this.state.game};
        console.log(`/api/games/${game.id}`, game);
    }
	render() {
        return (
            <div className="container mt-5">
                { this.state.loading ? <Loader /> : '' }
                <div className="row justify-content-center">
                    <div className="col-md-8">
                        {	this.state.game.id > 0
                            ? (
                                <Game 
                                    game={this.state.game}
                                    moves={this.state.moves}
                                    playerOne={this.state.playerOne} 
                                    playerTwo={this.state.playerTwo}
                                    startRound={this.startRound}
                                    finishRound={this.finishRound}
                                    getMoveList={this.getMoveList}
                                    setPlayerMove={this.setPlayerMove}
                                    winnerName={this.state.winner}
                                    />
                            )
                        	: (
                                <PlayerForm 
                                    player={ this.state.playersNum < 1 ? this.state.playerOne : this.state.playerTwo }
                                    number={this.state.playersNum} 
                                    onPlayerChange={this.onPlayerChange} 
                                    onUpdatePlayerCount={this.onUpdatePlayerCount} 
                                    setLoader={this.setLoader}/>
                            )
                    	}
                    </div>
                </div>
            </div>
        );
    }
}


const APP_CONTAINER = "app";
if (document.getElementById(APP_CONTAINER)) {
    ReactDOM.render(<Main />, document.getElementById(APP_CONTAINER));
}