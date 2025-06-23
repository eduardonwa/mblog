import { ref } from 'vue';

export function useShare(url: string, title = '', text = '') {
  const showMenu = ref(false);

  async function copyLink() {
    try {
      await navigator.clipboard.writeText(url);
      alert("¡Enlace copiado!");
    } catch (e) {
      if (import.meta.env.DEV) {
        alert("No se pudo copiar el enlace. ¿Estás en desarrollo? Prueba en producción o usa HTTPS.");
      } else {
        alert("No se pudo copiar el enlace.");
      }
    }
  }

  async function share() {
    if (navigator.share) {
      try {
        await navigator.share({ title, text, url });
      } catch {
        // El usuario canceló o falló el share
      }
    } else {
      copyLink();
    }
  }

  function openShare(shareUrl: string) {
    window.open(shareUrl, "_blank", "noopener,noreferrer");
  }

  function shareOnFacebook() {
    // 1. Usa URL canónica absoluta (sin parámetros de sesión)
    const canonicalUrl = window.location.origin + window.location.pathname;
    
    // 2. Construye URL de Facebook con parámetros anti-caché
    const fbUrl = new URL('https://www.facebook.com/dialog/share');
    fbUrl.searchParams.append('app_id', '145634995501895'); // App ID público
    fbUrl.searchParams.append('href', canonicalUrl);
    fbUrl.searchParams.append('display', 'popup');
    fbUrl.searchParams.append('redirect_uri', 'https://www.facebook.com');
    
    // 3. Abre ventana con características específicas
    const windowFeatures = 'width=600,height=400,noopener,noreferrer,resizable=yes';
    window.open(fbUrl.toString(), 'fb_share', windowFeatures);
  }

  return {
    showMenu,
    copyLink,
    share,
    openShare,
    shareOnFacebook
  };
}