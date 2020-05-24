import React, { Component } from 'react';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {faCheckCircle} from '@fortawesome/free-regular-svg-icons';
import {markTaskAsUncompleted} from '../../store/task/actions';
import store from '../../store/store';

class TaskCompletedIcon extends Component {
    render() {
        return (
            <FontAwesomeIcon icon={faCheckCircle}
                             className={'check-circle'}
                             onClick={() => {store.dispatch(markTaskAsUncompleted(this.props.task))}} />
        );
    }
}

export default TaskCompletedIcon;
