import {TOGGLE_CALENDAR} from './types';

const initialState = {isOpened: false};

export default (state = initialState, action) => {
    if (action.type === TOGGLE_CALENDAR) {
        return {
            ...state,
            isOpened: action.isOpened
        };
    }

    return state;
}