import chromadb
from chromadb.utils.embedding_functions import OllamaEmbeddingFunction
CHROMA_HOST = 'localhost'
CHROMA_PORT = 8000
collection_name = 'poeticsoft-heart-babel'
ollama_url = 'http://localhost:11434/api/embeddings'
ollama_model_name = 'nomic-embed-text'

def get_collection():
    
    client = chromadb.HttpClient(CHROMA_HOST, CHROMA_PORT)
    
    ollama_ef = OllamaEmbeddingFunction(
        url = ollama_url,
        model_name = ollama_model_name
    )
    
    return client.get_collection(
        name=collection_name, 
        embedding_function=ollama_ef
    )