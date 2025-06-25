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

    const isMobile = /Mobi|Android|iPhone|iPad|iPod/i.test(navigator.userAgent);

    if (isMobile) {
      // Intenta abrir la app
      const appLink = `fb://facewebmodal/f?href=${encodedUrl}`;
      window.location.href = appLink;

      // Opcional: Fallback si falla (muy breve delay)
      setTimeout(() => {
        // Abre la versión web si no funcionó
        window.location.href = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;
      }, 1500); // Le da chance al sistema de abrir la app
    } else {
      // En escritorio: abrir en nueva pestaña
      window.open(
        `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`,
        '_blank',
        'noopener,noreferrer'
      );
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