const { useEffect } = wp.element;
import DashboardHeader from './components/DashboardHeader';
import DragDropZone from './components/DragDropZone';
import ProgressCard from './components/ProgressCard';
import StorageInbox from './components/StorageInbox';
import WpContentSync from './components/WpContentSync';
import reducer from './components/Reducer';
import './main.scss';

const { createRoot } = wp.element;

/**
 * Main Babel Ingestor Application Controller (Reducer orchestrated).
 */
const IngestorApp = () => {
    const [state, dispatch] = reducer();

    const {
        rootData,
        pendingFiles,
        taggedPostsCount,
        uploading,
        uploadProgress,
        uploadLogs,
        ingesting,
        ingestProgress,
        ingestLogs,
        ingestCompleted,
        syncingWP,
        wpSyncLogs
    } = state;

    // Load Initial Configuration from HTML Mount Node
    useEffect(() => {
        const node = document.getElementById('babel-ingestor-root');
        if (node) {
            const files = JSON.parse(node.getAttribute('data-pending-files') || '[]');
            const postsCount = parseInt(node.getAttribute('data-tagged-posts-count') || '0', 10);

            dispatch({
                type: 'INITIALIZE',
                payload: {
                    rootData: {
                        rawDirPath: node.getAttribute('data-raw-dir-path') || '',
                        apiUploadUrl: node.getAttribute('data-api-upload-url') || '',
                        apiIngestUrl: node.getAttribute('data-api-ingest-url') || '',
                        nonce: node.getAttribute('data-nonce') || '',
                        convertedPostsDir: node.getAttribute('data-converted-posts-dir') || ''
                    },
                    pendingFiles: files,
                    taggedPostsCount: postsCount
                }
            });
        }
    }, []);

    // Upload Process Coordinator
    const handleUpload = async (files) => {
        dispatch({ type: 'UPLOAD_START' });

        let uploadedCount = 0;
        const totalFiles = files.length;
        const successfulUploads = [];

        for (let i = 0; i < totalFiles; i++) {
            const file = files[i];
            const relativePath = file.customRelativePath || file.webkitRelativePath || file.name;

            dispatch({
                type: 'ADD_UPLOAD_LOG',
                payload: {
                    type: 'pending',
                    message: `⏳ Uploading (${i + 1}/${totalFiles}): ${relativePath}...`
                }
            });

            try {
                const formData = new FormData();
                formData.append('file', file);
                formData.append('relative_path', relativePath);

                const response = await fetch(rootData.apiUploadUrl, {
                    method: 'POST',
                    headers: {
                        'X-WP-Nonce': rootData.nonce
                    },
                    body: formData
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    dispatch({
                        type: 'UPDATE_LAST_UPLOAD_LOG',
                        payload: {
                            type: 'success',
                            message: `✓ Successfully uploaded: ${relativePath}`
                        }
                    });
                    const uploadedPath = (result.data && result.data.relative_path) ? result.data.relative_path : relativePath;
                    successfulUploads.push(uploadedPath);
                } else {
                    const errorMsg = result.error ? result.error.message : 'Unknown error';
                    dispatch({
                        type: 'UPDATE_LAST_UPLOAD_LOG',
                        payload: {
                            type: 'error',
                            message: `✗ Error uploading ${relativePath}: ${errorMsg}`
                        }
                    });
                }
            } catch (err) {
                dispatch({
                    type: 'UPDATE_LAST_UPLOAD_LOG',
                    payload: {
                        type: 'error',
                        message: `✗ Connection Failed for ${relativePath}`
                    }
                });
            }

            uploadedCount++;
            dispatch({
                type: 'SET_UPLOAD_PROGRESS',
                payload: Math.round((uploadedCount / totalFiles) * 100)
            });
        }

        // Completed, notify state to show completed status and update files
        setTimeout(() => {
            dispatch({
                type: 'UPLOAD_COMPLETE',
                payload: { newFiles: successfulUploads }
            });
        }, 1000);
    };

    // Asynchronous Document-by-Document Ingestion Queue (The Timeout-Killer)
    const handleIngest = async () => {
        dispatch({ type: 'INGEST_START' });

        let processedCount = 0;
        const totalFiles = pendingFiles.length;

        for (let i = 0; i < totalFiles; i++) {
            const file = pendingFiles[i];

            dispatch({
                type: 'ADD_INGEST_LOG',
                payload: {
                    type: 'pending',
                    message: `⏳ Processing (${i + 1}/${totalFiles}): ${file}...`
                }
            });

            try {
                const response = await fetch(rootData.apiIngestUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-WP-Nonce': rootData.nonce
                    },
                    body: JSON.stringify({ file: file })
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    dispatch({
                        type: 'UPDATE_LAST_INGEST_LOG',
                        payload: {
                            type: 'success',
                            message: `✓ Fully parsed, converted, and indexed in ChromaDB: ${file}`
                        }
                    });
                } else {
                    const errorMsg = result.error ? result.error.message : 'Unknown error';
                    dispatch({
                        type: 'UPDATE_LAST_INGEST_LOG',
                        payload: {
                            type: 'error',
                            message: `✗ Failed to process ${file}: ${errorMsg}`
                        }
                    });
                }
            } catch (err) {
                dispatch({
                    type: 'UPDATE_LAST_INGEST_LOG',
                    payload: {
                        type: 'error',
                        message: `✗ Network/Timeout error processing ${file}`
                    }
                });
            }

            processedCount++;
            dispatch({
                type: 'SET_INGEST_PROGRESS',
                payload: Math.round((processedCount / totalFiles) * 100)
            });
        }

        dispatch({ type: 'INGEST_COMPLETE' });
    };

    // Async WordPress Content Synchronization
    const handleWpSync = async () => {
        dispatch({
            type: 'WP_SYNC_START',
            payload: {
                type: 'pending',
                message: '⏳ Initializing WordPress Content Sync and Chunking...'
            }
        });

        try {
            const apiSyncUrl = rootData.apiUploadUrl.replace('/upload', '/sync-posts');
            const response = await fetch(apiSyncUrl, {
                method: 'POST',
                headers: {
                    'X-WP-Nonce': rootData.nonce
                }
            });

            const result = await response.json();

            if (response.ok && result.success) {
                const data = result.data;
                const failedList = data.failed || [];

                dispatch({
                    type: 'ADD_WP_SYNC_LOG',
                    payload: {
                        type: 'success',
                        message: `✓ Successfully converted & synchronized ${data.success} WordPress posts/pages!`
                    }
                });

                if (failedList.length > 0) {
                    failedList.forEach((item) => {
                        dispatch({
                            type: 'ADD_WP_SYNC_LOG',
                            payload: {
                                type: 'error',
                                message: `✗ Post ID ${item.id} failed: ${item.message}`
                            }
                        });
                    });
                }

                dispatch({ type: 'WP_SYNC_COMPLETE', payload: { success: true } });
            } else {
                const errorMsg = result.error ? result.error.message : 'Unknown error';
                dispatch({
                    type: 'ADD_WP_SYNC_LOG',
                    payload: { type: 'error', message: `✗ WordPress Sync Failed: ${errorMsg}` }
                });
                dispatch({ type: 'WP_SYNC_COMPLETE', payload: { success: false } });
            }
        } catch (err) {
            dispatch({
                type: 'ADD_WP_SYNC_LOG',
                payload: {
                    type: 'error',
                    message: '✗ Connection or Server timeout during WordPress Sync.'
                }
            });
            dispatch({ type: 'WP_SYNC_COMPLETE', payload: { success: false } });
        }
    };

    const globalDisabled = uploading || ingesting || syncingWP;

    return (
        <div className="babel-ingestion-dashboard">
            {/* Title and Header Banner */}
            <DashboardHeader />

            {/* Interactive Drag and Drop Upload Zone */}
            <DragDropZone onUploadTriggered={handleUpload} disabled={globalDisabled} />

            {/* Upload Feedback Progress Bar and Logs */}
            {(uploading || uploadLogs.length > 0) && (
                <ProgressCard
                    title="Uploading Files to Raw Storage..."
                    progress={uploadProgress}
                    logs={uploadLogs}
                    completed={!uploading && uploadProgress === 100}
                    completionMessage="All files successfully uploaded to raw storage!"
                    progressBarColor="#007cba"
                />
            )}

            {/* Ingestion Queue Feedback Progress Bar and Logs */}
            {(ingesting || ingestLogs.length > 0) && (
                <ProgressCard
                    title="Vectorizing Knowledge Inbox..."
                    progress={ingestProgress}
                    logs={ingestLogs}
                    completed={ingestCompleted}
                    completionMessage="All files successfully processed, converted and indexed in ChromaDB!"
                    progressBarColor="#334155"
                />
            )}

            {/* Pending Raw Files Inbox Section */}
            <StorageInbox
                pendingFiles={pendingFiles}
                onIngestTriggered={handleIngest}
                disabled={globalDisabled}
            />

            {/* WordPress Content Sync and Realtime Vectorization Section */}
            <WpContentSync
                taggedPostsCount={taggedPostsCount}
                onSyncTriggered={handleWpSync}
                syncingWP={syncingWP}
                wpSyncLogs={wpSyncLogs}
                disabled={globalDisabled}
            />
        </div>
    );
};

const container = document.getElementById('babel-ingestor-root');
if (container) {
    const root = createRoot(container);
    root.render(<IngestorApp />);
}
