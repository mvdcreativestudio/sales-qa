document.addEventListener('DOMContentLoaded', () => {
    window.toggleStoreStatusClosed = async function(storeId) {
      console.log(`Store ID: ${storeId}`);
  
      const url = `${window.baseUrl}admin/stores/${storeId}/toggle-store-status-closed`;
  
      try {
          const response =  await fetch(`admin/stores/`+ storeId +`/toggle-store-status-closed`, {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
              },
          });
  
          if (response.ok) {
              const data =  await response.json();
              location.reload();
          } else {
              const errorData =  await response.json();
          }
      } catch (error) {
          console.error('Hubo un problema al intentar cambiar el estado de la tienda:', error);
      }
 };})
  
