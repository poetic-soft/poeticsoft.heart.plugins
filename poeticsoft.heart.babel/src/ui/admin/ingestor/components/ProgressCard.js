import LogConsole from './LogConsole';

/**
 * Progress Card Component.
 * Displays titles, custom progress bars, and scrolling consoles.
 */
const ProgressCard = ({
    title,
    progress,
    logs,
    completed,
    completionMessage,
    progressBarColor = '#46b450'
}) => {
    return (
        <div className="babel-dashboard-card progress-card">
            <div className="progress-header">
                <h3>{title}</h3>
                <span className="percentage" style={{ color: progressBarColor }}>
                    {progress}%
                </span>
            </div>
            <div className="progress-bar-container">
                <div
                    className="progress-bar"
                    style={{
                        width: `${progress}%`,
                        backgroundColor: progressBarColor
                    }}
                ></div>
            </div>

            <LogConsole logs={logs} />

            {completed && (
                <div className="completion-badge">
                    <span className="dashicons dashicons-yes-alt"></span>
                    {completionMessage}
                </div>
            )}
        </div>
    );
};

export default ProgressCard;
