import React, { Component } from 'react';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {faPlusCircle} from '@fortawesome/free-solid-svg-icons';
import {createTask} from '../../store/task/actions';
import store from '../../store/store';

class TaskAddIcon extends Component {
    render() {
        return (
            <FontAwesomeIcon icon={faPlusCircle}
                             className={'icon' + (this.props.isVisible ? '' : ' hidden')}
                             onClick={() => {store.dispatch(createTask(this.props.task))}}/>
        );
    }
}

export default TaskAddIcon;
