import {UPDATE_INPUT_VALUE, UPDATE_NEW_TASK, UPDATE_TASKS} from './types';

const initialState = {
    items: [],
    newItem: {
        name: '',
        date: null,
        isCompleted: false,
    },
    inputValue: '',
};

export default (state = initialState, action) => {
    switch(action.type) {
        case UPDATE_TASKS:
            return {
                ...state,
                items: action.tasks
            };

        case UPDATE_NEW_TASK:
            return {
                ...state,
                newItem: action.newTask
            };

        case UPDATE_INPUT_VALUE:
            return {
                ...state,
                inputValue: action.value
            };

        default:
            return state;
    }
}