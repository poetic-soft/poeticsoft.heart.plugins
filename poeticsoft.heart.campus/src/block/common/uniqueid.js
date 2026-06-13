import { v4 as uuidv4 } from 'uuid';
const { useEffect } = wp.element;

const seenBlockIds = new Map();

/**
 * Hook para gestionar un blockId único y persistente.
 *
 * @param {string} clientId El clientId del bloque proporcionado por Gutenberg.
 * @param {Object} attributes Los atributos del bloque.
 * @param {Function} setAttributes Función para actualizar los atributos.
 */
export const useUniqueId = (clientId, attributes, setAttributes) => {
    const { blockId, refClientId } = attributes;

    useEffect(() => {
        if (!blockId) {
            const newId = uuidv4();
            setAttributes({
                blockId: newId,
                refClientId: clientId
            });
            seenBlockIds.set(newId, clientId);
        } else {
            if (seenBlockIds.has(blockId) && seenBlockIds.get(blockId) !== clientId) {
                const newId = uuidv4();
                setAttributes({
                    blockId: newId,
                    refClientId: clientId
                });
                seenBlockIds.set(newId, clientId);
            } else {
                seenBlockIds.set(blockId, clientId);

                if (refClientId !== clientId) {
                    setAttributes({ refClientId: clientId });
                }
            }
        }
    }, []);
};
