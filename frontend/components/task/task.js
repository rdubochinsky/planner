import React, { Component } from 'react';
import moment from 'moment';
import TaskCompletedIcon from './taskCompletedIcon';
import TaskNotCompletedIcon from './taskNotCompletedIcon';
import TaskDeleteIcon from './taskDeleteIcon';

class Task extends Component {
    render() {
        const task = this.props.task;

        return (
            <li>
                <div>
                    {task.isCompleted ? <TaskCompletedIcon task={task} /> : <TaskNotCompletedIcon task={task} />}
                </div>
                <div className={'name ' + (task.isCompleted ? 'line-through' : '')}>
                    <p>
                        {task.name}
                        <span className={'date'}>
                            {task.date ? moment(task.date).format('DD/MM/YYYY') : ''}
                        </span>
                    </p>
                </div>
                <div>{task.isCompleted ? <TaskDeleteIcon task={task} /> : ''}</div>
            </li>
        );
    }
}

export default Task;
