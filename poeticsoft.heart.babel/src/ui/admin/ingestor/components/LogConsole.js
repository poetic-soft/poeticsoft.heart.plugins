const { useEffect, useRef } = wp.element;

/**
 * Log Console Component.
 * Automatically scrolls to bottom when logs are updated.
 */
const LogConsole = ({ logs, className = '' }) => {
    const consoleRef = useRef(null);

    useEffect(() => {
        if (consoleRef.current) {
            consoleRef.current.scrollTop = consoleRef.current.scrollHeight;
        }
    }, [logs]);

    if (!logs || logs.length === 0) return null;

    return (
        <div className={`log-console ${className}`} ref={consoleRef}>
            {logs.map((log, index) => (
                <div key={index} className={`log-line ${log.type}`}>
                    {log.message}
                </div>
            ))}
        </div>
    );
};

export default LogConsole;
