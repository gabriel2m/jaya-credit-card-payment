import { forwardRef, useEffect, useRef } from 'react';
import Inputmask from "inputmask";

export default forwardRef(function TextInput({ type = 'text', className = '', isFocused = false, mask = null, ...props }, ref) {
    const _ref = useRef();
    const input = ref ? ref : _ref;

    useEffect(() => {
        if (isFocused) {
            input.current.focus();
        }

        if (mask) {
            Inputmask(mask).mask(input.current)
        }
    }, []);

    return (
        <input
            {...props}
            type={type}
            className={
                'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm ' +
                className
            }
            ref={input}
        />
    );
});
