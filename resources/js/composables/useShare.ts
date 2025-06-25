import { ref } from 'vue';

export function useShare(url: string, title = '', text = '') {
  const showMenu = ref(false);

  async function copyLink() {
    try {
      await navigator.clipboard.writeText(url);
      alert("Copied to clipboard");
    } catch (e) {
      if (import.meta.env.DEV) {
        alert("Couldn't copy link. Are you in development? Try in production or use HTTPS.");
      } else {
        alert("Failed to copy link.");
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
    const encodedUrl = encodeURIComponent(url);
    const isMobile = /Android|iPhone|iPad|iPod/i.test(navigator.userAgent);
    const webUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;

    if (isMobile) {
      // 1° Intento: Abrir la app de Facebook (si está instalada)
      window.location.href = `fb://share?href=${encodedUrl}`;
      
      // 2° Intento (fallback): Si no se abre la app en 300ms, abre la web
      setTimeout(() => {
        if (!document.hidden) { // Si la app no interceptó la navegación
          window.open(webUrl, '_blank', 'noopener,noreferrer');
        }
      }, 300);
    } else {
      // Escritorio: Abre en nueva pestaña
      window.open(webUrl, '_blank', 'noopener,noreferrer');
    }
  }

  return {
    showMenu,
    copyLink,
    share,
    openShare,
    shareOnFacebook
  };
}