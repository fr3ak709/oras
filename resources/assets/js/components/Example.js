import React, { Component } from 'react';
import ReactDOM from 'react-dom';

export default class Example extends Component {
    
    render() {
        // run in cmd 'npm run dev' 
        // to compile this into public/js/app.js file
        return (
            <div className="container">
                <div className="row justify-content-center">
                    <div className="col-md-8">
                        <div className="card">
                            <div className="card-header">Example Component</div>

                            <div className="card-body">
                               qwell
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        );
    }
}

if (document.getElementById('react-element')) {
    ReactDOM.render(<Example />, document.getElementById('react-element'));
}
