/**
 * Storage Inbox Component.
 * Displays list of raw pending files and action buttons to process them.
 */
const StorageInbox = ({ pendingFiles, onIngestTriggered, disabled }) => {
    return (
        <div className="babel-dashboard-card">
            <h2 className="card-title">Babel Storage Inbox (raw/)</h2>

            {pendingFiles.length === 0 ? (
                <div className="empty-state">
                    <span className="dashicons dashicons-yes-alt success-icon"></span>
                    <p>No pending files in raw storage. Upload some files or folders to begin.</p>
                </div>
            ) : (
                <>
                    <div className="babel-file-tree">
                        <ul>
                            {pendingFiles.map((file, index) => (
                                <li key={index}>
                                    <span className="file-info">
                                        <span
                                            className={`dashicons ${file.endsWith('.pdf') ? 'dashicons-pdf' : 'dashicons-document'}`}
                                        ></span>
                                        {file}
                                    </span>
                                    <span className="file-ext">
                                        {file.split('.').pop().toUpperCase()}
                                    </span>
                                </li>
                            ))}
                        </ul>
                    </div>

                    <div className="card-actions">
                        <button
                            type="button"
                            className="button button-primary button-large"
                            onClick={onIngestTriggered}
                            disabled={disabled}
                        >
                            Process and Convert Inbox (Asynchronously)
                        </button>
                        <span className="action-desc">
                            {pendingFiles.length} {pendingFiles.length === 1 ? 'file' : 'files'}{' '}
                            ready to be converted into Markdown and vectorized chunk-by-chunk.
                        </span>
                    </div>
                    <p
                        style={{
                            fontSize: '11px',
                            color: '#888',
                            marginTop: '10px',
                            fontStyle: 'italic'
                        }}
                    >
                        Note: Processing runs on a secure, single-request AJAX queue to guarantee
                        zero server timeouts even on very large corpus books.
                    </p>
                </>
            )}
        </div>
    );
};

export default StorageInbox;
