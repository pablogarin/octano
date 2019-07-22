import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class MoveForm extends Component {
    render() {
        return (
            <div className="card">
                <div className="card-header">
                    <h2 className="lead"></h2>
                </div>
                <div className="card-body">
                    <form>
                        <input type="text" name="name" />
                        <button className="btn btn-primary">Save</button>
                    </form>
                </div>
            </div>
        );
    }
}