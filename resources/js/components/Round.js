import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class Round extends Component {
    constructor(props) {
        super(props);
        this.state = {
            selected: -1
        };
        this.handleMoveChange = this.handleMoveChange.bind(this);
        this.savePlayerMove   = this.savePlayerMove.bind(this);
    }
    componentWillMount() {
        this.currentMove = null;
        this.moves = this.props.moves.map( (item,key) => 
            <option key={item.id} value={item.id}>{item.name}</option>
        );
    }
    handleMoveChange(event) {
        const moveID = event.target.value;
        const move   = this.props.moves.filter(m=>m.id==moveID)[0];
        if( moveID != -1 ) {
            this.currentMove = move;
        }
        this.setState({selected:moveID});
    }
    savePlayerMove(event) {
        event.preventDefault();
        this.props.setPlayerMove(this.props.player, this.currentMove);
        this.currentMove = null;
        this.setState({selected:-1});
    }
    render() {
        return (
            <form onSubmit={this.savePlayerMove}>
                <h5>{this.props.player.name}</h5>
                <div className="form-group row">
                    <label>Select Move: </label>
                    <select 
                        className="form-control" 
                        onChange={this.handleMoveChange}
                        value={this.state.selected}
                    >
                        <option value="-1">-- Select --</option>
                        {this.moves}
                    </select>
                </div>
                <div className="form-group">
                    <button className="btn btn-primary form-control" type="submit">Ok</button>
                </div>
            </form>
        );
    }
}