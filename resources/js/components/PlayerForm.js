import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class PlayerForm extends Component {
    constructor(props) {
        super(props);
        this.handleNameChange = this.handleNameChange.bind(this);
        this.createPlayer     = this.createPlayer.bind(this);
    }
    handleNameChange(event) {
        const name = event.target.value;
        this.props.onPlayerChange(name);
    }
    createPlayer(event) {
        event.preventDefault();
        const player = this.props.player;
        this.props.setLoader();
        axios.post(
            '/api/players', 
            player
        ).then( response => {
            if( response.status == 201 ) {
                const   data = response.data,
                        id   = data.id,
                        name = data.name;
                this.props.onPlayerChange(name, id);
                this.props.onUpdatePlayerCount();
            }
        }).catch( error => {
            console.log(error);
        }).finally(_=>{
            this.props.setLoader();
        });
    }
    render() {
        return (
            <div className="card">
                <div className="card-header">
                    <h2 className="lead">Enter Palyer's Names</h2>
                </div>
                <div className="card-body">
                    <h5>Player {this.props.number == 0 ? 'One' : 'Two'}</h5>
                    <form onSubmit={this.createPlayer} >
                        <div className="row p-3 mt-3 mb-3">
                            <label className="col-md-4">Player Name</label>
                            <input 
                                type="text" 
                                className="col-md-8 form-control"
                                value={this.props.player.name} 
                                onChange={this.handleNameChange} 
                                />
                        </div>
                        <div className="row mt-3 mb-3">
                            <button className="btn btn-primary form-control" type="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        );
    }
}