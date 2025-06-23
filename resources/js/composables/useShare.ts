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
    const fbUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
    window.open(fbUrl, '_blank', 'noopener,noreferrer');
  }

  return {
    showMenu,
    copyLink,
    share,
    openShare,
    shareOnFacebook
  };
}