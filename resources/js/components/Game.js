import React, { Component } from 'react';
import ReactDOM from 'react-dom';

import Round from './Round';

export default class Game extends Component {
    constructor(props) {
        super(props);
        this.props.startRound();
    }
    componentWillMount() {
        const roundIndex = this.props.game.rounds.length - 1;
        this.round = this.props.game.rounds[roundIndex];
    }
    componentWillUpdate(nextProps, nextState) {
        const roundIndex = nextProps.game.rounds.length - 1;
        if ( this.round != nextProps.game.rounds[roundIndex] ) {
            this.round  = nextProps.game.rounds[roundIndex];
            this.rounds = nextProps.game.rounds.map((item, key) => (
                <tr key={item.round_number}>
                    <td>{ item.round_number }</td>
                    <td>{ item.round_winner.name ? item.round_winner.name : '---' }</td>
                </tr>
                )
            );
        }
    }
    render() {
        if ( this.props.game.ended ) {
            return (
                <div className="card">
                    <div className="card-header">
                        <h2 className="lead text-center">We have a WINNER!</h2>
                    </div>
                    <div className="card-body text-center">
                        <h2 className="">{this.props.winnerName} is the new EMPEROR!</h2>
                        <a href="/" className="btn btn-primary">Play Again!</a>
                    </div>
                </div>
            );
        }
        return (
            <div className="card">
                <div className="card-header">
                    <h2 className="lead">Round {this.round.round_number}</h2>
                </div>
                <div className="card-body">
                    <div className="row">
                        <div className="col-md-6">
                            <Round
                                player={this.round.player_one_move == '' ? this.props.playerOne : this.props.playerTwo}
                                moves={this.props.moves}
                                setPlayerMove={this.props.setPlayerMove}
                            />
                        </div>  
                        <div className="col-md-6">
                            <h5>Score</h5>
                            <table className="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Round</th>
                                        <th>Winner</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    { this.props.game.rounds.length > 1 ? this.rounds : false }
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}