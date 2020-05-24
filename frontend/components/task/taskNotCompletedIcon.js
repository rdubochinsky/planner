import React, { Component } from 'react';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {faCircle} from '@fortawesome/free-regular-svg-icons';
import {markTaskAsCompleted} from '../../store/task/actions';
import store from '../../store/store';

class TaskNotCompletedIcon extends Component {
    render() {
        return (
            <FontAwesomeIcon icon={faCircle}
                             className={'circle'}
                             onClick={() => {store.dispatch(markTaskAsCompleted(this.props.task))}} />
        );
    }
}

export default TaskNotCompletedIcon;
