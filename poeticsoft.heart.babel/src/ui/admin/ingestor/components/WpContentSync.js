import LogConsole from './LogConsole';

/**
 * WordPress Content Sync Component.
 */
const WpContentSync = ({ taggedPostsCount, onSyncTriggered, syncingWP, wpSyncLogs, disabled }) => {
    return (
        <div className="babel-dashboard-card">
            <h2 className="card-title">WordPress Content Sync</h2>
            <p className="card-desc">
                Synchronize WordPress posts, pages, and custom post types tagged with "babel"
                (including published, private, and scheduled/future content).
            </p>

            <div className={`sync-status-box ${taggedPostsCount > 0 ? 'pending' : 'synced'}`}>
                <div className="status-message">
                    {taggedPostsCount > 0 ? (
                        <>
                            <span className="message-label">Pending/Modified Documents:</span>
                            <span className="count-badge">{taggedPostsCount}</span>
                        </>
                    ) : (
                        <>
                            <span className="dashicons dashicons-yes-alt success-icon"></span>
                            <span>All documents are fully synchronized!</span>
                        </>
                    )}
                </div>
                <div className="status-action">
                    <button
                        type="button"
                        className="button button-secondary"
                        onClick={onSyncTriggered}
                        disabled={taggedPostsCount === 0 || syncingWP || disabled}
                    >
                        {syncingWP ? 'Synchronizing...' : 'Sync Tagged WordPress Content'}
                    </button>
                </div>
            </div>

            <LogConsole
                logs={wpSyncLogs}
                className="wp-sync-console"
                style={{ marginTop: '15px' }}
            />
        </div>
    );
};

export default WpContentSync;
