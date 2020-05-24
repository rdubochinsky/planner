import {TOGGLE_CALENDAR} from './types';
import store from '../store';

export const toggleCalendar = () => {
    return {
        type: TOGGLE_CALENDAR,
        isOpened: !store.getState().calendar.isOpened,
    }
};
