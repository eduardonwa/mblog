import { ref } from 'vue';

export function useShare(url: string, title = '', text = '') {
  const showMenu = ref(false);

  async function copyLink() {
    try {
      await navigator.clipboard.writeText(url);
      alert("Copied to clipboard");
    } catch (e) {
      if (import.meta.env.DEV) {
        console.error(e);
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

    // Por ahora, misma lógica para ambos: abrir en nueva pestaña
    const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodedUrl}`;

    window.open(
      shareUrl,
      '_blank',
      'noopener,noreferrer'
    );

    // console.log(`Sharing ${shareUrl} on Facebook from ${isMobile ? 'móvil' : 'escritorio'}`);
  }

  return {
    showMenu,
    copyLink,
    share,
    openShare,
    shareOnFacebook
  };
}