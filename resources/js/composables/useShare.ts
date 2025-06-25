import { ref } from 'vue';

declare global {
  interface Window {
    FB: any;
  }
}

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

  function waitForFB(): Promise<void> {
    return new Promise((resolve) => {
      const check = () => {
        if (window.FB) {
          resolve();
        } else {
          setTimeout(check, 100); // espera 100ms y vuelve a intentar
        }
      };
      check();
    });
  }

  async function shareOnFacebook() {
    await waitForFB();

    window.FB.ui({
      method: 'share',
      href: url,
    }, () => {
      // callback opcional
    });
  }

  return {
    showMenu,
    copyLink,
    share,
    openShare,
    shareOnFacebook
  };
}