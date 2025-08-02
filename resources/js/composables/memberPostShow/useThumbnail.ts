import { ThumbnailUrls } from '@/types';

export function useThumbnail() {
  function hasThumbnail(thumbnailUrls?: ThumbnailUrls): boolean {
    return !!thumbnailUrls && Object.values(thumbnailUrls).some(Boolean);
  }

  return { hasThumbnail };
}
