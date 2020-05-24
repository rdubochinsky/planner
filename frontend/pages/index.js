import React, { Component } from 'react';
import {connect} from 'react-redux';
import Task from '../components/task/task';
import Calendar from '../components/calendar';
import TaskAddIcon from '../components/task/taskAddIcon';
import TaskInput from '../components/task/taskInput';
import {fetchTasks} from '../store/task/actions';
import store from '../store/store';

class Index extends Component {
    componentDidMount() {
        store.dispatch(fetchTasks());
    }

    render() {
        let isInputPopulated = this.props.newTask.name.trim().length !== 0;

        return (
            <div className={'central-container'}>
                <h1 className={'white'}>My Planner</h1>

                <div className={'content-container'}>
                    <div className={'input-container'}>
                        <TaskInput />

                        <TaskAddIcon isVisible={isInputPopulated}
                                     task={this.props.newTask} />

                        <Calendar isVisible={isInputPopulated}
                                  selectedDate={this.props.newTask.date} />
                    </div>
                    <ul>
                        {this.props.tasks.map(task => <Task key={task.id} task={task} />)}
                    </ul>
                </div>
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        tasks: state.task.items,
        newTask: state.task.newItem,
    };
};

export default connect(mapStateToProps)(Index);
