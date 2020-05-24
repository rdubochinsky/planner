import React, { Component } from 'react';
import {addTaskDate, addTaskName, updateInputValue} from '../../store/task/actions';
import store from '../../store/store';
import {connect} from 'react-redux';

class TaskInput extends Component {
    onChange(event) {
        const name = event.target.value.trim();

        if (name.length === 0) {
            store.dispatch(addTaskDate(null));
        }

        store.dispatch(addTaskName(event.target.value));
        store.dispatch(updateInputValue(event.target.value));
    }

    render() {
        return (
            <input type={'text'}
                   name={'task_name'}
                   value={this.props.inputValue}
                   placeholder={'I\'d like to ...'}
                   onChange={this.onChange}/>
        );
    }
}

const mapStateToProps = (state) => {
    return {
        inputValue: state.task.inputValue,
    };
};

export default connect(mapStateToProps)(TaskInput);
