import {
    FETCH_TASKS,
    CREATE_TASK,
    UPDATE_TASK,
    MARK_TASK_AS_COMPLETED,
    MARK_TASK_AS_UNCOMPLETED,
    DELETE_TASK,
    UPDATE_NEW_TASK,
    UPDATE_TASKS,
    UPDATE_INPUT_VALUE,
} from './types';
import store from '../store';

export const fetchTasks = () => {
    fetch(process.env.NEXT_PUBLIC_API_HOST + '/api/tasks')
        .then(response => response.json())
        .then(resp => resp)
        .then(tasks => {
            store.dispatch(updateStoreTasks(tasks));
        });

    return {type: FETCH_TASKS};
};

export const createTask = (task) => {
    fetch(process.env.NEXT_PUBLIC_API_HOST + '/api/tasks', {
        method: 'post',
        body: JSON.stringify(task),
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
    }).then(() => {
        store.dispatch(resetStoreNewTask());
        store.dispatch(fetchTasks());
        store.dispatch(updateInputValue(''));
    });

    return {type: CREATE_TASK}
};

export const updateTask = (updatedTask) => {
    const tasks = store.getState().task.items.map((task) => {
            if (task.id !== updatedTask.id) {
                return task;
            }

            return {...updatedTask}
        });

    store.dispatch(updateStoreTasks(tasks));

    fetch(process.env.NEXT_PUBLIC_API_HOST + '/api/tasks/' + updatedTask.id, {
        method: 'put',
        body: JSON.stringify({...updatedTask, id: undefined}),
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
        },
    }).then(() => {
        store.dispatch(fetchTasks());
    });

    return {type: UPDATE_TASK}
};

export const deleteTask = (task) => {
    fetch(process.env.NEXT_PUBLIC_API_HOST + '/api/tasks/' + task.id, {
        method: 'delete',
    }).then(() => {
        store.dispatch(fetchTasks());
    });

    return  {type: DELETE_TASK}
};

export const markTaskAsCompleted = (task) => {
    store.dispatch(updateTask({...task, isCompleted: true}));

    return {type: MARK_TASK_AS_COMPLETED}
};

export const markTaskAsUncompleted = (task) => {
    store.dispatch(updateTask({...task, isCompleted: false}));

    return {type: MARK_TASK_AS_UNCOMPLETED}
};

export const addTaskDate = (date) => {
    return {
        type: UPDATE_NEW_TASK,
        newTask: {
            ...store.getState().task.newItem,
            date: date,
        }
    }
};

export const addTaskName = (name) => {
    return {
        type: UPDATE_NEW_TASK,
        newTask: {
            ...store.getState().task.newItem,
            name: name,
        }
    }
};

export const updateStoreTasks = (tasks) => {
    return {
        type: UPDATE_TASKS,
        tasks: tasks,
    }
};

export const resetStoreNewTask = () => {
    return {
        type: UPDATE_NEW_TASK,
        newTask: {name: '', date: null}
    }
};

export const updateInputValue = (value) => {
    return {
        type: UPDATE_INPUT_VALUE,
        value: value
    }
};