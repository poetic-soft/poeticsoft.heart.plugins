const { useRef } = wp.element;

/**
 * Drag & Drop Zone Component.
 * Supports directory drop traversal and file selection.
 */
const DragDropZone = ({ onUploadTriggered, disabled }) => {
    const dropzoneRef = useRef(null);
    const fileSelectorRef = useRef(null);
    const folderSelectorRef = useRef(null);

    // Helper: Traverse folder files recursively
    const traverseFileTree = (item, path = '') => {
        return new Promise((resolve) => {
            if (item.isFile) {
                item.file((file) => {
                    file.customRelativePath = path + file.name;
                    resolve([file]);
                });
            } else if (item.isDirectory) {
                const dirReader = item.createReader();
                dirReader.readEntries((entries) => {
                    const promises = entries.map((entry) =>
                        traverseFileTree(entry, path + item.name + '/')
                    );
                    Promise.all(promises).then((results) => {
                        resolve(results.flat());
                    });
                });
            } else {
                resolve([]);
            }
        });
    };

    const handleDrop = async (e) => {
        e.preventDefault();
        if (disabled) return;

        const items = e.dataTransfer.items;
        if (!items) {
            onUploadTriggered(Array.from(e.dataTransfer.files));
            return;
        }

        const promises = [];
        for (let i = 0; i < items.length; i++) {
            const item = items[i].webkitGetAsEntry();
            if (item) {
                promises.push(traverseFileTree(item));
            }
        }

        const fileGroups = await Promise.all(promises);
        const allFiles = fileGroups.flat();
        onUploadTriggered(allFiles);
    };

    const handleDragOver = (e) => {
        e.preventDefault();
        if (disabled) return;
        e.currentTarget.classList.add('hover');
    };

    const handleDragLeave = (e) => {
        e.preventDefault();
        e.currentTarget.classList.remove('hover');
    };

    return (
        <div
            ref={dropzoneRef}
            className="babel-dropzone-area"
            onDragOver={handleDragOver}
            onDragLeave={handleDragLeave}
            onDrop={(e) => {
                handleDragLeave(e);
                handleDrop(e);
            }}
            onClick={() => !disabled && fileSelectorRef.current && fileSelectorRef.current.click()}
        >
            <span className="dashicons dashicons-upload icon-upload"></span>
            <h2>Drag & Drop files or folders here</h2>
            <p className="formats">Supported formats: .md, .txt, .pdf, .docx</p>

            <div className="babel-dropzone-actions" onClick={(e) => e.stopPropagation()}>
                <button
                    type="button"
                    className="button button-primary"
                    onClick={() => fileSelectorRef.current && fileSelectorRef.current.click()}
                    disabled={disabled}
                >
                    Select Files
                </button>
                <button
                    type="button"
                    className="button button-secondary"
                    onClick={() => folderSelectorRef.current && folderSelectorRef.current.click()}
                    disabled={disabled}
                >
                    Select Folder
                </button>
            </div>

            <input
                type="file"
                ref={fileSelectorRef}
                multiple
                style={{ display: 'none' }}
                accept=".md,.txt,.pdf,.docx"
                onChange={(e) => {
                    onUploadTriggered(Array.from(e.target.files || []));
                    e.target.value = '';
                }}
                disabled={disabled}
            />
            <input
                type="file"
                ref={folderSelectorRef}
                webkitdirectory=""
                directory=""
                multiple
                style={{ display: 'none' }}
                onChange={(e) => {
                    onUploadTriggered(Array.from(e.target.files || []));
                    e.target.value = '';
                }}
                disabled={disabled}
            />
        </div>
    );
};

export default DragDropZone;
