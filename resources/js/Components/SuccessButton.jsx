export default function PrimaryButton({ className = '', disabled, children, ...props }) {
    return (
        <button
            {...props}
            className={
                `bg-green-600 rounded hover:opacity-80 text-white font-semibold py-1.5 ` + className
            }
            disabled={disabled}
        >
            {children}
        </button>
    );
}
