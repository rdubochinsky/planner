import React, { Component } from 'react';
import {FontAwesomeIcon} from '@fortawesome/react-fontawesome';
import {faCalendarAlt} from '@fortawesome/free-regular-svg-icons';
import moment from 'moment';
import ReactCalendar from 'react-calendar';
import {toggleCalendar} from '../store/calendar/actions';
import {addTaskDate} from '../store/task/actions';
import store from '../store/store';
import {connect} from 'react-redux';

class Calendar extends Component {
    onChange(date) {
        if (null !== date) {
            store.dispatch(toggleCalendar());
            store.dispatch(addTaskDate(date));
        }
    }

    render() {
        return (
            <div className={'calendar' + (this.props.isVisible ? '' : ' hidden')}>
                <span className={'selected-date'}>
                    {
                        this.props.selectedDate
                            ? moment(this.props.selectedDate).format('DD/MM/YYYY')
                            : ''
                    }
                </span>

                <FontAwesomeIcon icon={faCalendarAlt}
                                 className={'icon'}
                                 onClick={() => {store.dispatch(toggleCalendar())}} />

                <ReactCalendar className={this.props.isOpened ? '' : 'hidden'}
                               minDate={new Date()}
                               defaultValue={this.props.selectedDate}
                               onChange={this.onChange} />
            </div>
        );
    }
}

const mapStateToProps = (state) => {
    return {isOpened: state.calendar.isOpened};
};

export default connect(mapStateToProps)(Calendar);