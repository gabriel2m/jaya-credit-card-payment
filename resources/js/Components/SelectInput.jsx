import { forwardRef, useRef } from 'react';

export default forwardRef(function SelectInput({ className = '', options = [], ...props }, ref) {
    const _ref = useRef();
    const input = ref ? ref : _ref;

    return (
        <select
            {...props}
            type="select"
            className={
                'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm ' +
                className
            }
            ref={input}
        >
            {options ? options.map(
                (option) => <option key={option.value} value={option.value}>{option.label}</option>
            ) : ''}
        </select>
    );
});
