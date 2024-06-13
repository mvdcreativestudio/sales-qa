window.toggleStoreStatusClosed = async function(storeId) {
    console.log(`Store ID: ${storeId}`);

    try {
        const response =  await fetch(`admin/stores/`+storeId+`/toggle-store-status-closed`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
        });

        if (response.ok) {
            const data =  await response.json();
            console.log('Estado de la tienda actualizado con éxito:', data);
        } else {
            const errorData =  await response.json();
            console.error('Error en la respuesta del servidor:', errorData);
        }
    } catch (error) {
        console.error('Hubo un problema al intentar cambiar el estado de la tienda:', error);
    }
};
