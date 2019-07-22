import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class Loader extends Component {
    constructor(props) {
        super(props);
    }
    render() {
        return (
            <div className="loader">
            	<div className="load-bar">
            		<div className="bar"></div>
            		<div className="bar"></div>
            		<div className="bar"></div>
            	</div>
            </div>
        );
    }
}