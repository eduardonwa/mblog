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
    const url = "https://sickofmetal.net/tu-articulo"; // URL a compartir
    const encodedUrl = encodeURIComponent(url);
    const isMobile = /Android|iPhone|iPad/i.test(navigator.userAgent);

    // 1. Intenta abrir la app de Facebook en móvil
    if (isMobile) {
      window.location.href = `fb://facewebmodal/f?href=${encodedUrl}`;
      
      // 2. Fallback después de 500ms (si no se abrió la app)
      setTimeout(() => {
        if (!document.hidden) {
          window.open(
            `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`,
            '_blank',
            'noopener,noreferrer'
          );
        }
      }, 500);
    } else {
      // 3. En desktop: abre directamente en nueva pestaña
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