import React, { Component } from 'react';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {faTrashAlt} from '@fortawesome/free-regular-svg-icons';
import {deleteTask} from '../../store/task/actions';
import store from '../../store/store';

class TaskDeleteIcon extends Component {
    render() {
        return (
            <FontAwesomeIcon icon={faTrashAlt}
                             className={'trash'}
                             onClick={() => {store.dispatch(deleteTask(this.props.task))}} />
        );
    }
}

export default TaskDeleteIcon;
