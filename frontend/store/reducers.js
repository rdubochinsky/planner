import taskReducer from './task/reducer';
import calendarReducer from './calendar/reducer';
import {combineReducers} from 'redux';

export default combineReducers({
    task: taskReducer,
    calendar: calendarReducer,
});
