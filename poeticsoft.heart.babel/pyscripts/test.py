import chromadb
import json
from rich import print, print_json
from config_db import get_collection

def auditar():
    
    coleccion = get_collection()
    
    total_elementos = coleccion.count()
    print(f"📊 La colección tiene un total de {total_elementos} documentos.")
    datos = coleccion.get(
        limit=total_elementos, 
        include=["documents", "metadatas"]
    )

    nombre_archivo = "croma-content.json"
    print(f"💾 Guardando y formateando datos en '{nombre_archivo}'...")
    
    with open(nombre_archivo, "w", encoding="utf-8") as archivo:
        json.dump(datos, archivo, indent=4, ensure_ascii=False)
        
    print(f"✨ ¡Hecho! Todo se ha guardado correctamente de forma limpia y ordenada.")
if __name__ == "__main__":
    auditar()