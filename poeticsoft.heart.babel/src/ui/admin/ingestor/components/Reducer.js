const { useReducer } = wp.element;

// 1. Initial State Definition
const initialState = {
    rootData: {},
    pendingFiles: [],
    taggedPostsCount: 0,

    // Upload State
    uploading: false,
    uploadProgress: 0,
    uploadLogs: [],

    // Ingest State
    ingesting: false,
    ingestProgress: 0,
    ingestLogs: [],
    ingestCompleted: false,

    // WP Sync State
    syncingWP: false,
    wpSyncLogs: []
};

// 2. State Reducer Definition (Deterministic State Flows)
const ingestorReducer = (state, action) => {
    switch (action.type) {
        case 'INITIALIZE':
            return {
                ...state,
                rootData: action.payload.rootData,
                pendingFiles: action.payload.pendingFiles,
                taggedPostsCount: action.payload.taggedPostsCount
            };

        // Upload Actions
        case 'UPLOAD_START':
            return {
                ...state,
                uploading: true,
                uploadProgress: 0,
                uploadLogs: [],
                ingestProgress: 0,
                ingestLogs: [],
                ingestCompleted: false
            };
        case 'ADD_UPLOAD_LOG':
            return {
                ...state,
                uploadLogs: [...state.uploadLogs, action.payload]
            };
        case 'UPDATE_LAST_UPLOAD_LOG':
            return {
                ...state,
                uploadLogs: state.uploadLogs.map((log, index) =>
                    index === state.uploadLogs.length - 1 ? action.payload : log
                )
            };
        case 'SET_UPLOAD_PROGRESS':
            return {
                ...state,
                uploadProgress: action.payload
            };
        case 'UPLOAD_COMPLETE': {
            const newFiles = (action.payload && action.payload.newFiles) ? action.payload.newFiles : [];
            const updatedPendingFiles = Array.from(new Set([...state.pendingFiles, ...newFiles]));
            return {
                ...state,
                uploading: false,
                pendingFiles: updatedPendingFiles
            };
        }

        // Ingestion Actions
        case 'INGEST_START':
            return {
                ...state,
                ingesting: true,
                ingestProgress: 0,
                ingestLogs: [],
                ingestCompleted: false
            };
        case 'ADD_INGEST_LOG':
            return {
                ...state,
                ingestLogs: [...state.ingestLogs, action.payload]
            };
        case 'UPDATE_LAST_INGEST_LOG':
            return {
                ...state,
                ingestLogs: state.ingestLogs.map((log, index) =>
                    index === state.ingestLogs.length - 1 ? action.payload : log
                )
            };
        case 'SET_INGEST_PROGRESS':
            return {
                ...state,
                ingestProgress: action.payload
            };
        case 'INGEST_COMPLETE':
            return {
                ...state,
                ingesting: false,
                ingestCompleted: true,
                pendingFiles: []
            };

        // WP Sync Actions
        case 'WP_SYNC_START':
            return {
                ...state,
                syncingWP: true,
                wpSyncLogs: [action.payload]
            };
        case 'ADD_WP_SYNC_LOG':
            return {
                ...state,
                wpSyncLogs: [...state.wpSyncLogs, action.payload]
            };
        case 'WP_SYNC_COMPLETE':
            return {
                ...state,
                syncingWP: false,
                taggedPostsCount: action.payload.success ? 0 : state.taggedPostsCount
            };

        default:
            return state;
    }
};

export default () => useReducer(ingestorReducer, initialState);
